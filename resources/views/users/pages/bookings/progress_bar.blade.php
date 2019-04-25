<div class="row bs-wizard" style="padding-bottom: 20px">
    <div class="col-md-2 bs-wizard-step {{Request::is('search/bus')||
    Request::is('bus/*/schedule/*/trip/*/select/seat')||
    Request::is('bus/*/schedule/*/trip/*/booking-info')||
    Request::is('bus/*/schedule/*/trip/*/booking/store') ? 'complete' : ''}}">
        <div class="text-center bs-wizard-stepnum">@lang('Step one')</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">@lang('Timetable selection')</div>
    </div>

    <div class="col-md-2 bs-wizard-step {{Request::is('search/bus')||
    Request::is('bus/*/schedule/*/trip/*/select/seat')||
    Request::is('bus/*/schedule/*/trip/*/booking-info')||
    Request::is('bus/*/schedule/*/trip/*/booking/store') ? 'complete' : ''}}"><!-- complete -->
        <div class="text-center bs-wizard-stepnum">@lang('Step two')</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">@lang('Bus selection')</div>
    </div>

    <div class="col-md-4 bs-wizard-step {{ Request::is('bus/*/schedule/*/trip/*/select/seat')||
    Request::is('bus/*/schedule/*/trip/*/booking-info')||
    Request::is('bus/*/schedule/*/trip/*/booking/store') ? 'complete' : 'disabled'}}"><!-- complete -->
        <div class="text-center bs-wizard-stepnum">@lang('Step three')</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">@lang('Bus seat selection')</div>
    </div>

    <div class="col-md-2 bs-wizard-step {{Request::is('bus/*/schedule/*/trip/*/booking-info')||
    Request::is('bus/*/schedule/*/trip/*/booking/store') ? 'complete' : 'disabled'}}"><!-- active -->
        <div class="text-center bs-wizard-stepnum">@lang('Step four')</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">@lang('Entering personal information')</div>
    </div>
    <div class="col-md-2 bs-wizard-step {{Request::is('bus/*/schedule/*/trip/*/booking/store') ? 'complete' : 'disabled'}}"><!-- active -->
        <div class="text-center bs-wizard-stepnum">@lang('Step five')</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">@lang('Completion')</div>
    </div>
</div>