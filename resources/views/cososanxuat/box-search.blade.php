@component('components.box-search', ['routerName' => 'cososanxuat', 'search' => (empty($search)?null:$search)])
<div class="row">
    @if(!empty($order_column))
    <input type="hidden" value="{{$order_column}}" id="order_column" name="order_column" />
    @endif
    @if(!empty($order_direction))
    <input type="hidden" value="{{$order_direction}}" id="order_direction" name="order_direction" />
    @endif

</div>
<div class="row">
    <div class="col-md-3">
        <label for="input-cososanxuat">Tên cơ sở sản xuất</label>
        <input type="text" class="form-control " id="input_cososanxuat" placeholder="Tên cơ sở sản xuất" style="height:32px" name="search_tencososanxuat" value="{{$search_tencososanxuat}}">
        <label for="input-masoDKKD">Mã số ĐKKD/ Mã số thuế</label>
        <input type="text" class="form-control " id="input_masoDKKD" placeholder="Mã số ĐKKD/ Mã số thuế" style="height:32px" name="search_masothue" value="{{$search_masothue}}">
    </div>

    <div class="col-md-3">
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

    <div class="col-md-3">
        @component('components.multiple-select',[
        'label' => 'Loại hình cơ sở',
        'data'=> $loaicosos,
        'text' => 'ten',
        'value' => 'id',
        'name' => 'search_loai_co_so',
        'selected'=> $search_loai_co_so,
        'id' => 'search_loai_hinh_co_so',
        'tatca'=> 'Tất cả',
        'required'=> false])
        @endcomponent
    </div>

    <div class="col-md-3">
        <label for="input-year">Năm</label>
        <input type="number" class="form-control" id="input_year" placeholder="Năm" style="height:32px" name="search_year" value="{{$search_year}}" min="1970" max="3000">
    </div>
</div>

@endcomponent
