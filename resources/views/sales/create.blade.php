@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Left Column: Sale Details & Product List -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h2 class="h4 mb-0">Sale Details</h2></div>
            <div class="card-body">
                <div id="sale-response-message" class="alert d-none" role="alert"></div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="employee-id" class="form-label">Select Employee</label>
                        <select id="employee-id" class="form-select">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->employee_id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sale-date" class="form-label">Sale Date (Optional)</label>
                        <input type="datetime-local" id="sale-date" name="sale_date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2 class="h4 mb-0">Available Products</h2></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="products-table">
                        <thead>
                            <tr><th>Product Name</th><th>Price</th><th>Stock</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>${{ number_format($product->selling_price, 2) }}</td>
                                    <td>{{ $product->quantity_in_stock }}</td>
                                    <td>
                                        {{-- FIX: Removed onclick. Storing data in a data-* attribute for safety. --}}
                                        <button class="btn btn-sm btn-secondary add-to-cart-btn" data-product='@json($product)'>Add</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No products available for sale.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Current Order -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 2rem;">
            <div class="card-header"><h2 class="h4 mb-0">Current Order</h2></div>
            <div class="card-body">
                <div id="cart-items" class="mb-4">
                    <p class="text-muted">No items added yet.</p>
                </div>
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between"><span>Subtotal:</span><span id="subtotal">$0.00</span></div>
                    <div class="d-flex justify-content-between text-danger"><span>Discount:</span><span id="discount-display">-$0.00</span></div>
                    <hr>
                    <div class="d-flex justify-content-between h5"><strong>Total:</strong><strong id="total-amount">$0.00</strong></div>
                </div>
                <button id="submit-sale-btn" class="btn btn-primary w-100 mt-3">Submit Sale</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Event Listener for Add to Cart Buttons ---
        const productsTable = document.getElementById('products-table');
        if(productsTable) {
            productsTable.addEventListener('click', function (event) {
                if (event.target && event.target.classList.contains('add-to-cart-btn')) {
                    // Retrieve the product data from the data attribute
                    const productData = JSON.parse(event.target.dataset.product);
                    addToCart(productData);
                }
            });
        }

        let cart = {};
        const cartItemsContainer = document.getElementById('cart-items');
        const subtotalEl = document.getElementById('subtotal');
        const discountDisplayEl = document.getElementById('discount-display');
        const totalAmountEl = document.getElementById('total-amount');
        const submitSaleBtn = document.getElementById('submit-sale-btn');
        const saleResponseMessageEl = document.getElementById('sale-response-message');

        function addToCart(product) {
            const productId = product.product_id;
            if (cart[productId]) {
                if (cart[productId].quantity >= product.quantity_in_stock) {
                    showResponseMessage(`Stock limit reached for ${product.product_name}.`, 'danger');
                    return;
                }
                cart[productId].quantity++;
            } else {
                cart[productId] = {
                    name: product.product_name,
                    price: parseFloat(product.selling_price),
                    quantity: 1,
                    discount_percentage: 0,
                    max_stock: parseInt(product.quantity_in_stock),
                    product_id: productId
                };
            }
            renderCart();
        }

        function changeQuantity(productId, amount) {
            if (!cart[productId]) return;
            
            const newQuantity = cart[productId].quantity + amount;

            if (newQuantity > cart[productId].max_stock) {
                showResponseMessage(`Stock limit reached for ${cart[productId].name}.`, 'danger');
                return;
            }

            if (newQuantity <= 0) {
                delete cart[productId];
            } else {
                cart[productId].quantity = newQuantity;
            }
            renderCart();
        }
        
        function updateDiscount(productId, value) {
            if(cart[productId]) {
                cart[productId].discount_percentage = parseFloat(value) || 0;
            }
            renderCart();
        }

        function renderCart() {
            if (Object.keys(cart).length === 0) {
                cartItemsContainer.innerHTML = '<p class="text-muted">No items added yet.</p>';
            } else {
                cartItemsContainer.innerHTML = '';
                for (const productId in cart) {
                    const item = cart[productId];
                    const itemEl = document.createElement('div');
                    itemEl.className = 'mb-3';
                    itemEl.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">${item.name}</h6>
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(${productId}, -1)">-</button>
                                <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(${productId}, 1)">+</button>
                            </div>
                        </div>
                        <small class="text-muted">$${item.price.toFixed(2)} / item</small>
                        <div class="mt-1">
                            <label class="form-label form-label-sm">Discount (%):</label>
                            <input type="number" value="${item.discount_percentage}" oninput="updateDiscount(${productId}, this.value)" class="form-control form-control-sm" min="0" max="100" step="0.01">
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemEl);
                }
            }
            updateSummary();
        }

        function updateSummary() {
            let subtotal = 0;
            let totalDiscount = 0;
            for (const id in cart) {
                const item = cart[id];
                subtotal += item.price * item.quantity;
                totalDiscount += (item.price * (item.discount_percentage / 100)) * item.quantity;
            }
            const total = subtotal - totalDiscount;

            subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
            discountDisplayEl.textContent = `-$${totalDiscount.toFixed(2)}`;
            totalAmountEl.textContent = `$${total.toFixed(2)}`;
        }

        submitSaleBtn.addEventListener('click', async () => {
            if (Object.keys(cart).length === 0) {
                showResponseMessage('Please add items to the order.', 'warning');
                return;
            }

            const payload = {
                employee_id: document.getElementById('employee-id').value,
                sale_date: document.getElementById('sale-date').value || null,
                products_json: JSON.stringify(Object.values(cart))
            };

            submitSaleBtn.disabled = true;
            submitSaleBtn.textContent = 'Submitting...';

            try {
                const response = await axios.post('{{ route("sales.store") }}', payload);
                showResponseMessage(response.data.message, 'success');
                cart = {};
                renderCart();
                setTimeout(() => window.location.reload(), 2000);
            } catch (error) {
                const message = error.response?.data?.message || 'An unknown error occurred.';
                showResponseMessage(message, 'danger');
            } finally {
                submitSaleBtn.disabled = false;
                submitSaleBtn.textContent = 'Submit Sale';
            }
        });

        function showResponseMessage(message, type = 'success') {
            saleResponseMessageEl.textContent = message;
            saleResponseMessageEl.className = `alert alert-${type}`; // Bootstrap classes
            setTimeout(() => {
                saleResponseMessageEl.className = 'alert d-none';
            }, 5000);
        }
    });
</script>
@endpush
