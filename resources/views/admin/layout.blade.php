<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Starter</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('/adminlte/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/adminlte/plugins/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('/adminlte/plugins/ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/adminlte/css/AdminLTE.min.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('/adminlte/plugins/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="{{ asset('/adminlte/css/skins/skin-blue.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="/adminlte/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="/adminlte/datatables.net-bs/css/responsive.bootstrap.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('/adminlte/plugins/iCheck/all.css') }}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="/adminlte/plugins/morris.js/morris.css">
  @stack('styles')
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
@include('admin.partial.header')
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      @include('admin.partial.sidebarmenu')
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('title', 'Por Defecto')
        <small>@yield('description', 'Description Defecto')</small>
      </h1>
      <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">@yield('title', 'Por Defecto')</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('admin.partial.footer')

  @php /*
  <!-- Control Sidebar -->
  @include('admin.sidebarcontrol')
  <!-- /.control-sidebar -->*/
  @endphp


</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{ asset('/adminlte/js/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('/adminlte/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/adminlte/js/adminlte.min.js') }}"></script>
<!-- DataTables -->
<script src="/adminlte/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/adminlte/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="/adminlte/datatables.net-bs/js/dataTables.responsive.min.js"></script>
<script src="/adminlte/datatables.net-bs/js/responsive.bootstrap.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('/adminlte/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<!-- InputMask -->
<script src="/adminlte/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/adminlte/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/adminlte/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- Jquery Number -->
<script src="{{ asset('/adminlte/js/jquery.number.min.js') }}"></script>
<!-- MomentJs -->
<script src="/adminlte/plugins/moment/min/moment.min.js"></script>
<script src="/adminlte/plugins/moment/locale/es.js"></script>
<!-- date-range-picker -->
<script src="/adminlte/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- Morris.js charts -->
<script src="/adminlte/plugins/raphael/raphael.min.js"></script>
<script src="/adminlte/plugins/morris.js/morris.min.js"></script>
<!-- ChartJS -->
<script src="/adminlte/plugins/chart.js/Chart.js"></script>
<!-- Config js -->
<script src="/adminlte/js/plantilla.js"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
@stack('scripts')
</body>
</html>
