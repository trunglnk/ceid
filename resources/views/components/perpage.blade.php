
    <div class="dataTables_length" id="perpage">
        <label>{{__('component.perpage.title')}} 
            <select name="perpage" id="select_perpage" aria-controls="perpage" class="form-control  " >
                @foreach ($options as $option)
                    <option value="{{$option}}" {{(!empty($perPage)&&$option==$perPage)?'selected':null}} >{{$option}} </option>
                @endforeach
            </select> {{__('component.perpage.entries')}} </label>
    </div>


