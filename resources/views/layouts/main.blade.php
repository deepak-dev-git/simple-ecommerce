<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        @include('layouts.menu')

        <div class="main-content flex-grow-1">
            @include('layouts.header')

            <main>
                @yield('content')
            </main>
        </div>
    </div>

    @stack('footer_content')
</body>
</html>
