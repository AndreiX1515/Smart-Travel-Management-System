function showToast(message, type = "success") {
    let toastContainer = document.getElementById("toastContainer");

    // Create Toast Element
    let toastElement = document.createElement("div");
    toastElement.className = `toast text-white bg-${type} border-0`;
    toastElement.setAttribute("role", "alert");
    toastElement.setAttribute("aria-live", "assertive");
    toastElement.setAttribute("aria-atomic", "true");

    toastElement.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
            <small class="text-body-secondary">Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">${message}</div>
    `;

    // Append to toast container
    toastContainer.appendChild(toastElement);

    // Initialize and show the toast
    let toast = new bootstrap.Toast(toastElement);
    toast.show();

    // Remove toast after it's hidden
    toastElement.addEventListener("hidden.bs.toast", function () {
        toastElement.remove();
    });
}
