@extends('backend.layouts.master')

@section('css') 
<link href="{{ backend_assets_url('vendor/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css', 'global') }}" rel="stylesheet"/>
@stop

@section('content')

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Profile Page
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url(backend_url()) }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li><i class="fa fa-user"></i> Profile</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- Default box -->
		<div class="box">

		    <div class="box-header">

                @include('backend.layouts.partials.formalert')
                
		        <form action="{{ $form_action }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="form-profile" role="form">
		        	{!! csrf_field() !!}
		            <div class="row">
		                <div class="col-lg-6">
		                    <div class="form-group">
		                        <label for="username">Username:</label>
		                        <strong>{{ $data['username'] }}</strong>
		                    </div>
		                    <div class="form-group">
		                        <label for="name">Name <span class="text-danger">*</span></label>
		                        <input type="text" class="form-control" name="name" id="name" value="{{ ( (old('name') !== null) ? old('name') : (isset($data['name']) ? $data['name'] : '')) }}" required="required"/>
		                    </div>
		                    <div class="form-group">
		                        <label for="email">Email <span class="text-danger">*</span></label>
		                        <input type="email" class="form-control" name="email" id="email" value="{{ ( (old('email') !== null) ? old('email') : (isset($data['email']) ? $data['email'] : '')) }}" required="required"/>
		                    </div>
		                    <div class="form-group">
		                        <button class="btn btn-info" id="change_pass" type="button" data-toggle="modal" data-target="#passModal">Change Password</button>
		                    </div>
		                </div>
		                <div class="col-lg-4 col-lg-offset-2">
		                    <div class="form-group">
		                        <label for="avatar">Avatar</label><br />
		                        <div class="fileinput fileinput-new" data-provides="fileinput">
		                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
		                            	@if (isset($data['avatar']) && $data['avatar'] != '' && file_exists(upload_path($upload_path. $data['avatar'])))
		                                    <img src="{{ upload_url($upload_path. 'tmb_'. $data['avatar']) }}" id="post-image" />
	                                    @endif
		                            </div>
		                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
		                            <div>
		                                <span class="btn btn-default btn-file">
		                                    <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
		                                    <input type="file" name="avatar">
		                                </span>
		                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <div class="row button-row">
		                <div class="col-md-12 text-left">
		                    <button type="submit" class="btn btn-primary">Submit</button>
		                </div>
		            </div>
		            <!-- /.row (nested) -->
		        </form>
		    </div>
		    <!--/.box-body-->
		</div>
		<!-- /.box -->

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="passModalLabel" aria-hidden="true">
    <!-- Modal Dialog -->
    <div class="modal-dialog">
        <!-- Modal Content -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                Change Password
            </div>
            <div class="modal-body">
                <form action="{{ $change_password_url }}" method="post" id="change_pass_form" onsubmit="return false;">
                    <div id="print-msg" class="error"></div>
                    <div class="form-group">
                        <label for="old_password" class="control-label">Old Password:</label>
                        <input type="password" id="old_password" class="form-control" name="old_password" value="" required="required" />
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">New Password:</label>
                        <input type="password" id="password" class="form-control" name="password" value="" required="required" />
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="control-label">Confirm New Password:</label>
                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" value="" required="required" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="save_password" type="button">Save</button>
                <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true" type="button">Cancel</button>
            </div>
        </div><!-- Modal Content -->
    </div><!-- Modal Dialog -->
</div><!-- Modal -->

@stop

@section('script')

<script src="{{ backend_assets_url('vendor/bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.min.js', 'global') }}"></script>
<script type="text/javascript">
    $("#save_password").on('click', function() {
        $("#print-msg").empty();
        var self = $(this),
            self_html = $(this).html();
        var old_password = $("#old_password").val();
        var new_password = $("#new_password").val();
        var conf_password = $("#conf_password").val();
        if (old_password != '') {
            if (new_password != '' && (conf_password == new_password)) {
                var data = $('#change_pass_form').serializeArray();
                submit_ajax('{{ $change_password_url }}', data, self)
                    .done(function(response) {
                        if (response['message']) {
                            $("#print-msg").html(response['message']);
                            if (response['status'] == 'success') {
	                            setTimeout(function() {
	                                if (response['redirect']) {
	                                    window.location = response['redirect'];
	                                }
	                            }, 1000);
	                        }
                        }
                        self.html(self_html).removeAttr('disabled');
                    });
            } else {
                $("#print-msg").html('{!! alert_box('Please input Your New Password or Confirmation is not correct.', 'danger') !!}');
            }
        } else {
            $("#print-msg").html('{!! alert_box('Please input Your old password.', 'danger') !!}');
        }
    });
</script>
@stop


