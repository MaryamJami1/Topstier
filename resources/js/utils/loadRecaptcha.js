export function loadRecaptcha(siteKey) {
  return new Promise((resolve, reject) => {
    // If already loaded
    if (window.grecaptcha) {
      return resolve(window.grecaptcha);
    }

    const script = document.createElement("script");
    script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
    script.async = true;
    script.defer = true;

    script.onload = () => {
      if (window.grecaptcha) {
        resolve(window.grecaptcha);
      } else {
        reject("reCAPTCHA failed to load");
      }
    };

    script.onerror = () => reject("reCAPTCHA script error");

    document.head.appendChild(script);
  });
}
