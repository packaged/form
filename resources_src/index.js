import {addErrors, clearErrors, validateField, validateForm} from './validation';

document.addEventListener(
  'submit', e => {
    const results = validateForm(e.target);
    results.forEach(
      (result, fieldName) => {
        // show errors if necessary
        const errContainer = e.target.querySelector(`.p-form__field[name="${fieldName}"] .p-form__errors`);
        if(errContainer)
        {
          errContainer.classList.toggle('p-form__errors--hidden', result.errors.length === 0);
        }
        console.log(result.errors);

        if(result.errors.length > 0)
        {
          clearErrors(e.target, fieldName);
          addErrors(e.target, fieldName, result.errors);
          e.preventDefault();
        }
      }
    );
  }
);

document.addEventListener('input', e => {
  const inputEle = e.target;
  const fieldName = inputEle.getAttribute('name');
  if(fieldName)
  {
    const form = inputEle.closest('form');
    if(form)
    {
      const result = validateField(form, fieldName);
      const errContainer = form.querySelector(`.p-form__field[name="${fieldName}"] .p-form__errors`);
      if(errContainer && result.errors.length === 0)
      {
        clearErrors(form, fieldName);
        errContainer.classList.add('p-form__errors--hidden');
      }
      else if(!result.potentiallyValid)
      {
        clearErrors(form, fieldName);
        addErrors(form, fieldName, result.errors);
      }
    }
  }
});
