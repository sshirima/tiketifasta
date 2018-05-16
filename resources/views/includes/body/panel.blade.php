<div class="col-md-10">
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                {{--<li class="active"><a href="#tab1info" data-toggle="tab">Info 1</a></li>
                <li><a href="#tab2info" data-toggle="tab">Info 2</a></li>
                <li><a href="#tab3info" data-toggle="tab">Info 3</a></li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#tab4info" data-toggle="tab">Info 4</a></li>
                        <li><a href="#tab5info" data-toggle="tab">Info 5</a></li>
                    </ul>
                </li>--}}
                @yield('panel_heading')
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                @yield('panel_body')
                {{--<div class="tab-pane fade in active" id="tab1info">Info 1</div>
                <div class="tab-pane fade" id="tab2info">Info 2</div>
                <div class="tab-pane fade" id="tab3info">Info 3</div>
                <div class="tab-pane fade" id="tab4info">Info 4</div>
                <div class="tab-pane fade" id="tab5info">Info 5</div>--}}
            </div>
        </div>
    </div>
</div>