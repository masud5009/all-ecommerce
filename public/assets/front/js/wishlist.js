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

    fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = window.routes.login;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.in_wishlist) {
                    button.classList.add('text-red-500');
                } else {
                    button.classList.remove('text-red-500');
                }
                // Optionally, show a success message
                // alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}
