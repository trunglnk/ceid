@component('components.modal-add', [ 
        'type' => 'menu', 
        'title' => __('model.menu'),
        'route' => 'system.menus.add',
        'width' => '35%',
    ])
    <div class="row">
        <div class="col-sm-6">
            <label for="name" class="control-label">{{__('field.name')}}</label>
            <input type="text" class="form-control" id="name" value="{{old('name')}}" name="name" tabindex="1" autofocus required>

            <label for="router_link" class="control-label">{{__('field.route_name')}}</label>
            <input type="text" class="form-control" id="router_link" value="{{old('router_link')}}" name="router_link" tabindex="2">

            <label for"fa_icon" class="control-label">{{__('field.icon')}}</label>
            <input type="text" class="form-control" id="fa_icon" value="{{old('fa_icon')}}" name="fa_icon" tabindex="3" required>
        </div>
        <div class="col-sm-6">
            <label for="parent_ids" class="control-label">{{__('field.parent_menu')}}</label>
            @component('components.select', ['data' => $menu_parents, 
                'text' => 'name', 
                'name' => 'parent_id', 
                'value' => 'id',
                'id' => 'parent_id', 
                'none_required' => true,
                'idSelected'=> old('parent_id'),
                'tabindex'=>4
                ])
            @endcomponent

            <label for="order" class="control-label">{{__('field.order')}}</label>
            <input type="number" class="form-control" id="order"value="{{old('order')}}" name="order" tabindex="5">

            @component('components.group-checkbox', [
                'title' => __('field.status'),
                'id' => 'active',
                'name' => 'active',
                'title_active' => __('field.active'),
                'title_inactive' => __('field.inactive'),
                'value_active' => 1,
                'value_inactive' => 0,
                'value' => old('active', 1),                         
            ])
            @endcomponent
        </div>
    </div>       
@endcomponent