<!DOCTYPE html>
<html lang="en">

<head>
    @include('templates.be.layouts.head')
</head>

<body class="c-app">

    @include('templates.be.layouts.sidebar')

    @include('templates.be.layouts.aside')

    <div class="c-wrapper">
        @include('templates.be.layouts.header')

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div id="ui-view">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>

        @include('templates.be.layouts.footer')
    </div>

    @include('templates.be.layouts.modal')

    @include('templates.be.layouts.bot-script')

</body>

</html>
