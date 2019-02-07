@extends('admins.layouts.master')

@section('title')
    {{ __('page_home.page_tile_admin') }}
@endsection

@section('content-body')
    <div class="row"  style="padding: 5px">
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div id="health_box" class="small-box bg-red">
                <div class="inner">
                    <h3 id="health_percent">0%</h3>

                    <div id="system_status">System health</div>
                    <div id="report_summary"></div>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{$data['merchants_active_count']}}</h3>
                    <p>Merchants</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <a href="{{route('admin.merchant.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    {{--<h3>53<sup style="font-size: 20px">%</sup></h3>--}}
                    <h3>{{$data['buses_count']}}</h3>

                    <p>Buses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bus"></i>
                </div>
                <a href="{{route('admin.buses.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$data['tickets_active_count']}}</h3>

                    <p>Active tickets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <a href="{{route('admin.tickets.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row" style="padding: 5px">
        @csrf
    </div>
@endsection

@section('import_js')

    <script src="{{ URL::asset('js/toaster/jquery.toaster.js') }}"></script>
    <script src="{{ URL::asset('js/admin/monitor_server.js') }}"></script>
    <script>
        var servers = {!! json_encode($data['servers'], JSON_HEX_TAG) !!};
        var server_ips = {!! json_encode($data['server_ips'], JSON_HEX_TAG) !!};
    </script>

@endsection