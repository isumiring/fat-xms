@extends('backend.layouts.master')

@section('css') 
<link href="{{ backend_assets_url('vendor/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css', 'global') }}" rel="stylesheet"/>
@stop

@section('content')

<div class="content-wrapper">
    @include('backend.layouts.partials.heading')

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
        
            <div class="box-header">

                @include('backend.layouts.partials.formalert')

                <form action="{{ $form_action }}" method="post" accept-charset="utf-8" id="form-data" role="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" id="username" value="{{ ( (old('username') !== null) ? old('username') : (isset($data['username']) ? $data['username'] : '')) }}" required="required"/>
                            </div>
                            <div class="form-group">
                                <label for="user_group_id">Group <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="user_group_id" id="user_group_id" required="required">
                                    <?php foreach ($groups as $group) : ?>
                                    <option value="{{ $group['id'] }}" {!! (isset($data['user_group_id']) && $group['id'] == $data['user_group_id']) ? 'selected="selected"' : '' !!}>
                                        {{ $group['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password  <span class="text-danger">*</span></label>
                                <input type="password" id="password" class="form-control" name="password" value=""/>
                                @if (isset($data['id']))
                                <p class="help-block"><small>Leave this field empty if You don't want to change the password.</small></p>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="conf_password">Password Confirmation <span class="text-danger">*</span></label>
                                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" value=""/>
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email" value="{{ ( (old('email') !== null) ? old('email') : (isset($data['email']) ? $data['email'] : '')) }}" required="required"/>
                            </div>
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ ( (old('name') !== null) ? old('name') : (isset($data['name']) ? $data['name'] : '')) }}" required="required"/>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <div class="form-group">
                                <label for="user_status">Status</label><br/>
                                <label class="no-padding">
                                    <input type="checkbox" class="iCheckBox" value="1" name="user_status" id="user_status"

                                        @if (old('user_status') !== null)
                                            @if (old('user_status') == 1)
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['user_status']) && $data['user_status'] == 1)
                                            checked="checked"
                                        @endif
                                    /> Active
                                </label>
                            </div>
                            @if (auth_user()->is_superadmin)
                            <div class="form-group">
                                <label for="is_superadmin">Super Administrator</label>
                                <div class="checkbox">
                                    <label class="no-padding">
                                        <input type="checkbox" class="iCheckBox" value="1" name="is_superadmin" id="is_superadmin"

                                            @if (old('is_superadmin') !== null)
                                                @if (old('is_superadmin') == 1)
                                                    checked="checked"
                                                @endif
                                            @elseif (isset($data['is_superadmin']) && $data['is_superadmin'] == 1)
                                                checked="checked"
                                            @endif
                                        /> Yes
                                    </label>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="avatar">Avatar</label><br />
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new fileinput-upload thumbnail" style="width: 200px; height: 150px;">
                                        @if (isset($data['avatar']) && $data['avatar'] != '' && file_exists(upload_path($upload_path. $data['avatar'])))
                                            <img src="{{ upload_url($upload_path. 'tmb_'. $data['avatar']) }}" id="post-image" />
                                            <span class="btn btn-danger btn-delete-photo" id="delete-picture" data-id="{{ $data['id'] }}">x</span>
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
                    <!-- /.row -->
                    <div class="row button-row">
                        <div class="col-md-12 text-left">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-danger" href="{{ $data_url }}">Cancel</a>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                </form>
            </div>
        </div>
        <!--/.box-body-->
    </section>
</div>
<!--/.box-->

@stop

@section('script')
<script src="{{ backend_assets_url('vendor/bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.min.js', 'global') }}"></script>
<script type="text/javascript">
    $(function() {
        @if (isset($data['id']))
        $("#delete-picture").click(function() {
            $('.form-message').empty();
            var $this = $(this),
                $this_html = $(this).html();
            var $id = $this.attr('data-id');
            var $data = [
                {'name': 'id', 'value': $id}
            ];
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                showLoaderOnConfirm: true
            })
            .then(function() {
                submit_ajax('{{ $delete_picture_url }}', $data, $this)
                    .done(function(response) {
                        if (response['message'])  {
                            $(".form-message").html(response['message']);
                        }
                        if (response['status'] == 'success') {
                            $("#post-image").remove();
                            $this.remove();
                        }
                    });
            });
        });
        @endif
    })
</script>
@stop
