document.addEventListener('DOMContentLoaded', function() {
    const productContainer = document.querySelector('.product-placeholder');
    
    if (!productContainer) return;

    // Show loading state
    productContainer.innerHTML = '<div style="text-align:center;width:100%;color:#fff;">Loading Live Inventory...</div>';

    fetch('/tpo-inventory.php')
        .then(response => response.json())
        .then(products => {
            productContainer.innerHTML = ''; // Clear loading message
            
            if (products.length === 0) {
                productContainer.innerHTML = '<div style="text-align:center;width:100%;">No products found.</div>';
                return;
            }

            products.forEach(product => {
                const card = document.createElement('div');
                card.className = 'prod-card';
                
                // Fallback image if none
                const imgUrl = product.image ? product.image : 'https://placehold.co/300x300/222/999?text=No+Image';

                card.innerHTML = `
                    <div class="prod-image">
                        <a href="${product.link}">
                            <img src="${imgUrl}" alt="${product.title}" style="max-width:100%;max-height:100%;border-radius:4px;">
                        </a>
                    </div>
                    <div class="prod-title"><a href="${product.link}">${product.title}</a></div>
                    <div class="prod-price">${product.price}</div>
                    <a href="${product.add_to_cart}" class="prod-btn" style="display:inline-block;margin-top:10px;padding:8px 15px;background:#d32f2f;color:white;font-weight:bold;font-size:12px;border-radius:3px;">ADD TO CART</a>
                `;
                
                productContainer.appendChild(card);
            });
        })
        .catch(err => {
            console.error('Error fetching inventory:', err);
            productContainer.innerHTML = '<div style="text-align:center;width:100%;color:red;">Failed to load products.</div>';
        });
});
