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
                                <label for="parent_id">Parent <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="parent_id" id="parent_id" required="required">
                                    <option value="0"
                                        @if (old('parent_id') !== null && (old('parent_id') == '' || old('parent_id') == '0'))
                                            selected="selected"
                                        @elseif (isset($data['parent_id']) && ($data['parent_id'] == '' || $data['parent_id'] == '0'))
                                            selected="selected"
                                        @endif
                                    >ROOT</option>
                                    @if (isset($parents['root']))
                                        @include('backend.page.option', ['parentData' => $parents['root'], 'prefix' => ''])
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Page Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required="required" name="title" id="title" value="{{ ( (old('title') !== null) ? old('title') : (isset($data['title']) ? $data['title'] : '')) }}"/>
                            </div>
                            <div class="page-type-opt">
                                <label class="control-label" style="display: block;">Page Type <span class="text-danger">*</span></label>
                                <label class="radio-inline no-padding">
                                    <input type="radio" name="type" class="required iCheckBox" id="static_page" value="static_page"
                                        @if (old('type') !== null)
                                            @if (old('type') == 'static_page')
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['type']) && $data['type'] == 'static_page')
                                            checked="checked"
                                        @endif 
                                    /> Static Page
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" class="required iCheckBox" id="module" value="module"
                                        @if (old('type') !== null)
                                            @if (old('type') == 'module')
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['type']) && $data['type'] == 'module')
                                            checked="checked"
                                        @endif 
                                    /> Module
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" class="required iCheckBox" id="external_link" value="external_link"
                                        @if (old('type') !== null)
                                            @if (old('type') == 'external_link')
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['type']) && $data['type'] == 'external_link')
                                            checked="checked"
                                        @endif 
                                    /> External URL
                                </label>
                            </div>
                            <div class="content-static-page" style="display: none; margin-top: 20px;">
                                <div class="form-group">
                                    <label for="slug_url">SEO URL / SLUG <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="slug_url" id="slug_url" value="{{ ( (old('slug_url') !== null) ? old('slug_url') : (isset($data['slug_url']) ? $data['slug_url'] : '')) }}"/>
                                </div>
                                <div class="form-group">
                                    <label for="teaser">Teaser</label>
                                    <textarea name="teaser" id="teaser" rows="3" class="form-control">{{ ( (old('teaser') !== null) ? old('teaser') : (isset($data['teaser']) ? $data['teaser'] : '')) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" rows="3" class="form-control editorable">{{ ( (old('description') !== null) ? old('description') : (isset($data['description']) ? $data['description'] : '')) }}</textarea>
                                </div>
                            </div>
                            <div class="content-module" style="display: none; margin-top: 20px;">
                                <div class="form-group">
                                    <label for="module">Module <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="module" id="module" value="{{ ( (old('module') !== null) ? old('module') : (isset($data['module']) ? $data['module'] : '')) }}"/>
                                </div>
                            </div>
                            <div class="content-ext-link" style="display: none; margin-top: 20px;">
                                <div class="form-group">
                                    <label for="ext_link">External URL <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="ext_link" id="ext_link" placeholder="with http://" value="{{ ( (old('ext_link') !== null) ? old('ext_link') : (isset($data['ext_link']) ? $data['ext_link'] : '')) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <div class="form-group form-group-sm">
                                <label for="position">Position</label>
                                <input type="number" min="1" step="1" class="form-control" name="position" id="position" value="{{ ( (old('position') !== null) ? old('position') : (isset($data['position']) ? $data['position'] : $max_position)) }}"/>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" value="1" name="is_published" id="is_published"
                                        @if (old('is_published') !== null)
                                            @if (old('is_published') == 1)
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['is_published']) && $data['is_published'] == 1)
                                            checked="checked"
                                        @endif 
                                    /> Publish
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" value="1" name="is_featured" id="is_featured"
                                        @if (old('is_featured') !== null)
                                            @if (old('is_featured') == 1)
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['is_featured']) && $data['is_featured'] == 1)
                                            checked="checked"
                                        @endif 
                                    /> Featured
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" value="1" name="is_header" id="is_header"
                                        @if (old('is_header') !== null)
                                            @if (old('is_header') == 1)
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['is_header']) && $data['is_header'] == 1)
                                            checked="checked"
                                        @endif 
                                    /> Show in Header
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" value="1" name="is_footer" id="is_footer"
                                        @if (old('is_footer') !== null)
                                            @if (old('is_footer') == 1)
                                                checked="checked"
                                            @endif
                                        @elseif (isset($data['is_footer']) && $data['is_footer'] == 1)
                                            checked="checked"
                                        @endif 
                                    /> Show in Footer
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="primary_image">Primary Image</label><br>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail fileinput-upload" style="width: 200px; height: 150px;">
                                        @if (isset($data['primary_image']) && $data['primary_image'] != '' && file_exists(upload_path($upload_path. $data['primary_image'])))
                                            <img src="{{ upload_url($upload_path. $data['primary_image']) }}" id="primary_image" />
                                            <span class="btn btn-danger btn-delete-photo" data-id="{{ $data['id'] }}" data-type="primary_image">x</span>
                                        @endif
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                            <input type="file" name="primary_image">
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
    var row_gallery = <?php echo (isset($post['images'])) ? count($post['images']) : 0; ?>;
    $(function() {
        $('.seodef').keyup(function() {
            $('#uri_path').val(convert_to_uri(this.value));
        });
        @if (isset($data['id']))
        $('.btn-delete-photo, .delete-picture').on('click', function() {
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
        $('input[name=type]').on('ifClicked', function() {
            var self = $(this);
            if (self.val() == 'static_page') {
                // static page
                $('.content-module, .content-ext-link').slideUp('fast', function() {
                    $('.content-static-page').delay(500).slideDown('slow');
                });
            } else if (self.val() == 'module') {
                // module
                $('.content-static-page, .content-ext-link').slideUp('fast', function() {
                    $('.content-module').delay(500).slideDown('slow');
                });
            } else if (self.val() == 'external_link') {
                // external link
                $('.content-static-page, .content-module').slideUp('fast', function() {
                    $('.content-ext-link').delay(500).slideDown('slow');
                });
            } else {
                // default
                $('.content-static-page, .content-module, .content-ext-link').hide();
            }
        });
        $('input[name=type]:checked').trigger('ifClicked');
    });
</script>
@stop

