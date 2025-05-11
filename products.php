<?php 
include 'includes/header.php';

// Get category filter if provided
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$products = getProducts($category);
?>

<section class="bg-gray-100 py-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-2">Shop Our Collection</h1>
        <nav class="text-sm">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="/" class="text-gray-600 hover:text-black">Home</a>
                    <svg class="w-3 h-3 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>Shop<?= $category ? ' > ' . ucfirst($category) : '' ?></li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row">
            <!-- Sidebar/Filters -->
            <div class="w-full md:w-1/4 pr-0 md:pr-6 mb-6 md:mb-0">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <h2 class="font-bold text-lg mb-4">Categories</h2>
                    <ul class="space-y-2">
                        <li><a href="/products.php" class="block text-gray-600 hover:text-black <?= empty($category) ? 'font-bold' : '' ?>">All Products</a></li>
                        <li><a href="/products.php?category=casual" class="block text-gray-600 hover:text-black <?= $category == 'casual' ? 'font-bold' : '' ?>">Casual Wear</a></li>
                        <li><a href="/products.php?category=formal" class="block text-gray-600 hover:text-black <?= $category == 'formal' ? 'font-bold' : '' ?>">Formal Wear</a></li>
                        <li><a href="/products.php?category=accessories" class="block text-gray-600 hover:text-black <?= $category == 'accessories' ? 'font-bold' : '' ?>">Accessories</a></li>
                    </ul>
                    
                    <h2 class="font-bold text-lg mt-6 mb-4">Price Range</h2>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox">
                            <span class="ml-2">Under $50</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox">
                            <span class="ml-2">$50 - $100</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox">
                            <span class="ml-2">$100 - $200</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox">
                            <span class="ml-2">$200+</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Product Grid -->
            <div class="w-full md:w-3/4">
                <div class="mb-6 flex justify-between items-center">
                    <p class="text-gray-600"><?= count($products) ?> products found</p>
                    <select class="border rounded-lg px-4 py-2">
                        <option value="newest">Newest</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                    </select>
                </div>
                
                <?php if (count($products) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm group">
                        <a href="/product-details.php?id=<?= $product['id'] ?>">
                            <div class="h-64 overflow-hidden">
                                <img src="<?= $product['image_url'] ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-2"><?= $product['name'] ?></h3>
                                <p class="text-gray-600 mb-2">$<?= number_format($product['price'], 2) ?></p>
                                <button onclick="addToCart(<?= $product['id'] ?>)" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">Add to Cart</button>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <p class="text-gray-600">No products found in this category.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>