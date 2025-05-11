<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information
$userId = $_SESSION['user_id'];
$userData = getUserById($userId);

// Process account update if form submitted
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    // Validate inputs
    if (empty($name) || empty($email)) {
        $updateMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Please fill in all required fields.</div>';
    } else {
        // Update user information
        if (updateUserProfile($userId, $name, $email)) {
            $updateMessage = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Account updated successfully!</div>';
            // Refresh user data after update
            $userData = getUserById($userId);
        } else {
            $updateMessage = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Failed to update account. Please try again.</div>';
        }
    }
}

// Get user's order history
$orders = getUserOrders($userId);

// Page title
$pageTitle = "My Account";
require_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Account</h1>
    
    <?php echo $updateMessage; ?>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Account Information -->
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Account Information</h2>
                
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    
                    <button type="submit" name="update_account" 
                            class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition duration-300">
                        Update Profile
                    </button>
                </form>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold mb-2">Security</h3>
                    <a href="change-password.php" class="text-purple-600 hover:text-purple-800">Change Password</a>
                </div>
            </div>
        </div>
        
        <!-- Order History -->
        <div class="md:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Order History</h2>
                
                <?php if (empty($orders)): ?>
                    <p class="text-gray-600">You haven't placed any orders yet.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Order #</th>
                                    <th class="px-4 py-2 text-left">Date</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-3"><?php echo $order['order_id']; ?></td>
                                    <td class="px-4 py-3"><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo getOrderStatusClass($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right"><?php echo formatCurrency($order['total']); ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="order-details.php?id=<?php echo $order['order_id']; ?>" 
                                           class="text-purple-600 hover:text-purple-800">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>