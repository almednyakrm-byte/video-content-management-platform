**list_محتوى-إلكتروني.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محتوى إلكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d6fde;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d6fde;
            color: #fff;
        }
        .table td {
            background-color: #f7f7f7;
        }
        .table td a {
            text-decoration: none;
            color: #2d6fde;
        }
        .table td a:hover {
            color: #ccc;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز إدارة المحتوى الإلكتروني</span>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold">مركز إدارة المحتوى الإلكتروني</span>
        <span class="text-lg font-bold">اسم المستخدم: <?php echo $_SESSION['username']; ?></span>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-bold">قائمة المحتوى الإلكتروني</h2>
            <a href="create_محتوى-إلكتروني.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" id="search-button">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be generated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const tableBody = document.getElementById('table-body');

        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/محتوى-إلكتروني.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.title}</td>
                                <td>${item.description}</td>
                                <td>
                                    <a href="edit_محتوى-إلكتروني.php?id=${item.id}" class="text-teal-500 hover:text-teal-700">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/محتوى-إلكتروني.php')
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.title}</td>
                                <td>${item.description}</td>
                                <td>
                                    <a href="edit_محتوى-إلكتروني.php?id=${item.id}" class="text-teal-500 hover:text-teal-700">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            }
        });

        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                fetch('../backend/محتوى-إلكتروني.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code generates a premium Tailwind UI containing a header navigation, a table showing the list of records, and a search bar. The table rows are generated dynamically using JavaScript and the Fetch API. The delete button sends a DELETE request to the backend to delete the corresponding record.