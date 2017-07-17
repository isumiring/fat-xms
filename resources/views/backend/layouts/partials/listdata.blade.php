@extends('backend.layouts.master')

@section('content')

<div class="content-wrapper">

    @include('backend.layouts.partials.heading')

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-dttables box-info">
            <div class="row top-cursor">
                <div class="flash-message">
                    @if (session('flash_message'))
                    {!! alert_box(session('flash_message')['message'], session('flash_message')['status']) !!}
                    @endif
                </div>
                <div class="col-md-4 col-md-offset-8 text-right">
                @if ( ! isset($hide_add) || $hide_add == false)
                    <a href="{{ $add_url }}" class="btn btn-success">Add</a>
                @endif
                @if ( ! isset($hide_delete) || $hide_delete == false)
                    <button type="button" class="btn btn-danger delete-record" data-url="{{ $delete_url }}">Delete</button>
                @endif
                </div>
            </div>

            <div class="box-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-list" data-limit="{{ config('constant.default.limit_data') }}" data-url="{{ $data_url }}">
                    <thead>
                        <tr>
                        @foreach ($tables as $key => $table)
                            <th 
                                @if (isset($table['searchable']))
                                data-searchable="{{ $table['searchable'] }}"
                                @endif
                                @if (isset($table['orderable']))
                                data-orderable="<?php echo $table['orderable']; ?>"
                                @endif
                                @if (isset($table['classname']))
                                data-classname="<?php echo $table['classname']; ?>"
                                @endif
                                data-name="{{ $table['name'] }}"
                            >
                                {{ $table['text'] }}
                            </th>
                        @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
            <!--/.box-body-->

            <br/><br/>
            <div class="row">
                <div class="col-md-4 col-md-offset-8 text-right">
                @if ( ! isset($hide_add) || $hide_add == false)
                    <a href="<?php echo $add_url; ?>" class="btn btn-success">Add</a>
                @endif
                @if ( ! isset($hide_delete) || $hide_delete == false)
                    <input type="hidden" id="delete-record-field"/>
                    <button type="button" class="btn btn-danger delete-record" data-url="{{ $delete_url }}">Delete</button>
                @endif
                </div>
            </div>

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@stop

@section('script')
<script type="text/javascript">
    list_dataTables('#dataTables-list');
</script>
@stop
