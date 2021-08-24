import {addErrors, clearErrors, validateForm, validateHandler} from './validation';

document.addEventListener(
  'submit', e =>
  {
    /**
     * @type {HTMLFormElement}
     */
    const form = e.path && e.path[0] || e.target;
    const results = validateForm(form);
    results.forEach(
      (result, handlerName) =>
      {
        // show errors if necessary
        const errContainer = form.querySelector(`.p-form__field[handler-name="${handlerName}"] .p-form__errors`);
        if(errContainer)
        {
          errContainer.classList.toggle('p-form__errors--hidden', result.errors.length === 0);
        }

        if(result.errors.length > 0)
        {
          clearErrors(form, handlerName);
          addErrors(form, handlerName, result.errors);
          e.preventDefault();
          e.stopImmediatePropagation();
        }
      },
    );
  },
);

document.addEventListener(
  'reset', e =>
  {
    /**
     * @type {HTMLFormElement}
     */
    const form = e.path && e.path[0] || e.target;
    form.querySelectorAll('.p-form__field[handler-name]')
        .forEach((ele) => clearErrors(form, ele.getAttribute('handler-name')));
  }
);

document.addEventListener('input', e =>
{
  const inputEle = e.path && e.path[0] || e.target;
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

  const result = validateHandler(form, handlerName);
  const errContainer = handlerContainer.querySelector(`.p-form__errors`);
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
