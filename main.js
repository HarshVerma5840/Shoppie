document.addEventListener('DOMContentLoaded', () => {

    function validateLoginForm() {
        let isValid = true;
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

        document.getElementById("email-error").innerText = "";
        document.getElementById("password-error").innerText = "";

        if (email === "") {
            document.getElementById("email-error").innerText = "Email is required";
            isValid = false;
        } else if (!emailPattern.test(email)) {
            document.getElementById("email-error").innerText = "Invalid email format";
            isValid = false;
        }

        if (password === "") {
            document.getElementById("password-error").innerText = "Password is required";
            isValid = false;
        } else if (password.length < 6) {
            document.getElementById("password-error").innerText = "Password must be at least 6 characters long";
            isValid = false;
        }

        return isValid;
    }

    function validateSignupForm() {
        let isValid = true;
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const mobile = document.getElementById("mobile").value.trim();
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirm-password").value.trim();
        const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

        document.getElementById("name-error").innerText = "";
        document.getElementById("email-error").innerText = "";
        document.getElementById("mobile-error").innerText = "";
        document.getElementById("password-error").innerText = "";
        document.getElementById("confirm-password-error").innerText = "";

        if (name === "") {
            document.getElementById("name-error").innerText = "Name is required";
            isValid = false;
        }

        if (email === "") {
            document.getElementById("email-error").innerText = "Email is required";
            isValid = false;
        } else if (!emailPattern.test(email)) {
            document.getElementById("email-error").innerText = "Invalid email format";
            isValid = false;
        }

        if (mobile === "") {
            document.getElementById("mobile-error").innerText = "Mobile number is required";
            isValid = false;
        } else if (!/^\d{10}$/.test(mobile)) {
            document.getElementById("mobile-error").innerText = "Mobile number must be 10 digits";
            isValid = false;
        }

        if (password === "") {
            document.getElementById("password-error").innerText = "Password is required";
            isValid = false;
        } else if (password.length < 6) {
            document.getElementById("password-error").innerText = "Password must be at least 6 characters long";
            isValid = false;
        }

        if (confirmPassword === "") {
            document.getElementById("confirm-password-error").innerText = "Confirm Password is required";
            isValid = false;
        } else if (confirmPassword !== password) {
            document.getElementById("confirm-password-error").innerText = "Passwords do not match";
            isValid = false;
        }

        return isValid;
    }



    function addItemToCart(productId) {
        fetch('cart_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ productId: productId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fetchAndRenderCart(); 
                const cartDropdown = document.getElementById('cartItems');
                cartDropdown.style.display = 'block';
                setTimeout(() => {
                    cartDropdown.style.display = ''; 
                }, 2500);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function removeItemFromCart(productId) {
        fetch('remove_cart_item.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ productId: productId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fetchAndRenderCart();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function fetchAndRenderCart() {
        fetch('get_cart.php')
            .then(response => response.json())
            .then(cartItems => {
                const cartItemsContainer = document.getElementById("cartItems");
                cartItemsContainer.innerHTML = "";

                if (cartItems.length === 0) {
                    cartItemsContainer.innerHTML = '<p style="padding: 10px; color: #555;">Your cart is empty.</p>';
                    return;
                }

                cartItems.forEach(item => {
                    const div = document.createElement("div");
                    div.className = "cart-item";
                    div.innerHTML = `
                      <img src="${item.image}" alt="${item.name}" width="40">
                      <span>${item.name} (${item.quantity}) <br> <strong>$${item.price}</strong></span>
                      <button class="remove-btn" data-id="${item.id}">×</button>
                    `;
                    cartItemsContainer.appendChild(div);
                });
            });
    }
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            const productId = button.dataset.id;
            addItemToCart(productId);
        });
    });
    
    document.getElementById('cartItems').addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-btn')) {
            const productId = event.target.dataset.id;
            removeItemFromCart(productId);
        }
    });

    fetchAndRenderCart();
});