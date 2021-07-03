<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/android-icon-192x192-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="57x57" href="/icons/apple-icon-57x57-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="60x60" href="/icons/apple-icon-60x60-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="72x72" href="/icons/apple-icon-72x72-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="76x76" href="/icons/apple-icon-76x76-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="114x114" href="/icons/apple-icon-114x114-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="120x120" href="/icons/apple-icon-120x120-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="144x144" href="/icons/apple-icon-144x144-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="152x152" href="/icons/apple-icon-152x152-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="180x180" href="/icons/apple-icon-180x180-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32-dunplab-manifest-15727.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/icons/favicon-96x96-dunplab-manifest-15727.png">
    <title>The Hot Meal - Planner</title>
    @include('includes.meta')
    @include('includes.gtm-head')
    @include('includes.laravel')
{{--    <script src="{{ mix('js/manifest.js') }}" defer></script>--}}
{{--    <script src="{{ mix('js/planner.vendors.js') }}" defer></script>--}}
    <script src="{{ mix('js/planner.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="{{ mix('css/planner.css') }}">
{{--    this for remote vue-devtools--}}
{{--    <script>--}}
{{--        window.__VUE_DEVTOOLS_HOST__ = '192.168.0.116' // default: localhost--}}
{{--        window.__VUE_DEVTOOLS_PORT__ = '8098' // default: 8098--}}
{{--    </script>--}}
{{--    <script src="http://192.168.0.116:8098"></script>--}}

</head>
<body>
    @include('includes.gtm-body')
<div id="app"></div>
<div id="preloader">
</div>
@include('includes.statcounter')
</body>
</html>
