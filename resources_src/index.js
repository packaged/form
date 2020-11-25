import {addErrors, clearErrors, validateForm, validateHandler} from './validation';

document.addEventListener(
  'submit', e =>
  {
    /**
     * @type {HTMLFormElement}
     */
    const form = e.target;
    const results = validateForm(form);
    results.forEach(
      (result, handlerName) =>
      {
        // show errors if necessary
        const errContainer = e.target.querySelector(`.p-form__field[name="${handlerName}"] .p-form__errors`);
        if(errContainer)
        {
          errContainer.classList.toggle('p-form__errors--hidden', result.errors.length === 0);
        }

        if(result.errors.length > 0)
        {
          clearErrors(form, handlerName);
          addErrors(form, handlerName, result.errors);
          e.preventDefault();
        }
      }
    );
  }
);

document.addEventListener('input', e =>
{
  const inputEle = e.target;
  const handlerContainer = inputEle.closest('.p-form__field');
  if(!handlerContainer || !handlerContainer.hasAttribute('name'))
  {
    return;
  }
  const handlerName = handlerContainer.getAttribute('name');

  const form = inputEle.closest('form');
  if(!form)
  {
    return;
  }

  const result = validateHandler(form, handlerName);
  const errContainer = form.querySelector(`.p-form__field[name="${handlerName}"] .p-form__errors`);
  if(errContainer && result.errors.length === 0)
  {
    clearErrors(form, handlerName);
    errContainer.classList.add('p-form__errors--hidden');
  }
  else if(!result.potentiallyValid)
  {
    clearErrors(form, handlerName);
    addErrors(form, handlerName, result.errors);
  }
});
