
export function removeRecaptcha() {
  return new Promise((resolve) => {

    const script = document.querySelector('script[src*="recaptcha/api.js"]');
    if (script) {
      script.remove();
    }


    const badge = document.querySelector('.grecaptcha-badge');
    if (badge) {
      badge.remove();
    }

    if (window.grecaptcha) {
      delete window.grecaptcha;
    }
    // console.log("reCAPTCHA removed");
    resolve(true);
  });
}
