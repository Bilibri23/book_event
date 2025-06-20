/**
 * Displays a Bootstrap Toast notification.
 * @param {string} message The message to display.
 * @param {string} type 'success' (green) or 'error' (red).
 */
function showToast(message, type = 'success') {
    const toastElement = document.getElementById('appToast');
    if (!toastElement) return;

    const toastBody = toastElement.querySelector('.toast-body');

    // Reset classes
    toastElement.classList.remove('bg-success', 'bg-danger');

    // Set color based on type
    if (type === 'success') {
        toastElement.classList.add('bg-success');
    } else {
        toastElement.classList.add('bg-danger');
    }

    toastBody.textContent = message;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}