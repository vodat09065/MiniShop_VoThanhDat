// Add to Cart
document.querySelectorAll(".btn-add-cart").forEach(button => {
    button.addEventListener("click", function () {
        const productid = this.dataset.productid;
        const formData = new FormData();
        formData.append("productid", productid);
        
        // Get quantity if present (on detail page)
        const qtyInput = document.getElementById("quantity");
        if (qtyInput) {
            formData.append("quantity", qtyInput.value);
        }

        fetch(BASE_URL + "cart/add", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count on header
                const cartCountEl = document.querySelector("#cartCount");
                if (cartCountEl) {
                    cartCountEl.textContent = data.cartCount;
                    // Simple animation to draw attention
                    cartCountEl.classList.add("bg-success");
                    cartCountEl.classList.remove("bg-danger");
                    setTimeout(() => {
                        cartCountEl.classList.add("bg-danger");
                        cartCountEl.classList.remove("bg-success");
                    }, 500);
                }
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Lỗi:", error);
        });
    });
});

// Update Cart Quantity
function updateCart(productid, quantity) {
    if (quantity < 1) {
        removeCart(productid);
        return;
    }
    
    const formData = new FormData();
    formData.append("productid", productid);
    formData.append("quantity", quantity);

    fetch(BASE_URL + "cart/update", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update quantity input value
            const row = document.querySelector("#cart-item-" + productid);
            if (row) {
                row.querySelector("input[type='text']").value = quantity;
                row.querySelector(".input-group button:nth-child(1)").setAttribute("onclick", `updateCart(${productid}, ${quantity - 1})`);
                row.querySelector(".input-group button:nth-child(3)").setAttribute("onclick", `updateCart(${productid}, ${quantity + 1})`);
                
                // Update item subtotal
                const subtotalEl = document.querySelector(".item-subtotal-" + productid);
                if (subtotalEl) subtotalEl.textContent = data.itemTotal;
            }
            
            // Update global cart total
            const cartTotalEl = document.querySelector("#cartTotal");
            if (cartTotalEl) cartTotalEl.textContent = data.cartTotal;
            
            // Update header count
            const cartCountEl = document.querySelector("#cartCount");
            if (cartCountEl) cartCountEl.textContent = data.cartCount;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error("Lỗi:", error);
    });
}

// Remove from Cart
function removeCart(productid) {
    if (!confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) return;
    
    const formData = new FormData();
    formData.append("productid", productid);

    fetch(BASE_URL + "cart/remove", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove row from DOM
            const row = document.querySelector("#cart-item-" + productid);
            if (row) row.remove();
            
            // Update global cart total
            const cartTotalEl = document.querySelector("#cartTotal");
            if (cartTotalEl) cartTotalEl.textContent = data.cartTotal;
            
            // Update header count
            const cartCountEl = document.querySelector("#cartCount");
            if (cartCountEl) cartCountEl.textContent = data.cartCount;
            
            // Reload page if cart is empty
            if (data.cartCount == 0) {
                location.reload();
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error("Lỗi:", error);
    });
}

// Detail Page Quantity Buttons
document.addEventListener("DOMContentLoaded", function() {
    const btnPlus = document.getElementById('btn-plus');
    const btnMinus = document.getElementById('btn-minus');
    const qtyInput = document.getElementById('quantity');
    
    if (btnPlus && btnMinus && qtyInput) {
        btnPlus.addEventListener('click', function() {
            qtyInput.value = parseInt(qtyInput.value) + 1;
        });
        btnMinus.addEventListener('click', function() {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        });
    }
});
