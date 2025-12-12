@component('components.confirm-delete', [ 
    'type' => 'delete-doanthanhtra',
    'title' => __('đoàn thanh tra'),
    'width' => '40%',
    'route' => 'doanthanhtra.delete',
    'data' => $data,
    'method' => 'delete',
    ])
    <div class="row">
        <p align="center">Bạn có muốn xóa mục này</p>   
    </div>
@endcomponent