<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Attendance System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#F4F5F7',
                        primary: '#2E3440',
                        secondary: '#6B7280',
                        accent: '#4C6FFF',
                        accentHover: '#3B5CFF',
                        surface: '#FFFFFF',
                        border: '#E5E7EB',
                        success: '#10B981',
                        danger: '#EF4444',
                    }
                }
            }
        }
    </script>
    <style>
        .glow-accent { box-shadow: 0 8px 24px rgba(76,111,255,0.18); }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(76,111,255,0.12); }
    </style>
</head>
<body class="bg-bg text-primary min-h-screen">
    <div class="container mx-auto px-4 py-6">
        @yield('content')
    </div>
</body>
</html>