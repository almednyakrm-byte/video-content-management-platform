**edit_محتوى-إلكتروني.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/محتوى-إلكتروني.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل محتوى إلكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل محتوى إلكتروني</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">المحتوى</label>
                <textarea id="content" name="content" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" rows="5"><?= $existingRecord['content'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-md focus:ring-emerald-600 focus:border-emerald-600">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/محتوى-إلكتروني.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/محتوى-إلكتروني.php** (assuming this file already exists and fetches data from the database)

<?php
// Fetch existing record details
$id = $_GET['id'];
$existingRecord = fetchRecordFromDatabase($id);

// Output JSON response
echo json_encode($existingRecord);


Note: Replace `fetchRecordFromDatabase($id)` with your actual database query function.