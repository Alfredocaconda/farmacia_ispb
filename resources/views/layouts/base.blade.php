<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <!-- Favicon -->
        <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" />
        <link rel="stylesheet" href="{{asset('css/backend-plugin.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/backend.css')}}?v=1.0.0">
        <link rel="stylesheet" href="{{asset('css/master.css')}}">
        <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('vendor/remixicon/fonts/remixicon.css')}}"> 
        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">
        <style>
            .tabela-rolavel {
                max-height: 500px;
                overflow-y: auto;
                display: block;
            }
        </style>

</head>
<body class="  ">
    @yield('content')

</body>
</html>
