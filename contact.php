<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Initialize variables
$name = $email = $subject = $message = '';
$errors = [];
$success = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    }
    
    // If no errors, save contact message to database
    if (empty($errors)) {
        if (saveContactMessage($name, $email, $subject, $message)) {
            $success = true;
            $name = $email = $subject = $message = ''; // Clear form
        } else {
            $errors[] = 'Failed to send message. Please try again later.';
        }
    }
}

// Page title
$pageTitle = "Contact Us";
require_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Contact Us</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        Thank you for your message! We'll get back to you as soon as possible.
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-2">Full Name *</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">Email Address *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    
                    <div class="mb-4">
                        <label for="subject" class="block text-gray-700 mb-2">Subject *</label>
                        <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="block text-gray-700 mb-2">Message *</label>
                        <textarea id="message" name="message" rows="5" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-600"><?php echo htmlspecialchars($message); ?></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition duration-300">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div>
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-xl font-semibold mb-4">Our Information</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="text-purple-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Address</h3>
                            <p class="text-gray-600">
                                123 Fashion Avenue<br>
                                New York, NY 10001<br>
                                United States
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="text-purple-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Phone</h3>
                            <p class="text-gray-600">
                                +1 (555) 123-4567
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="text-purple-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Email</h3>
                            <p class="text-gray-600">
                                info@velvetvogue.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Business Hours</h2>
                
                <ul class="space-y-2">
                    <li class="flex justify-between">
                        <span class="text-gray-600">Monday - Friday:</span>
                        <span>9:00 AM - 7:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Saturday:</span>
                        <span>10:00 AM - 5:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Sunday:</span>
                        <span>Closed</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Map Section -->
    <div class="mt-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Find Us</h2>
            <div class="w-full h-64 bg-gray-200 rounded-md">
                <!-- Replace with actual map embed code -->
                <div class="flex items-center justify-center h-full text-gray-500">
                    Map placeholder - Replace with Google Maps or other map service embed
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>