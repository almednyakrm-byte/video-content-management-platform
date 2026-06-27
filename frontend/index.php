<?php
session_start();

// Check if user is authenticated
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
    <title>منصة إدارة الفيديوهات والصور والمحتوى الإلكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-emerald-600 text-white">
        <h1 class="text-3xl font-bold">منصة إدارة الفيديوهات والصور والمحتوى الإلكتروني</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4">
        <div class="glassmorphism w-1/2 p-4">
            <h2 class="text-2xl font-bold">مرحباً بكم في منصة إدارة الفيديوهات والصور والمحتوى الإلكتروني</h2>
            <p class="text-lg">هذه المنصة توفر لك إمكانية إدارة و تعديل و توزيع المحتوى الإلكتروني بسهولة</p>
        </div>
    </div>
    <div class="flex justify-center items-center p-4">
        <div class="glassmorphism w-1/2 p-4">
            <h2 class="text-2xl font-bold">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold">فيديوهات</h3>
                    <p class="text-lg" id="video-count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold">صور</h3>
                    <p class="text-lg" id="image-count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold">محتوى إلكتروني</h3>
                    <p class="text-lg" id="content-count"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center p-4">
        <div class="glassmorphism w-1/2 p-4">
            <h2 class="text-2xl font-bold">روابط سريعة</h2>
            <div class="flex justify-between items-center">
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='videos.php'">فيديوهات</button>
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='images.php'">صور</button>
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='content.php'">محتوى إلكتروني</button>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('video-count').innerHTML = data.video_count;
                document.getElementById('image-count').innerHTML = data.image_count;
                document.getElementById('content-count').innerHTML = data.content_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a Javascript API call from the backend files.

Note: You need to create a backend file (e.g. `api/stats.php`) to handle the API call and return the stats data in JSON format.