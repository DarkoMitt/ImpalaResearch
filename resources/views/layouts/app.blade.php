<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impala Research</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<script>
    history.scrollRestoration = "manual";

    window.addEventListener("load", () => {
        window.scrollTo({
            top: 0,
            left: 0,
            behavior: "instant"
        });
    });
</script>
<body class="bg-white font-[Poppins] text-[#2F2F32]">
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>
</body>
</html>