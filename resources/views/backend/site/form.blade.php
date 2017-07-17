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
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Site Info</a></li>
                                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Site Settings</a></li>
                                </ul>
                                <!--/.nav-tabs-->

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="info">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="site_name">Site Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="site_name" id="site_name" value="{{ ( (old('site_name') !== null) ? old('site_name') : (isset($data['site_name']) ? $data['site_name'] : '')) }}" required="required"/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="site_url">Site URL <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="site_url" id="site_url" value="{{ ( (old('site_url') !== null) ? old('site_url') : (isset($data['site_url']) ? $data['site_url'] : '')) }}" required="required"/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="site_path">Site Path</label>
                                                    <input type="text" class="form-control" name="site_path" id="site_path" value="{{ ( (old('site_path') !== null) ? old('site_path') : (isset($data['site_path']) ? $data['site_path'] : '')) }}"/>
                                                </div>
                                            </div><!-- /.col-md-6 -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="image">Logo</label><br>
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail fileinput-upload" style="width: 200px; height: 150px;">
                                                            @if (isset($data['site_logo']) && $data['site_logo'] != '' && file_exists(upload_path($upload_path. $data['site_logo'])))
                                                                <img src="{{ upload_url($upload_path. $data['site_logo']) }}" id="site_logo" />
                                                                <span class="btn btn-danger btn-delete-photo" data-id="{{ $data['id'] }}" data-type="site_logo">x</span>
                                                            @endif
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                                        <div>
                                                            <span class="btn btn-default btn-file">
                                                                <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                                                <input type="file" name="site_logo">
                                                            </span>
                                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="image_header">Header Image</label>
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail fileinput-upload" style="width: 200px; height: 150px;">
                                                            @if (isset($data['site_image_header']) && $data['site_image_header'] != '' && file_exists(upload_path($upload_path. $data['site_image_header'])))
                                                                <img src="{{ upload_url($upload_path. $data['site_image_header']) }}" id="site_image_header" />
                                                                <span class="btn btn-danger btn-delete-photo" data-id="{{ $data['id'] }}" data-type="site_image_header">x</span>
                                                            @endif
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                                        <div>
                                                            <span class="btn btn-default btn-file">
                                                                <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                                                <input type="file" name="site_image_header">
                                                            </span>
                                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.col-md-4 -->
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- /#info -->

                                    <div role="tabpanel" class="tab-pane fade" id="settings">
                                        <div class="row">
                                            @foreach ($data['site_settings'] as $row => $setting)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="{{ $setting['type'] }}">{{ ucwords(str_replace('_', ' ', $setting['type'])) }} <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="site_settings[{{ $setting['type'] }}]" id="{{ $setting['type'] }}" rows="1">{{ $setting['value'] }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!--/#settings-->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col-md-12 -->
                    </div>
                    <!-- /.row -->
                    <div class="row button-row">
                        <div class="col-md-12 text-left">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-danger" href="{{ $index_url }}">Cancel</a>
                        </div>
                    </div>
                    <!-- /.button-row -->
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
        $('.btn-delete-photo').click(function() {
            $('.form-message').empty();
            var $this = $(this),
                $this_html = $(this).html();
            var $id = $this.attr('data-id'),
                $type = $this.attr('data-type');
            var $data = [
                {'name': 'id', 'value': $id},
                {'name': 'type', 'value': $type}
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
                            $('#'+ $type).remove();
                            $this.remove();
                        }
                    });
            });
        });
        @endif
    })
</script>
@stop
