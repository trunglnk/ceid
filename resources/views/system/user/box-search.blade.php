@component('components.box-search', ['routerName' => 'users', 'search' => (empty($search)?null:$search)])
    <div class="row">
        <div class="col-md-6">
            @component('components.multiple-select', [
                'label' => __('field.role'),
                'data' => $roles,
                'text' => 'name',
                'id'=>'search_role',
                'none_required '=> true,
                'value' => 'id',
                'name' => 'search_role',
                'selected' => $search_role,
            ])
            @endcomponent
        </div>
        <div class="col-md-6">
                @component('components.multiple-select-boolean', [
                'label' => __('field.status'),
                'true_text' => __('field.active'),
                'false_text' => __('field.inactive'),
                'name' => 'search_status',
                'selected' => $search_status,
                'required' => false,
            ])
                @endcomponent
        </div>
    </div>

@endcomponent
