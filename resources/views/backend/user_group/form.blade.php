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

                <form action="{{ $form_action }}" method="post" accept-charset="utf-8" id="form-data" role="form">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Group Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ ( (old('name') !== null) ? old('name') : (isset($data['name']) ? $data['name'] : '')) }}" required="required" />
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
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
