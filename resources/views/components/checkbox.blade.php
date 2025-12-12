@if(isset($id))
    <div class="checkbox" style="margin-top: 30px;">
        <input type="hidden"  {{empty($name)?null:'name='.$name}} value={{$value_unchecked}} />
        <input type="checkbox" {{empty($disabled)?null:$disabled}} {{empty($id)?null:'id='.$id}}  value={{$value_checked}} {{empty($name)?null:'name='.$name}}  {{($value == $value_checked) ? 'checked' : null}} {{ (isset($disabled) && ($disabled == true)) ? 'disabled' : null }} {{ isset($tabindex) ? 'tabindex='.$tabindex : null }}> {{$title}}
    </div>
@else
    <div class="checkbox">
        <input type="hidden" {{empty($name)?null:'name='.$name}} value={{$value_unchecked}} />
        <input type="checkbox" {{empty($disabled)?null:$disabled}} value={{$value_checked}} {{empty($name)?null:'name='.$name}}  {{($value == $value_checked) ? 'checked' : null}} {{ (isset($disabled) && ($disabled == true)) ? 'disabled' : null }} {{ isset($tabindex) ? 'tabindex='.$tabindex : null }}> {{$title}}
    </div>
@endif