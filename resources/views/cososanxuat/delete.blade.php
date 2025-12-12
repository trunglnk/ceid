@component('components.confirm-delete', [ 
    'type' => 'delete-cososanxuat',
    'title' => 'Cơ sở thanh tra',
    'width' => '35%',
    'route' => 'cososanxuat.delete',
    'data' => $data,
    'method' => 'delete',
    ])
    <div class="row">
        <div class="col-sm-12">
            <label for="code" class="control-label">Tên</label>
            <input type="text" class="form-control" value="{{$data->ten}}" disabled>
        </div>
    </div>
@endcomponent