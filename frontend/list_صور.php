**list_صور.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صور</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">صور</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_صور.php'">Add New Item</button>
            <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search..." id="search" oninput="searchRecords()">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="px-4 py-2"><?php echo $record['id']; ?></td>
                        <td class="px-4 py-2"><?php echo $record['name']; ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_صور.php?id=<?php echo $record['id']; ?>" class="text-teal-500 hover:text-teal-800">Edit</a>
                            <button class="ml-2 text-red-600 hover:text-red-800" onclick="deleteRecord(<?php echo $record['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/صور.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search
                }
            })
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.id}</td>
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_صور.php?id=${record.id}" class="text-teal-500 hover:text-teal-800">Edit</a>
                            <button class="ml-2 text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/صور.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting record!');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        function fetchRecords() {
            return fetch('../backend/صور.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => data.records)
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>


**backend/صور.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search records
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM records WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM records";
}

// Fetch records
$result = $conn->query($query);
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM records WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}

// Output records
echo json_encode(array('records' => $records));
$conn->close();
?>


Note: This code assumes you have a database table named `records` with columns `id` and `name`. You should replace the database connection details and table name with your actual database credentials and table structure.