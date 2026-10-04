<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.head')
</head>

<body>

    <div class="page">

        @include('layouts.sidebar')

        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <div class="page-wrapper">

            @include('layouts.navbar')

            @yield('content')

            @include('layouts.footer')

        </div>

    </div>

    @include('layouts.script')

</body>

</html>
