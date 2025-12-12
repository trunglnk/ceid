@component('components.box-search', ['routerName' => 'system.functions', 'search' => (empty($search)?null:$search)])
    <div class="row">
        <div class=" col-md-6">
            @component('components.multiple-select',[
                'label' => __('field.role'),               
                'data'=> $name_role,
                'text' => 'name',
                'value' => 'id',
                'name' => 'search_role',
                'selected'=> $search_role,
                'tatca'=> 'Tất cả',
                'required'=> false])
            @endcomponent
        </div>

        <div class=" col-md-6">
            @component('components.multiple-select',[
                'label' => __('field.menu'),              
                'data'=> $name_menu,
                'text' => 'name',
                'value' => 'id',
                'name' => 'search_menu',
                'selected'=> $search_menu,
                'tatca'=>'Tất cả',
                'required'=>false])
            @endcomponent
        </div>
    </div>
@endcomponent


