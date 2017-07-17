@extends('backend.layouts.master')

@section('content')


<div class="content-wrapper">
    <section class="content-header">
        <h1>
            500 Error Page
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(backend_url()) }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-exclamation-triangle"></i> 500 error</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-red"> 404</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>

                <p>
                    We will work on fixing that right away.
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
