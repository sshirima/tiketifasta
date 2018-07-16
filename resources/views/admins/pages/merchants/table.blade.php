<table class="table table-responsive" id="categories-table">
    <thead>
    <tr>
        <th>Merchant name</th>
        <th>Start of contract</th>
        <th>End of contract</th>
        <th>Status</th>
        <th>Created date</th>
        <th> Authorization</th>
        <th class="pull-right" colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($merchants as $merchant)
        <tr>
            <td>{!! $merchant->name !!}</td>
            <td>{!! $merchant->contract_start !!}</td>
            <td>{!! $merchant->contract_end !!}</td>
            <td>@if($merchant->status) <div class="label label-success">Active</div> @else <div class="label label-danger">Inactive</div>@endif</td>
            <td>{!! $merchant->created_at !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.merchant.authorize', $merchant->id], 'method' => 'get']) !!}
                    <button class='btn {{$merchant->status?'btn-danger':'btn-success'}} btn-xs'>{{$merchant->status?'Disable account':'Enable account'}}</button>
                {!! Form::close() !!}
            </td>
            <td class="pull-right">
                {!! Form::open(['route' => ['admin.merchant.delete', $merchant->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.merchant.edit', [$merchant->id]) !!}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    <a href="{!! route('admin.merchant.show', [$merchant->id]) !!}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.merchant.delete', [$merchant->id]) !!}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-remove"></i></a>
                    </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>