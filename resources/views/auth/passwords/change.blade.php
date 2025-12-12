@component('components.modal-update', [ 
    'type' => 'auth-changepassword',
    'title' => __('auth.passwords.change.title'),
    'width' => '25%',
    'route' => 'profile.changepassword',
    'data' => $user,
    'method' => 'post'])

    <div class="row">
        <div class="col-md-12">
            <label for="inputPassword" class="control-label">{{__('title.password')}}</label>
            <input type="password" class="form-control" id="inputPassword" name="password">
            
            <label for="inputNewPassword" class="control-label">{{__('title.new_password')}}</label>
            <input type="password" class="form-control" id="inputNewPassword" name="new_password">

            <label for="inputComfirmNewPassword" class="control-label">{{__('title.repeat_new_password')}}</label>
            <input type="password" class="form-control" id="inputComfirmPassword" name="new_password_confirmation">
        </div>        
    </div>        
@endcomponent