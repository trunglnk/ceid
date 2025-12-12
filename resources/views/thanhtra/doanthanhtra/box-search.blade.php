@component('components.box-search', ['routerName' => 'doanthanhtra', 'search' => (empty($search)?null:$search)])
<div class="row">
    <div class="col-md-6">
        @component('components.multiple-select',[
        'label' => 'Hình thức thanh tra',
        'data'=> $hinhthuc,
        'text' => 'ten',
        'value' => 'id',
        'name' => 'hinh_thuc_thanh_tra',
        'selected'=> $hinh_thuc_thanh_tra,
        'id' => 'hinh_thuc_thanh_tra',
        'tatca'=> 'Tất cả',
        'required'=> false])
        @endcomponent
    </div>
    <div class="col-md-6">
        @component('components.multiple-select',[
        'label' => 'Cơ quan ký QĐ thành lập đoàn thanh tra',
        'data'=> $coquantochuc,
        'text' => 'ten',
        'value' => 'id',
        'name' => 'co_quan_quyet_dinh',
        'selected'=> $co_quan_quyet_dinh,
        'id' => 'co_quan_quyet_dinh',
        'tatca'=> 'Tất cả',
        'required'=> false])
        @endcomponent
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @component('components.multiple-select',[
        'label' => 'Miền',
        'data'=> $miens,
        'text' => 'ten',
        'value' => 'id',
        'name' => 'search_mien',
        'selected'=> $search_mien,
        'id' => 'search_mien',
        'tatca'=> 'Tất cả',
        'required'=> false])
        @endcomponent
    </div>
    <div class="col-md-6">
        @component('components.multiple-select',[
        'label' => 'Tỉnh thành',
        'data'=> $tinhthanhs,
        'text' => 'ten',
        'value' => 'id',
        'name' => 'search_tinh_thanh',
        'id' => 'search_tinh_thanh',
        'selected'=> $search_tinh_thanh,
        'tatca'=> 'Tất cả',
        'required'=> false])
        @endcomponent
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        @component('components.multiple-select',[
        'label' => 'Cơ quan thực hiện',
        'data'=> $coquantochuc,
        'text' => 'ten',
        'value' => 'id',
        'name' => 'co_quan_thuc_hien',
        'selected'=> $co_quan_thuc_hien,
        'id' => 'co_quan_thuc_hien',
        'tatca'=> 'Tất cả',
        'required'=> false])
        @endcomponent
    </div>
</div>
<label>Thời gian ký QĐ thành lập đoàn thanh tra</label>
<div class="row">
    <div class="col-md-6">
        <div class="input-group" style="width:100%">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control datemask" id="search_start_time" value="{{isset($search_start_time)?$search_start_time:\Illuminate\Support\Carbon::now()->addYear(-1)->format(config('app.format_date'))}}" name="search_start_time">
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group" style="width:100%">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control datemask" id="search_end_time" value="{{isset($search_end_time)?$search_end_time:\Illuminate\Support\Carbon::now()->format(config('app.format_date'))}}" name="search_end_time">
        </div>
    </div>
</div>
@endcomponent
