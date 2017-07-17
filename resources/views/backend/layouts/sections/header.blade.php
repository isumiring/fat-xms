<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $site_info['site_name'] }}{{ (isset($head_title)) ? ' - '. $head_title : '' }}</title>
    <meta name="description" content="@yield('head_description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ backend_assets_url('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ backend_assets_url('bower_components/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ backend_assets_url('bower_components/Ionicons/css/ionicons.min.css') }}" />
    <link rel="stylesheet" href="{{ backend_assets_url('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ backend_assets_url('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ backend_assets_url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ backend_assets_url('plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ backend_assets_url('bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ backend_assets_url('dist/css/AdminLTE.min.css') }}" />
    <link rel="stylesheet" href="{{ backend_assets_url('dist/css/skins/skin-blue.min.css') }}" />
    <link rel="stylesheet" href="{{ backend_assets_url('vendor/bower_components/sweetalert2/dist/sweetalert2.min.css', 'global') }}" />
    <link rel="stylesheet" href="{{ backend_assets_url('css/animate.css', 'global') }}" />
    <link rel="stylesheet" href="{{ backend_assets_url('css/custom.css', 'global') }}" />

    @yield('css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="adminlte2 hold-transition {{ (request()->segment(2)) ? request()->segment(2). '-page' : 'home-page' }} {{ (request()->segment(2) != 'login') ? 'skin-blue sidebar-mini' : '' }}">
<div class="wrapper">
