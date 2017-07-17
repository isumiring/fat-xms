@extends('backend.layouts.master')

@section('content')


<div class="content-wrapper">
    <section class="content-header">
        <h1>
            401 Unauthorized Page
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(backend_url()) }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-exclamation-triangle"></i> 401 error</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"> 401</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> HEY!! You do not have access to this page.</h3>

                <p>
                    Yes!!! You are forbid to access this area.
                </p>
                <a href="{{ url(backend_url()) }}" class="btn btn-primary btn-flat">Back to dashboard</a>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@stop
