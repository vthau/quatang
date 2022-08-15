<!DOCTYPE html>
<html lang="en">

<head>
    @include('templates.fe.layouts.head')
</head>

<body class="cms-index-index index-opt-5">
    <div class="wrapper">
        @include('templates.fe.layouts.header')

        <main class="site-main">
            @yield('content')
        </main>

        @include('templates.fe.layouts.footer')
    </div>

    @include('templates.fe.layouts.bot-script')

    <script type="text/javascript">
        jQuery(document).ready(function () {

        });
    </script>

</body>

</html>
