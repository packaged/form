import base64 from 'base-64';
import {DataSetValidator, ValidationResponse, Validator} from '@packaged/validate';
import {ConfirmationValidator} from '@packaged/validate/js/validators/ConfirmationValidator.js';
import {WeakMappedSet} from './WeakMappedSet.js';

function _getEleValue(ele)
{
  if((!(ele.type === 'checkbox' || ele.type === 'radio')) || ele instanceof HTMLSelectElement || ele.checked)
  {
    return ele.value;
  }
  return null;
}

function _getHandlerScope(form, handlerName)
{
  return form.querySelector(`.p-form__field[handler-name="${handlerName}"]`);
}

function _getHandlerValue(form, handlerName)
{
  let fieldValue = null;
  const handlerScope = _getHandlerScope(form, handlerName);
  if(!handlerScope)
  {
    return fieldValue;
  }

  const inputs = handlerScope.querySelectorAll(`:scope [name="${handlerName}"], :scope [name^="${handlerName}["]`);
  if(inputs.length === 1)
  {
    fieldValue = _getEleValue(inputs[0]);
  }
  else if(inputs.length > 1)
  {
    fieldValue = [];
    inputs.forEach(
      ele =>
      {
        const eleVal = _getEleValue(ele);
        if(eleVal === null)
        {
          return;
        }
        if(ele.getAttribute('name').substr(-1) === ']')
        {
          if(!fieldValue || fieldValue.constructor.name !== 'Array')
          {
            fieldValue = [];
          }
          fieldValue.push(eleVal);
        }
        else
        {
          fieldValue = eleVal;
        }
      }
    );
  }
  return fieldValue;
}

/**
 *
 * @param {HTMLFormElement} form
 * @param {String} handlerName
 * @param {ValidationResponse} result
 * @param {Boolean} errorOnPotentiallyValid
 * @private
 */
function _updateValidationState(form, handlerName, result, errorOnPotentiallyValid = false)
{
  const handlerScope = _getHandlerScope(form, handlerName);
  if(!handlerScope)
  {
    return;
  }
  let state = 'valid';
  if(result.errors.length > 0)
  {
    if(result.potentiallyValid && !errorOnPotentiallyValid)
    {
      state = 'potentially-valid';
    }
    else
    {
      state = 'invalid';
    }
  }
  handlerScope.setAttribute('validation-state', state);
}

/**
 * @param {HTMLFormElement} form
 * @param {String} handlerName
 */
export function clearErrors(form, handlerName)
{
  const handlerScope = _getHandlerScope(form, handlerName);
  if(!handlerScope)
  {
    return;
  }
  handlerScope.removeAttribute('validation-state');

  const errContainer = handlerScope.querySelector(`.p-form__errors`);
  if(errContainer)
  {
    errContainer.innerHTML = '';
  }
}

/**
 * @param {HTMLFormElement} form
 * @param {String} handlerName
 * @param {String[]} errors
 */
export function addErrors(form, handlerName, errors = [])
{
  if(errors.length <= 0)
  {
    return;
  }
  const errContainer = form.querySelector(`.p-form__field[handler-name="${handlerName}"] .p-form__errors`);
  if(!errContainer)
  {
    console.error('validation error:', `"${handlerName}"`, errors);
    return;
  }

  _updateValidationState(form, handlerName, ValidationResponse.error(errors));
  const errUl = errContainer.querySelector(':scope > ul') || document.createElement('ul');
  errors.forEach((err) =>
                 {
                   const errEle = document.createElement('li');
                   errEle.innerText = err;
                   errUl.append(errEle);
                 });
  errContainer.append(errUl);
}

const _reverseConfirmations = new WeakMappedSet();

export function validateHandler(form, handlerName, errorOnPotentiallyValid = false, _processedMap = new WeakMap)
{
  const fieldValue = _getHandlerValue(form, handlerName);
  const handlerScope = _getHandlerScope(form, handlerName);
  const result = ValidationResponse.success();
  if(!handlerScope)
  {
    return result;
  }
  if(_processedMap.has(handlerScope))
  {
    return _processedMap.get(handlerScope);
  }

  const validators = _getValidators(handlerScope);

  try
  {
    const formData = new FormData(form);
    const data = {};
    for(let [key, val] of formData.entries())
    {
      data[key] = val;
    }

    validators.forEach(
      (validator) =>
      {
        if(validator instanceof DataSetValidator)
        {
          validator.setData(data);
        }
        if(validator instanceof ConfirmationValidator)
        {
          const fld = _getHandlerScope(form, validator._field);
          _reverseConfirmations.add(fld, handlerName);
        }
        result.combine(validator.validate(fieldValue));
      }
    );
  }
  catch(e)
  {
  }

  _processedMap.set(handlerScope, result);

  const confirms = _reverseConfirmations.get(handlerScope);
  if(confirms)
  {
    confirms.forEach(
      (c) =>
      {
        validateHandler(form, c, true, _processedMap);
      }
    );
  }

  _updateValidationState(form, handlerName, result, errorOnPotentiallyValid);

  clearErrors(form, handlerName);
  if(!result.potentiallyValid || errorOnPotentiallyValid)
  {
    addErrors(form, handlerName, result.errors);
  }

  return result;
}

/**
 * @param {HTMLFormElement} form
 * @return {Map<String,ValidationResponse>}
 */
export function validateForm(form)
{
  const fullResult = new Map();
  if(!(form instanceof HTMLFormElement))
  {
    console.error('not a form element');
    return fullResult;
  }

  const _processedMap = new WeakMap;
  const fields = form.querySelectorAll('.p-form__field[validation]');
  fields.forEach(
    (container) =>
    {
      const handlerName = container.getAttribute('handler-name');
      const result = validateHandler(form, handlerName, true, _processedMap);
      fullResult.set(handlerName, result);
    }
  );

  return fullResult;
}

const _validatorsMap = new WeakMap();

function _getValidators(handlerScope)
{
  if(!_validatorsMap.has(handlerScope))
  {
    const validationString = handlerScope.getAttribute('validation');
    if(validationString)
    {
      const validatorsObj = JSON.parse(base64.decode(validationString));
      const validators = validatorsObj.map(
        (validatorObj) =>
        {
          try
          {
            return Validator.fromJsonObject(validatorObj);
          }
          catch(e)
          {
            return null;
          }
        }
      );
      _validatorsMap.set(handlerScope, validators.filter(v => v instanceof Validator));
    }
  }
  return _validatorsMap.get(handlerScope);
}
