@component('components.modal-add', [ 
    'type' => 'user', 
    'title' => __('model.user'),
    'width' => '40%',
    'route' => 'users.add',
    ])
    <div class="row">
        <div class="col-sm-6">
            <label for="name" class="control-label">{{__('field.name')}}<span style="color:red">*</span></label>
            <input type="text" class="form-control" id="name"  value="{{old('name')}}"  name="name" tabindex="1" autofocus required>           

            <label for="email" class="control-label">{{__('field.email')}}<span style="color:red">*</span></label>
            <input type="email" class="form-control" id="email"  value="{{old('email')}}" name="email" tabindex="3" required>                        
            <label for="password" class="control-label">{{__('field.password')}}<span style="color:red">*</span></label>
            <input type="password" class="form-control"  value="{{old('password')}}" id="password" name="password" tabindex="8" required minlength="6" maxlength="255">

        </div>
        <div class="col-md-6">
        <label for="username" class="control-label">{{__('field.username')}}<span style="color:red">*</span></label>
            <input type="text" class="form-control"  value="{{old('username')}}" id="username" name="username" tabindex="2" required>
            
            <label for="role_id" class="control-label">{{__('field.role')}}<span style="color:red">*</span></label>
            @component('components.select', ['data' => $roles, 
                'text' => 'name', 
                'name' => 'role_id', 
                'value' => 'id',
                'id' => 'role_id', 
                'idSelected'=>  old('role_id'),
                'tabindex'=>4
                ])
            @endcomponent            
            <label for="password_confirmation" class="control-label">{{__('field.repeat_password')}}<span style="color:red">*</span></label>
            <input type="password" class="form-control"  value="{{old('password_confirmation')}}" id="password_confirmation" name="password_confirmation" tabindex="8" required minlength="6" maxlength="255">
        </div>
        <div class="col-md-12">
            @component('components.group-checkbox', [
                'title' => __('field.status'),
                'id' => 'active',
                'name' => 'active',
                'title_active' => __('field.active'),
                'title_inactive' => __('field.inactive'),
                'value_active' => 1,
                'value_inactive' => 0,
                'value' => old('active',1),
            ])
            @endcomponent
        </div>
    </div>
@endcomponent