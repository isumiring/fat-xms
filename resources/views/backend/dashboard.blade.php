@extends('backend.layouts.master')

@section('content')

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Dashboard
			<small>it all starts here</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url(backend_url()) }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
				<div class="box-body">
					Start creating your amazing application!
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box-header -->
		</div>
		<!-- /.box -->

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@stop