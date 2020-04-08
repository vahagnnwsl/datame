<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@stack('title')</title>
    <meta name="description" content=" @stack('description')">
    <meta name="keywords" content="@stack('keywords')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('libs/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/swiper/swiper.min.css') }}">
    <link rel="stylesheet" href="{{  asset('libs/fancybox/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{  asset('libs/scrollbar/jquery.mcustomscrollbar.css') }}">
    <link rel="stylesheet" href="{{  asset('libs/selectstyle/jquery.formstyler.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">
    <link rel="stylesheet" href="{{ mix('css/media.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

<!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
</head>
<body>

<!-- PAGE WRAPPER -->
<div class="page_wrapper" id="app">

    @include('layouts.main.header')

    @yield('content')

    @include('layouts.footer')

</div>
<!-- END PAGE WRAPPER -->

{{--@include('spravka')--}}

{{--@include('layouts.modal.messages')--}}
{{----}}
{{--@include('layouts.modal.thanks')--}}

<script src="{{ mix('js/app.js') }}"></script>


<!--[if lt IE 9]>
<script src="{{ asset('ibs/html5shiv/es5-shim.min.js') }}"></script>
<script src="{{  asset('libs/html5shiv/html5shiv.min.js') }}"></script>
<script src="libs/html5shiv/html5shiv-printshiv.min.js') }}"></script>
<script src="{{ asset('libs/respond/respond.min.js') }}"></script>
<![endif]-->
{{--<script src="libs/jquery/jquery-3.2.1.min.js') }}""></script>--}}
<script src="{{ asset('libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('libs/swiper/swiper.min.js') }}"></script>
<script src="{{ asset('libs/scroll2id/pagescroll2id.min.js') }}"></script>
<script src="{{ asset('libs/fancybox/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('libs/validator/jquery.validate.min.js') }}"></script>
<script src="{{ asset('libs/maskedinput/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('libs/scrollbar/jquery.mcustomscrollbar.js') }}"></script>
<script src="{{ asset('libs/selectstyle/jquery.formstyler.min.js') }}"></script>
<script src="{{ mix('js/common.js') }}"></script>

@stack('scripts')

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(53523589, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/53523589" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

<!-- Google Analytics counter --><!-- /Google Analytics counter -->
</body>
</html>