document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('php/auth/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast(result.message, 'success');
                    registerForm.reset();
                    // Optional: redirect to login after a delay
                    setTimeout(() => { window.location.href = 'login.html'; }, 2000);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('An unexpected error occurred.', 'error');
            }
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('php/auth/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast(result.message, 'success');
                    // Redirect to the URL provided by the server
                    window.location.href = result.redirect;
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('An unexpected error occurred.', 'error');
            }
        });
    }
});