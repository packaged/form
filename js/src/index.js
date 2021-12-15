import {clearErrors, validateForm, validateHandler} from './validation.js';

export * from './validation.js';

let _init = new WeakSet();

export function init(rootElement = document)
{
  if(_init.has(rootElement))
  {
    return;
  }
  _init.add(rootElement);

  rootElement.addEventListener('submit', e =>
  {
    /**
     * @type {HTMLFormElement}
     */
    const form = e.path && e.path[0] || e.target;
    const results = validateForm(form);
    e.detail = e.detail || {};
    e.detail['@packaged/form'] = {validation: results};
    if(!results.isValid)
    {
      e.preventDefault();
    }
  });

  rootElement.addEventListener('reset', e =>
  {
    /**
     * @type {HTMLFormElement}
     */
    const form = e.path && e.path[0] || e.target;
    form.querySelectorAll('.p-form__field[handler-name]')
        .forEach((ele) => clearErrors(form, ele.getAttribute('handler-name')));
  });

  rootElement.addEventListener('input', e =>
  {
    const inputEle = e.path && e.path[0] || e.target;
    _validateInput(inputEle, false);
  });

  rootElement.addEventListener('focusout', e =>
  {
    const inputEle = e.path && e.path[0] || e.target;
    _validateInput(inputEle, true);
  });
}

function _validateInput(inputEle, errorOnPotentiallyValid = false)
{
  const handlerContainer = inputEle.closest('.p-form__field');
  if(!handlerContainer || !handlerContainer.hasAttribute('handler-name'))
  {
    return;
  }
  const handlerName = handlerContainer.getAttribute('handler-name');

  const form = inputEle.closest('form');
  if(!form)
  {
    return;
  }
  validateHandler(form, handlerName, errorOnPotentiallyValid);
}
