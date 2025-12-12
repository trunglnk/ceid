@if(isset($none_required) && ($none_required))
        <select data="{{$data}}" 
        {{!empty($disabled) ? 'disabled'  : null}}  
        {{!empty($name) ? 'name=' . $name  : null}} 
        class = "{{isset($class)? ('form-control '.$class): ('form-control') }}"
        {{empty($id) ? null : 'id='.$id}} {{isset($tabindex) ? ('tabindex=' . $tabindex) : null}} 
        {{isset($onchange) ? ('onchange=' . $onchange) : null}} 
        {{isset($autofocus) ? ('autofocus') : null}}>
                <option value={{null}}>{{empty($all)? __('component.select.none'): $all}}</option>
                @foreach($data as $item)
                <option value={{empty($value) ? $item->id : ($item->$value)}} {{(isset($idSelected) && isset($item->$value) && ($item->$value) == $idSelected) ? 'selected="selected"' : null}}>{{$item->$text}} </option>
                @endforeach
        </select>
@else
        <select data="{{$data}}" 
                {{!empty($disabled) ? 'disabled'  : null}} 
                {{empty($tabindex)? '0':'tabindex='.$tabindex}}
                required 
                {{!empty($name) ? 'name=' . $name  : null}}
                class = "{{isset($class)? ('form-control '.$class): ('form-control') }}"
                {{empty($id) ? null : 'id='.$id}} 
                {{isset($tabindex) ? ('tabindex=' . $tabindex) : null}}
                {{isset($onchange) ? ('onchange=' . $onchange) : null}}
                {{isset($autofocus) ? ('autofocus') : null}}>
                <option value={{null}}>{{empty($all)? __('component.select.none'): $all}}</option>
                @foreach($data as $item)
                <option value={{empty($value) ? $item->id : ($item->$value)}} {{(isset($idSelected) && isset($item->$value) && ($item->$value) == $idSelected) ? 'selected="selected"' : null}}>{{$item->$text}} </option>
                @endforeach
        </select>
@endif
