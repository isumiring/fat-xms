@extends('backend.layouts.master')

@section('content')

<div class="content-wrapper">
    @include('backend.layouts.partials.heading')

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
        
            <div class="box-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-message">
                            @if (session('form_message'))
                            {!! alert_box(session('form_message')['message'], session('form_message')['status']) !!}
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ $form_action }}" method="post" accept-charset="utf-8" id="form-data" role="form">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" id="select-all"/> <label for="select-all">Select All</label>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                @include('backend.user_group.menus', ['menu_listing' => $user_menus['root'], 'prefix' => ''])
                            </div>
                        </div>
                    </div>
                    <div class="row button-row">
                        <div class="col-md-12 text-left">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-danger" href="<?php echo $data_url; ?>">Cancel</a>
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
<script type="text/javascript">
    $(function() {
        $('#select-all').on('ifClicked', function (e) {
            $(this).on('ifChecked', function() {
                $('.checkauth').iCheck('check');
            });
            $(this).on('ifUnchecked', function() {
                $('.checkauth').iCheck('uncheck');
            });
        });
        $('.checkauth').on('ifChecked', function(e) {
            if ($('.checkauth:checked').length == $('.checkauth').length) {
                $('#select-all').iCheck('check');
            }
        });
        $('.checkauth').on('ifUnchecked', function(e) {
            $('#select-all').iCheck('uncheck');
        });
        if ($('.checkauth:checked').length == $('.checkauth').length) {
            $('#select-all').iCheck('check');
        }
    });
</script>
@stop
