@extends('backend.layouts.master')

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
                                        @include('backend.user_menu.option', ['parentData' => $parents['root'], 'prefix' => ''])
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="menu">Menu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="menu" id="menu" value="{{ ( (old('menu') !== null) ? old('menu') : (isset($data['menu']) ? $data['menu'] : '')) }}" required="required"/>
                            </div>
                            <div class="form-group">
                                <label for="file">File Path <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="file" id="file" value="{{ ( (old('file') !== null) ? old('file') : (isset($data['file']) ? $data['file'] : '')) }}" required="required"/>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <div class="form-group form-group-sm">
                                <label for="position">Position</label>
                                <input type="number" min="1" step="1" class="form-control" name="position" id="position" value="{{ ( (old('position') !== null) ? old('position') : (isset($data['position']) ? $data['position'] : $max_position)) }}"/>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="icon_tags">Icon (fontawsome)</label>
                                <input type="text" class="form-control" name="icon_tags" id="icon_tags" value="{{ ( (old('icon_tags') !== null) ? old('icon_tags') : (isset($data['icon_tags']) ? $data['icon_tags'] : '')) }}"/>
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

