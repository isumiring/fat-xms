
<div class="row">
    <div class="col-md-12">
        <div class="form-message">
            @if (session('form_message'))
            {!! alert_box(session('form_message')['message'], session('form_message')['status']) !!}
            @endif
        </div>
    </div>
</div>