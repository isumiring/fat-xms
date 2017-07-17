@extends('backend.layouts.blank')

@section('content')
<div class="login-box">
    <div class="login-logo">
        @if ($site_info['site_logo'] != '' && file_exists(upload_path('sites/'. $site_info['site_logo'])))
        <div class="login-logo">
            <a href="{{ route(backend_path('.index')) }}">
                <img src="{{ upload_url('sites/'. $site_data['site_logo']) }}" alt="Main Logo" class="img-responsive img-sitelogo"/>
            </a>
        </div>
        <!-- /.login-logo -->
        @else
        <a href="{{ route(backend_path('.index')) }}"><b>FAT</b> XMS</a>
        @endif
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Hey! Let's go to work.</p>

        <form action="{{ $form_action }}" method="post" role="form" onsubmit="return false;" id="form-login-auth">
            <div class="form-message">
                @if($errors->any())
                {!! alert_box($errors->all(), 'danger') !!}
                @endif
            </div>
            {!! csrf_field() !!}
            <div class="form-group has-feedback animated fadeInLeftBig">
                <input type="text" class="form-control" placeholder="Username" name="username" required="required" value="" autofocus/>
                <span class="fa fa-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback animated fadeInRightBig">
                <input type="password" class="form-control" placeholder="Password" name="password" required="required" value=""/>
                <span class="fa fa-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="go-login">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@stop

@section('script')

<script type="text/javascript">
    $(function() {
        $('#go-login').on('click', function() {
            $('.form-message').empty();
            var self = $(this),
                self_html = $(this).html();
            var data = $('#form-login-auth').serializeArray();
            if (typeof data != 'undefined' && data != '') {
                submit_ajax('{{ $form_action }}', data, self)
                    .always(function() {
                        self.html(self_html).removeAttr('disabled');
                    })
                    .done(function(response) {
                        if (response['status'] && response['status'] == 'failed') {
                            $('.form-message').html(response['message']);
                        }
                    });
            }
        })
    })
</script>

@stop