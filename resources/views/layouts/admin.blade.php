<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen">
        <nav class="bg-white shadow p-4 mb-6">
            <div class="container mx-auto">
                <h1 class="text-lg font-bold">Admin Panel</h1>
            </div>
        </nav>

        <main class="container mx-auto px-4">
            @yield('content')
        </main>
    </div>

</body>

</html>
