document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('[data-action="toggle-wishlist"]')) {
            const button = e.target.closest('[data-action="toggle-wishlist"]');
            const productId = button.dataset.productId;
            toggleWishlist(productId, button);
        }
    });
});

function toggleWishlist(productId, button) {
    const url = window.routes.wishlistToggle;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!url || !productId || button.disabled) {
        return;
    }

    button.disabled = true;

    fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(async response => {
            if (response.status === 401) {
                window.location.href = window.routes.login;
                return null;
            }

            const contentType = response.headers.get('content-type') || '';
            const data = contentType.includes('application/json')
                ? await response.json()
                : { success: false, message: 'Unable to update wishlist right now.' };

            if (!response.ok) {
                throw new Error(data.message || 'Unable to update wishlist right now.');
            }

            return data;
        })
        .then(data => {
            if (!data) {
                return;
            }

            if (data.success) {
                if (data.in_wishlist) {
                    button.classList.add('text-red-500');
                } else {
                    button.classList.remove('text-red-500');
                }
            } else if (data.message) {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Wishlist error:', error);
            alert(error.message || 'Unable to update wishlist right now.');
        })
        .finally(() => {
            button.disabled = false;
        });
}
