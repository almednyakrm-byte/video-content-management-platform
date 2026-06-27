**edit_صور.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/صور.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

// Set form data
$form_data = $record;

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Prepare data for AJAX request
    $data = [
        'id' => $id,
        'name' => $_POST['name'],
        'description' => $_POST['description'],
    ];

    // Send AJAX request
    $response = json_decode(send_ajax_request('../backend/صور.php', 'PUT', $data), true);

    // Check if request was successful
    if ($response['success']) {
        // Redirect to list page
        header('Location: list_صور.php');
        exit;
    } else {
        // Display error message
        echo 'Error updating record';
    }
}

// Function to send AJAX request
function send_ajax_request($url, $method, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit Record</h1>
        <form action="" method="post" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="<?= $form_data['name'] ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $form_data['description'] ?></textarea>
            </div>
            <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Update Record</button>
        </form>
    </div>

    <script>
        // Fetch existing record details via GET
        fetch('../backend/صور.php?id=' + <?= $id ?>)
            .then(response => response.json())
            .then(data => {
                // Populate form fields
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


**Note:** This code assumes that the `send_ajax_request` function is defined in the PHP file. Also, make sure to replace `../backend/صور.php` with the actual URL of your backend API.