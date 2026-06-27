**edit_فيديوهات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/فيديوهات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل فيديو</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">تعديل فيديو</h1>
        <form id="edit-video-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md" value="<?= $existingRecord['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md"><?= $existingRecord['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="url" class="block text-sm font-medium text-gray-700">رابط الفيديو</label>
                <input type="text" id="url" name="url" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md" value="<?= $existingRecord['url'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-video-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/فيديوهات.php',
                    data: $(this).serialize() + '&id=' + <?= $id ?>,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_فيديوهات.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/فيديوهات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'ID is required'));
    exit;
}

// Get id
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array();
// Your database query to fetch existing record details
// ...

// Output existing record details as JSON
echo json_encode($existingRecord);