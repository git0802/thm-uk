<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html prefix="og: http://ogp.me/ns#" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    @include('includes.laravel')
    <style>
        * , *:before, *:after {
            outline: none !important;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Open Sans', sans-serif;
        }
        a, span, p, td {
            font-size: 16px;
            color: #121217;
        }
        span, p, td {
            cursor: default;
        }
        a {
            text-decoration: none;
        }
        @media screen and (max-width: 480px) {
            .two-row {
                display: block;
                margin-bottom: 8px;
            }
        }
    </style>

</head>
<body>
@yield('content')
</body>
