import base64 from 'base-64';
import {ValidationResponse, Validator} from '@packaged/validate';

function _getEleValue(ele) {
  if((!(ele.type === 'checkbox' || ele.type === 'radio')) || ele instanceof HTMLSelectElement || ele.checked)
  {
    return ele.value;
  }
  return null;
}

function _getFieldValue(form, fieldName) {
  let fieldValue = null;
  const inputs = form.querySelectorAll(`[name="${fieldName}"]`);
  if(inputs.length > 1)
  {
    fieldValue = [];
    inputs.forEach(
      ele => {
        const eleVal = _getEleValue(ele);
        if(eleVal === null)
        {
          return;
        }
        if(fieldName.substr(-1) === ']')
        {
          if(fieldValue.constructor.name !== 'Array')
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
  else if(inputs.length === 1)
  {
    fieldValue = _getEleValue(inputs[0]);
  }
  return fieldValue;
}

/**
 *
 * @param {HTMLFormElement} form
 * @param {String} name
 * @param {ValidationResponse} result
 * @param {Boolean} errorOnPotentiallyValid
 * @private
 */
function _updateValidationState(form, name, result, errorOnPotentiallyValid = false) {
  const container = form.querySelector(`.p-form__field[name="${name}"]`);
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
  container.setAttribute('validation-state', state);
}

/**
 * @param {HTMLFormElement} form
 * @param {String} name
 * @param {String[]} errors
 */
export function addErrors(form, name, errors = []) {
  if(errors.length <= 0)
  {
    return;
  }
  const errContainer = form.querySelector(`.p-form__field[name="${name}"] .p-form__errors`);
  const errUl = errContainer.querySelector(':scope > ul') || document.createElement('ul');
  errors.forEach(
    (err) => {
      const errEle = document.createElement('li');
      errEle.innerText = err;
      errUl.append(errEle);
    }
  );
  errContainer.append(errUl);
}

/**
 * @param {HTMLFormElement} form
 * @param {String} name
 */
export function clearErrors(form, name) {
  const errContainer = form.querySelector(`.p-form__field[name="${name}"] .p-form__errors`);
  errContainer.innerHTML = '';
}

export function validateField(form, fieldName, errorOnPotentiallyValid = false) {
  let fieldValue = _getFieldValue(form, fieldName);
  const container = form.querySelector(`.p-form__field[name="${fieldName}"]`);

  const result = ValidationResponse.success();
  const validators = JSON.parse(base64.decode(container.getAttribute('validation')));
  validators.forEach(
    (validatorObj) => {
      try
      {
        const validator = Validator.fromJsonObject(validatorObj);
        result.combine(validator.validate(fieldValue));
      }
      catch(e)
      {
      }
    }
  );

  _updateValidationState(form, fieldName, result, errorOnPotentiallyValid);
  return result;
}

/**
 * @param {HTMLFormElement} form
 * @return {Map<String,ValidationResponse>}
 */
export function validateForm(form) {
  const fullResult = new Map();
  if(!form instanceof HTMLFormElement)
  {
    console.error('not a form element');
    return fullResult;
  }

  const fields = form.querySelectorAll('.p-form__field[validation]');
  fields.forEach(
    (container) => {
      const fieldName = container.getAttribute('name');
      const result = validateField(form, fieldName, true);
      fullResult.set(fieldName, result);
    }
  );

  return fullResult;
}
