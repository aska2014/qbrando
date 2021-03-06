<!DOCTYPE HTML>
<html xmlns:ng="http://angularjs.org" id="ng-app" ng-app="qbrando">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    @if(! $seo)
    <title>Qbrando | Online shop for luxury in Qatar</title>
    @else
        {{ $seo->toHtml() }}
    @endif


    <link rel="stylesheet" href="{{ URL::asset('app/css/app.css') }}"/>

    <link rel="icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon"/>

    <script src="{{ URL::asset('app/lib/respond.min.js') }}"></script>

    <!--[if lte IE 8]>
    <script src="{{ URL::asset('app/lib/json/json2.js') }}"></script>
    <![endif]-->

    <!--[if lte IE 8]>
    <script>
        document.createElement('my-cart-btn');
    </script>
    <![endif]-->

    @if(App::environment() == 'production')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-29205808-4', 'qbrando.com');
        ga('send', 'pageview');

    </script>
    @endif

    {{ $template->render('head') }}

</head>
<body ng-controller="MainController">

<div class="large-container">
    <div class="container">

        {{ $template->render('header') }}

        <div class="clearfix"></div>

        {{ $template->render('lower_header') }}

        <div class="clearfix"></div>

        <div class="content">


            @if($template->getLocation('sidebar') == 'left')
                {{ $template->render('sidebar') }}
            @endif

            {{ $template->render('body') }}

            @if($template->getLocation('sidebar') == 'right')
                {{ $template->render('sidebar') }}
            @endif

            <div class="clearfix"></div>

            {{ $template->render('footer') }}
        </div>


    </div>
</div>


@include('partials.modal')


@if(App::environment() == 'production')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

<script src="http://code.angularjs.org/1.2.0rc1/angular.min.js"></script>
<script src="http://code.angularjs.org/1.2.0rc1/angular-resource.min.js"></script>
<script src="http://code.angularjs.org/1.2.0rc1/angular-cookies.min.js"></script>

<script src="{{ URL::asset('app/lib/zoom/zoomsl-3.0.min.js') }}"></script>
@else
<script src="{{ URL::asset('app/lib/jquery.min.js') }}"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="{{ URL::asset('app/lib/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ URL::asset('app/lib/angular/angular.js') }}"></script>
<script src="{{ URL::asset('app/lib/angular/angular-resource.min.js') }}"></script>
<script src="{{ URL::asset('app/lib/angular/angular-cookies.min.js') }}"></script>

<script src="{{ URL::asset('app/lib/zoom/zoomsl-3.0.min.js') }}"></script>
@endif

<script src="{{ URL::asset('app/js/app.js') }}"></script>
<script src="{{ URL::asset('app/js/services.js') }}"></script>
<script src="{{ URL::asset('app/js/controllers.js') }}"></script>
<script src="{{ URL::asset('app/js/filters.js') }}"></script>
<script src="{{ URL::asset('app/js/directives.js') }}"></script>



{{ $template->render('scripts') }}

</body>
</html>