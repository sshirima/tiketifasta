<table class="table table-responsive" id="categories-table">
    <thead>
    <tr>
        <th>Merchant name</th>
        <th>Created date</th>
        <th class="pull-right" colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($merchants as $merchant)
        <tr>
            <td>{!! $merchant->name !!}</td>
            <td>{!! $merchant->created_at !!}</td>
            <td class="pull-right">
                {!! Form::open(['route' => ['admin.merchant.delete', $merchant->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
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