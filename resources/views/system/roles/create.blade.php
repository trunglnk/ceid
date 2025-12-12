@component('components.modal-add', [ 
    'type' => 'role', 
    'title' => __('model.role'),
    'width' => '25%',
    'route' => 'system.roles.add',
    ])
    <div class="row">
        <div class="col-sm-6">
            <label for="code" class="control-label">{{__('field.code')}}<span style="color:red">*</span></label>
            <input type="text" class="form-control" id="code" value="{{old('code')}}" name="code" autofocus tabindex="1" required>
        </div>
        <div class="col-sm-6">
            <label for="name" class="control-label">{{__('field.name')}}<span style="color:red">*</span></label>
            <input type="text" class="form-control" id="name" value="{{old('name')}}" name="name" tabindex="2" required>
        </div>
    </div>
    <div class="row">          
        <div class="col-sm-6">
            @component('components.group-checkbox', [
                'title' => __('field.role_system'),
                'id' => 'system',
                'name' => 'system',
                'title_active' => __('label.yes'),
                'title_inactive' => __('label.no'),
                'value_active' => 1,
                'value_inactive' => 0,
                'value' => old('active', 1),       
            ])
            @endcomponent            
        </div> 
        <div class="col-sm-6">
            <label for="description" class="control-label">{{__('field.note')}}<span style="color:red">*</span></label>
            <input type="text" class="form-control" id="description" value="{{old('description')}}" name="description" required tabindex="3">
        </div>
    </div>   
@endcomponent