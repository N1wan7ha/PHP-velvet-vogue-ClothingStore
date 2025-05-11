<?php
include_once 'includes/config.php';
include_once 'includes/db.php';
include_once 'includes/functions.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no valid product ID is provided, redirect to products page
if ($product_id <= 0) {
    header("Location: products.php");
    exit();
}

// Get product details from database
$product = getProductById($product_id);

// If product not found, redirect to products page
if (!$product) {
    header("Location: products.php");
    exit();
}

// Set page title
$page_title = $product['name'] . " - Velvet Vogue";

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap -mx-4">
        <!-- Product Image -->
        <div class="w-full md:w-1/2 px-4 mb-8">
            <div class="bg-gray-100 rounded-lg p-4">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-auto rounded-lg">
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="w-full md:w-1/2 px-4">
            <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="text-2xl font-semibold text-purple-700 mb-4">
                $<?php echo number_format($product['price'], 2); ?>
            </div>
            
            <div class="mb-6">
                <?php echo htmlspecialchars($product['description']); ?>
            </div>
            
            <div class="mb-6">
                <p class="mb-2"><span class="font-semibold">Category:</span> <?php echo htmlspecialchars($product['category']); ?></p>
                <p class="mb-2"><span class="font-semibold">Availability:</span> <?php echo ($product['stock'] > 0) ? 'In Stock' : 'Out of Stock'; ?></p>
            </div>
            
            <?php if ($product['stock'] > 0) : ?>
                <form action="cart.php" method="post" class="mb-6">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    
                    <div class="flex items-center mb-4">
                        <label for="quantity" class="mr-4 font-semibold">Quantity:</label>
                        <select name="quantity" id="quantity" class="border border-gray-300 rounded px-3 py-2">
                            <?php for ($i = 1; $i <= min(10, $product['stock']); $i++) : ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="bg-purple-700 text-white px-6 py-3 rounded hover:bg-purple-800 transition-colors">
                        Add to Cart
                    </button>
                </form>
            <?php else : ?>
                <div class="mb-6">
                    <button disabled class="bg-gray-400 text-white px-6 py-3 rounded cursor-not-allowed">
                        Out of Stock
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Product features/details -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-3">Product Details</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <?php
                    // Display product features if available
                    if (!empty($product['features'])) {
                        $features = explode('|', $product['features']);
                        foreach ($features as $feature) {
                            echo '<li>' . htmlspecialchars(trim($feature)) . '</li>';
                        }
                    } else {
                        echo '<li>Premium quality materials</li>';
                        echo '<li>Ethically sourced</li>';
                        echo '<li>30-day return policy</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Related Products Section -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">You May Also Like</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            // Get related products (same category)
            $related_products = getRelatedProducts($product['category_id'], $product_id, 4);
            
            foreach ($related_products as $related) :
            ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <a href="product-details.php?id=<?php echo $related['id']; ?>">
                        <img src="<?php echo htmlspecialchars($related['image_url']); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>" class="w-full h-64 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="product-details.php?id=<?php echo $related['id']; ?>" class="hover:text-purple-700">
                                <?php echo htmlspecialchars($related['name']); ?>
                            </a>
                        </h3>
                        <p class="text-purple-700 font-semibold">$<?php echo number_format($related['price'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>