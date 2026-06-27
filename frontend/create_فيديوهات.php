<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Get module slug
$mod_slug = 'فيديوهات';

// Get current user
$current_user = $_SESSION['user_id'];

// Page title
$page_title = 'Create فيديوهات';

// Include header
include 'header.php';
?>

<main class="md:flex flex-wrap justify-center p-4">
    <div class="md:w-1/2 xl:w-1/3 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-emerald-600 text-2xl font-bold mb-4">Create فيديوهات</h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="title" class="block text-teal-500 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-teal-500 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
            </div>
            <div class="mb-4">
                <label for="url" class="block text-teal-500 text-sm font-bold mb-2">URL</label>
                <input type="url" id="url" name="url" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-teal-500 text-sm font-bold mb-2">Category</label>
                <select id="category" name="category" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
                    <option value="">Select Category</option>
                    <?php
                    // Fetch categories from database
                    $categories = mysqli_query($conn, "SELECT * FROM categories");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">Create فيديوهات</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/فيديوهات.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_فيديوهات.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>