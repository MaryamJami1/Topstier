import { ref } from "vue";

export function useRecaptcha() {
  const loading = ref(false);

  const verifyRecaptcha = async (action = "default_action") => {
    try {
      loading.value = true;

      const token = await window.grecaptcha.execute(
        import.meta.env.VITE_RECAPTCHA_KEY,
        { action }
      );

      const res = await fetch("/api/v1/recaptcha/verify", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token, action }),
      });

      const data = await res.json();

    
      return data; 
    } catch (err) {
      console.error("Recaptcha error:", err);
      return false;
    } finally {
      loading.value = false;
    }
  };

  return { verifyRecaptcha, loading };
}
