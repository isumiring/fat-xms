
    @if (request()->segment(2) != 'login')
    <footer class="main-footer">
        {{ $site_info['site_settings']['app_footer'] }}
    </footer>
    @endif
</div>
<!-- /.wrapper -->
    <script src="{{ backend_assets_url('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ backend_assets_url('js/list.min.js', 'global') }}"></script>
    <script src="{{ backend_assets_url('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ backend_assets_url('plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ backend_assets_url('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ backend_assets_url('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ backend_assets_url('vendor/bower_components/sweetalert2/dist/sweetalert2.min.js', 'global') }}"></script>
    <script src="{{ backend_assets_url('js/custom.js', 'global') }}"></script>
    
    <script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ajaxSuccess(function( event, request, settings, data) {
        if (typeof data['redirect_auth'] !== 'undefined') {
            window.location = data['redirect_auth'];
            return;
        }
    });

    $('.select2').select2()

    //Flat color scheme for iCheck
    $('.iCheckBox, input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        // checkboxClass: 'icheckbox_flat-green',
        // radioClass: 'iradio_flat-green'
    });
    $('input[type=checkbox].no-icheckbox').iCheck('destroy');

    // listjs
    var options = {
        valueNames: [ 'auth_menu_name' ]
    };
    var authMenuList = new List('sidebar-auth', options);

    </script>
    
    @yield('script')
</body>
</html>