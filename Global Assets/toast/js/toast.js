export function showToast(message, type = "success") {
    let toastElement = document.getElementById("liveToast");
    let toastMessage = document.getElementById("toast-message");

    if (!toastElement || !toastMessage) {
        console.error("Toast elements not found. Ensure toast HTML is included.");
        return;
    }

    toastMessage.textContent = message;

    // Remove old background classes and add the new one
    toastElement.classList.remove("bg-success", "bg-danger", "bg-warning");
    let bgColor = "bg-success"; // Default

    if (type === "success") {
        bgColor = "bg-success";
    } else if (type === "error") {
        bgColor = "bg-danger";
    } else if (type === "warning") {
        bgColor = "bg-warning";
    }

    toastElement.classList.add(bgColor);

    // Show toast
    let toast = new bootstrap.Toast(toastElement);
    toast.show();

    // Auto-close the toast after 5 seconds (5000ms)
    setTimeout(() => {
        toast.hide();
    }, 5000);
}

// Function to check and display flash messages from localStorage
export function checkFlashMessage() {
    let flashMessage = localStorage.getItem("flashMessage");
    let flashType = localStorage.getItem("flashType");

    if (flashMessage) {
        showToast(flashMessage, flashType || "success");

        // Remove after displaying to prevent duplicate toasts
        localStorage.removeItem("flashMessage");
        localStorage.removeItem("flashType");
    }
}
