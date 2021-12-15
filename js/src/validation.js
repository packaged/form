import base64 from 'base-64';
import {DataSetValidator, ValidationResponse, Validator} from '@packaged/validate';
import {ConfirmationValidator} from '@packaged/validate/js/validators/ConfirmationValidator.js';
import {WeakMappedSet} from './WeakMappedSet.js';

/**
 * @type {WeakMap<HTMLElement, ValidationResponse>}
 * @private
 */
const _errorMap = new WeakMap();

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
    inputs.forEach(ele =>
    {
      const eleVal = _getEleValue(ele);
      if(eleVal === null)
      {
        return;
      }
      if(ele.getAttribute('name')
            .substr(-1) === ']')
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
    });
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

  _errorMap.delete(handlerScope);

  const errContainer = handlerScope.querySelector(`.p-form__errors`);
  if(errContainer)
  {
    errContainer.innerHTML = '';
  }
}

export function getErrors(form, handlerName)
{
  const handlerScope = _getHandlerScope(form, handlerName);
  return _errorMap.get(handlerScope);
}

/**
 * @param {HTMLFormElement} form
 * @param {String} handlerName
 * @param {ValidationResponse} validationResponse
 * @param {Boolean} errorOnPotentiallyValid
 */
export function addErrors(form, handlerName, validationResponse, errorOnPotentiallyValid = false)
{
  if(!validationResponse)
  {
    return;
  }
  if(validationResponse.errors <= 0)
  {
    return;
  }
  _updateValidationState(form, handlerName, validationResponse, errorOnPotentiallyValid);

  const handlerScope = _getHandlerScope(form, handlerName);
  const errContainer = handlerScope.querySelector(`.p-form__errors`);
  if(!errContainer)
  {
    return;
  }

  const errUl = errContainer.querySelector(':scope > ul') || document.createElement('ul');
  validationResponse.errors.forEach((err) =>
  {
    const errEle = document.createElement('li');
    errEle.innerText = err;
    errUl.append(errEle);
  });
  errContainer.append(errUl);
  _errorMap.set(handlerScope, validationResponse);
}

const _reverseConfirmations = new WeakMappedSet();

export function validateHandler(form, handlerName, errorOnPotentiallyValid = false, _processedMap = new WeakMap)
{
  const fieldValue = _getHandlerValue(form, handlerName);
  const handlerScope = _getHandlerScope(form, handlerName);
  if(!handlerScope)
  {
    return ValidationResponse.error(['handler not found']);
  }
  if(_processedMap.has(handlerScope))
  {
    return _processedMap.get(handlerScope);
  }

  const result = ValidationResponse.success();
  const validators = _getValidators(handlerScope);

  try
  {
    const formData = new FormData(form);
    const data = {};
    for(let [key, val] of formData.entries())
    {
      data[key] = val;
    }

    validators.forEach((validator) =>
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
    });
  }
  catch(e)
  {
  }

  _processedMap.set(handlerScope, result);

  const confirms = _reverseConfirmations.get(handlerScope);
  if(confirms)
  {
    confirms.forEach((c) =>
    {
      validateHandler(form, c, true, _processedMap);
    });
  }

  clearErrors(form, handlerName);
  if(result.errors && (!result.potentiallyValid || errorOnPotentiallyValid))
  {
    addErrors(form, handlerName, result, errorOnPotentiallyValid);
  }

  form.dispatchEvent(new CustomEvent('form-handler-validation',
    {detail: {handlerName, result}, bubbles: true, cancellable: false}
  ));

  return result;
}

/**
 * @param {HTMLFormElement} form
 * @return {ValidationResults}
 */
export function validateForm(form)
{
  const fullResult = new ValidationResults();
  if(!(form instanceof HTMLFormElement))
  {
    console.error('not a form element');
    return fullResult;
  }

  const _processedMap = new WeakMap;
  const fields = form.querySelectorAll('.p-form__field[validation]');
  fields.forEach((container) =>
  {
    const handlerName = container.getAttribute('handler-name');
    const result = validateHandler(form, handlerName, true, _processedMap);
    fullResult.append(handlerName, result);
  });

  form.dispatchEvent(new CustomEvent('form-validation',
    {detail: {result: fullResult}, bubbles: true, cancellable: false}
  ));
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
      const validators = validatorsObj.map((validatorObj) =>
      {
        try
        {
          return Validator.fromJsonObject(validatorObj);
        }
        catch(e)
        {
          return null;
        }
      });
      _validatorsMap.set(handlerScope, validators.filter(v => v instanceof Validator));
    }
  }
  return _validatorsMap.get(handlerScope);
}

export class ValidationResults
{
  constructor()
  {
    /**
     * @type {boolean}
     * @private
     */
    this._isValid = true;
    /**
     * @type {Map<String, ValidationResponse>}
     * @private
     */
    this._results = new Map();
  }

  get isValid()
  {
    return this._isValid;
  }

  get results()
  {
    return this._results;
  }

  /**
   * @param {String} key
   * @param {ValidationResponse} result
   */
  append(key, result)
  {
    this._results.set(key, result);
    this._isValid = this._isValid && result.errors.length === 0;
  }
}
