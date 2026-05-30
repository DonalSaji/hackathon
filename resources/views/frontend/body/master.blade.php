<!DOCTYPE html>
<html lang="en">
@include('frontend.body.header')

<body class="theme-red">
    <div class="content-page">

        <div class="content"></div>
        @yield('content')
    </div>
    @include('frontend.body.footer')

    @include('frontend.body.scripts')
</body>

</html>
