import Toast from 'js-toast';

// Initialize Toast library
Toast.config({
    dir: 'ltr',
    timeout: 5,
    useProgressBar: true,
});

/**
 * Toast notification manager
 */
const NotificationManager = {
    /**
     * Show success notification
     */
    success(message, title = 'Success') {
        Toast.info(message, {
            title: title,
            type: 'success',
            duration: 5000,
        });
    },

    /**
     * Show error notification
     */
    error(message, title = 'Error') {
        Toast.error(message, {
            title: title,
            type: 'error',
            duration: 6000,
        });
    },

    /**
     * Show warning notification
     */
    warning(message, title = 'Warning') {
        Toast.warning(message, {
            title: title,
            type: 'warning',
            duration: 5000,
        });
    },

    /**
     * Show info notification
     */
    info(message, title = 'Info') {
        Toast.info(message, {
            title: title,
            type: 'info',
            duration: 4000,
        });
    },

    /**
     * Show multiple notifications
     */
    multiple(notifications) {
        notifications.forEach((notification) => {
            const method = this[notification.type] || this.info;
            method.call(this, notification.message, notification.title);
        });
    },

    /**
     * Show loading/pending notification (doesn't auto-close)
     */
    loading(message, title = 'Loading') {
        return Toast.info(message, {
            title: title,
            type: 'info',
            duration: 0, // Don't auto-close
        });
    },
};

/**
 * Global toast function for easy access
 */
window.toast = NotificationManager;

/**
 * Display flash notifications from session
 */
function displayFlashNotifications() {
    const notificationElement = document.getElementById('flash-notifications');

    if (notificationElement) {
        try {
            const notifications = JSON.parse(notificationElement.textContent);

            if (Array.isArray(notifications) && notifications.length > 0) {
                NotificationManager.multiple(notifications);
            }
        } catch (error) {
            console.error('Error parsing notifications:', error);
        }
    }
}

/**
 * Make AJAX request with toast notifications
 */
async function fetchWithToast(url, options = {}) {
    try {
        // Show loading notification if specified
        if (options.showLoading) {
            options.loadingId = NotificationManager.loading(
                options.loadingMessage || 'Processing...',
                options.loadingTitle || 'Loading'
            );
        }

        // Make request
        const response = await fetch(url, {
            ...options,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
                ...options.headers,
            },
        });

        const contentType = response.headers.get('content-type');
        let data = null;

        if (contentType?.includes('application/json')) {
            data = await response.json();
        } else {
            data = await response.text();
        }

        // Check for error response
        if (!response.ok) {
            throw new Error(data?.message || `HTTP ${response.status}`);
        }

        // Show success notification
        if (options.successMessage) {
            NotificationManager.success(
                options.successMessage,
                options.successTitle || 'Success'
            );
        }

        return { ok: true, status: response.status, data };
    } catch (error) {
        // Show error notification
        NotificationManager.error(
            options.errorMessage || error.message || 'An error occurred',
            options.errorTitle || 'Error'
        );

        return { ok: false, error: error.message };
    }
}

/**
 * Get CSRF token from meta tag
 */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

/**
 * Show validation errors as notifications
 */
function showValidationErrors(errors) {
    if (typeof errors === 'object' && !Array.isArray(errors)) {
        // Laravel validation errors object
        Object.values(errors).forEach((errorMessages) => {
            if (Array.isArray(errorMessages)) {
                errorMessages.forEach((message) => {
                    NotificationManager.error(message, 'Validation Error');
                });
            } else {
                NotificationManager.error(errorMessages, 'Validation Error');
            }
        });
    } else if (Array.isArray(errors)) {
        // Array of error messages
        errors.forEach((message) => {
            NotificationManager.error(message, 'Error');
        });
    } else {
        // Single error message
        NotificationManager.error(errors, 'Error');
    }
}

/**
 * Make simple POST request with toast feedback
 */
async function postWithToast(url, data = {}, options = {}) {
    return fetchWithToast(url, {
        method: 'POST',
        body: JSON.stringify(data),
        ...options,
    });
}

/**
 * Make simple PATCH request with toast feedback
 */
async function patchWithToast(url, data = {}, options = {}) {
    return fetchWithToast(url, {
        method: 'PATCH',
        body: JSON.stringify(data),
        ...options,
    });
}

/**
 * Make simple DELETE request with toast feedback
 */
async function deleteWithToast(url, options = {}) {
    return fetchWithToast(url, {
        method: 'DELETE',
        ...options,
    });
}

// Auto-display flash notifications on page load
document.addEventListener('DOMContentLoaded', displayFlashNotifications);

// Export for use in modules
export {
    NotificationManager,
    fetchWithToast,
    postWithToast,
    patchWithToast,
    deleteWithToast,
    showValidationErrors,
    getCsrfToken,
};

// Global exports
window.NotificationManager = NotificationManager;
window.fetchWithToast = fetchWithToast;
window.postWithToast = postWithToast;
window.patchWithToast = patchWithToast;
window.deleteWithToast = deleteWithToast;
window.showValidationErrors = showValidationErrors;
