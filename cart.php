<?php
include_once 'includes/config.php';
include_once 'includes/db.php';
include_once 'includes/functions.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Add product to cart
    if ($action === 'add') {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        // Validate product ID and quantity
        if ($product_id > 0 && $quantity > 0) {
            // Get product details
            $product = getProductById($product_id);
            
            if ($product && $product['stock'] >= $quantity) {
                // Check if product already exists in cart
                if (isset($_SESSION['cart'][$product_id])) {
                    // Update quantity
                    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                } else {
                    // Add new product to cart
                    $_SESSION['cart'][$product_id] = [
                        'id' => $product_id,
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image_url' => $product['image_url'],
                        'quantity' => $quantity
                    ];
                }
                
                // Redirect back to product page with success message
                header("Location: cart.php?status=added");
                exit();
            }
        }
    }
    
    // Update cart quantity
    if ($action === 'update') {
        $cart_items = isset($_POST['cart']) ? $_POST['cart'] : [];
        
        foreach ($cart_items as $product_id => $item) {
            $quantity = isset($item['quantity']) ? intval($item['quantity']) : 0;
            
            if ($quantity <= 0) {
                // Remove from cart if quantity is 0 or negative
                unset($_SESSION['cart'][$product_id]);
            } else {
                // Update quantity
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            }
        }
        
        // Redirect to avoid form resubmission
        header("Location: cart.php?status=updated");
        exit();
    }
    
    // Remove item from cart
    if ($action === 'remove') {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            
            // Redirect to avoid form resubmission
            header("Location: cart.php?status=removed");
            exit();
        }
    }
    
    // Clear cart
    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        
        // Redirect to avoid form resubmission
        header("Location: cart.php?status=cleared");
        exit();
    }
    
    // Proceed to checkout
    if ($action === 'checkout') {
        // Check if user is logged in
        if (!isLoggedIn()) {
            // Redirect to login page
            $_SESSION['redirect_after_login'] = 'cart.php';
            header("Location: account.php?action=login");
            exit();
        } else {
            // Process order
            $user_id = $_SESSION['user_id'];
            
            // Create order
            $order_id = createOrder($user_id, $_SESSION['cart']);
            
            if ($order_id) {
                // Clear cart
                $_SESSION['cart'] = [];
                
                // Redirect to order confirmation
                header("Location: order-confirmation.php?order_id=$order_id");
                exit();
            } else {
                // Redirect to cart with error message
                header("Location: cart.php?status=error");
                exit();
            }
        }
    }
}

// Calculate cart total
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

// Set page title
$page_title = "Shopping Cart - Velvet Vogue";

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
    
    <?php if (empty($_SESSION['cart'])) : ?>
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <p class="text-xl mb-6">Your cart is empty.</p>
            <a href="products.php" class="bg-purple-700 text-white px-6 py-3 rounded hover:bg-purple-800 transition-colors">
                Continue Shopping
            </a>
        </div>
    <?php else : ?>
        <div class="flex flex-wrap -mx-4">
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3 px-4 mb-8">
                <form action="cart.php" method="post">
                    <input type="hidden" name="action" value="update">
                    
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 text-left">Product</th>
                                    <th class="py-3 px-4 text-center">Price</th>
                                    <th class="py-3 px-4 text-center">Quantity</th>
                                    <th class="py-3 px-4 text-center">Subtotal</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $product_id => $item) : ?>
                                    <tr class="border-t border-gray-200">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-16 h-16 object-cover rounded mr-4">
                                                <a href="product-details.php?id=<?php echo $product_id; ?>" class="hover:text-purple-700">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            $<?php echo number_format($item['price'], 2); ?>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <input type="number" name="cart[<?php echo $product_id; ?>][quantity]" value="<?php echo $item['quantity']; ?>" min="1" max="10" class="border border-gray-300 rounded px-2 py-1 w-16 text-center">
                                        </td>
                                        <td class="py-4 px-4 text-center font-semibold">
                                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <form action="cart.php" method="post" class="inline">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 flex justify-between">
                        <button type="submit" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">
                            Update Cart
                        </button>
                        
                        <form action="cart.php" method="post" class="inline">
                            <input type="hidden" name="action" value="clear">
                            <button type="submit" class="text-red-600 hover:text-red-800 px-4 py-2">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </form>
            </div>
            
            <!-- Cart Summary -->
            <div class="w-full lg:w-1/3 px-4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    
                    <div class="border-t border-b border-gray-200 py-4 mb-4">
                        <div class="flex justify-between mb-2">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Shipping</span>
                            <span>$<?php echo number_format(SHIPPING_FEE, 2); ?></span>
                        </div>
                        <?php if ($cart_total >= FREE_SHIPPING_THRESHOLD) : ?>
                            <div class="flex justify-between text-green-600">
                                <span>Free Shipping Discount</span>
                                <span>-$<?php echo number_format(SHIPPING_FEE, 2); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex justify-between font-semibold text-lg mb-6">
                        <span>Total</span>
                        <span>$<?php 
                            $final_total = $cart_total;
                            if ($cart_total < FREE_SHIPPING_THRESHOLD) {
                                $final_total += SHIPPING_FEE;
                            }
                            echo number_format($final_total, 2); 
                        ?></span>
                    </div>
                    
                    <form action="cart.php" method="post">
                        <input type="hidden" name="action" value="checkout">
                        <button type="submit" class="w-full bg-purple-700 text-white px-6 py-3 rounded hover:bg-purple-800 transition-colors">
                            Proceed to Checkout
                        </button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <a href="products.php" class="text-purple-700 hover:text-purple-900">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>