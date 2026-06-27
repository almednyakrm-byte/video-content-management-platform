**create_محتوى-إلكتروني.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($description) && !empty($content)) {
        // Insert data into database
        $sql = "INSERT INTO محتوى_إلكتروني (title, description, content) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $title, $description, $content);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_محتوى-إلكتروني.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New محتوى إلكتروني</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title</label>
            <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
        </div>
        <div class="mb-4">
            <label for="content" class="block text-sm font-bold text-gray-700 mb-2">Content</label>
            <textarea id="content" name="content" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Create</button>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-2"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/محتوى-إلكتروني.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_محتوى-إلكتروني.php';
                    } else {
                        alert('Error creating record');
                    }
                }
            });
        });
    });
</script>


**backend/محتوى-إلكتروني.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been sent
if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['content'])) {
    // Insert data into database
    $sql = "INSERT INTO محتوى_إلكتروني (title, description, content) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $_POST['title'], $_POST['description'], $_POST['content']);
    $stmt->execute();

    // Return success message
    echo 'success';
} else {
    // Return error message
    echo 'Error creating record';
}
?>