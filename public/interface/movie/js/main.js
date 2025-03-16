const nlForms = document.getElementsByClassName('newsletter-form');
const captchaContainer =  document.getElementById('captcha-container');

document.getElementById('captcha-close-button').addEventListener('click', closeCaptcha);

function openCaptcha(nlForm)
{
  captchaContainer.style.display = 'block';

  const tmpInput = document.getElementById('arena');
  tmpInput.addEventListener('keyup', readyInput);
  tmpInput.focus();
  tmpInput.nlForm = nlForm;
}

function closeCaptcha()
{
  captchaContainer.style.display = 'none';
}

function triggerFormCheck(event)
{
  event.preventDefault();
  loopForm = event.target;
  openCaptcha(loopForm);

  return false;
}

function readyInput()
{
  if (event.target.value.length > 3) {
    event.target.nlForm.querySelector('#new_subscriber_' + someSpecificValue).value = event.target.value;
    event.target.nlForm.submit();

    return true;
   }
}

for (let nlForm of nlForms) {
  nlForm.addEventListener('submit', triggerFormCheck);
}


