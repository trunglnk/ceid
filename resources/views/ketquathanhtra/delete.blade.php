@component('components.confirm-delete', [ 
    'type' => 'delete-ketquathanhtra',
    'title' => 'Kết quả thanh tra',
    'width' => '35%',
    'route' => 'ketquathanhtra.delete',
    'data' => $data,
    'method' => 'delete',
    ])
    <div class="row">
        <div class="col-sm-6">
            <label for="code" class="control-label">Số quyết định</label>
            <input type="text" class="form-control" value="{{$data->so_quyet_dinh}}" disabled>
        </div>
        <div class="col-sm-6">
            <label for="code" class="control-label">Quyết định thanh tra</label>
            <input type="text" class="form-control" value="{{$data->quyetDinhThanhTra->so_quyet_dinh}}" disabled>
        </div>
    </div>
@endcomponent