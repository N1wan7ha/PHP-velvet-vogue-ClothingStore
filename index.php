<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="bg-gray-100">
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Express Your Style</h1>
                <p class="text-xl mb-6">Discover trendy casualwear and formal wear at Velvet Vogue.</p>
                <a href="/products.php" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">Shop Now</a>
            </div>
            <div class="md:w-1/2">
                <img src="/assets/images/banners/hero.jpg" alt="Velvet Vogue Collection" class="rounded-lg shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Browse Categories</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="/products.php?category=casual" class="block group">
                <div class="bg-gray-200 rounded-lg overflow-hidden shadow-md relative h-64">
                    <img src="/assets/images/categories/casual.jpg" alt="Casual Wear" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Casual Wear</h3>
                    </div>
                </div>
            </a>
            <a href="/products.php?category=formal" class="block group">
                <div class="bg-gray-200 rounded-lg overflow-hidden shadow-md relative h-64">
                    <img src="/assets/images/categories/formal.jpg" alt="Formal Wear" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Formal Wear</h3>
                    </div>
                </div>
            </a>
            <a href="/products.php?category=accessories" class="block group">
                <div class="bg-gray-200 rounded-lg overflow-hidden shadow-md relative h-64">
                    <img src="/assets/images/categories/accessories.jpg" alt="Accessories" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Accessories</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">New Arrivals</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <?php
            $featured_products = getProducts('', 4); // Get 4 latest products
            
            foreach ($featured_products as $product):
            ?>
            <div class="bg-white rounded-lg overflow-hidden shadow-md group">
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
        <div class="text-center mt-8">
            <a href="/products.php" class="inline-block border border-black text-black px-6 py-3 rounded-lg hover:bg-black hover:text-white transition">View All Products</a>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Join Our Newsletter</h2>
            <p class="text-gray-600 mb-6">Subscribe to get updates on new arrivals and special offers.</p>
            <form class="max-w-md mx-auto flex flex-col sm:flex-row gap-4">
                <input type="email" placeholder="Your email address" class="flex-1 px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-black">
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>