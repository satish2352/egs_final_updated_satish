@if (Session::get('status') == 'success')
    <div class="col-12 grid-margin">
        <div class="alert alert-success" id="success-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong> <span id="data_to_show">
                    {{ Session::get('msg') }}
                </span> </strong>
        </div>
    </div>
@endif

@if (Session::get('status') == 'error')
    <div class="col-12 grid-margin">
        <div class="alert alert-danger" id="danger-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong> <span id="data_to_show">
                    {!! session('msg') !!}
                </span> </strong>
        </div>
    </div>
@endif
