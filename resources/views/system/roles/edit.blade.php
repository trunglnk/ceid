@extends('layouts.form')

@section('content')
<h2 class="page-header title_master_form">
    {{__('title.edit_role')}}
</h2>
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-7">
        <form method="POST" enctype="multipart/form-data" action="{{route('system.roles.update', $role->id)}}" onsubmit="document.getElementById('onsubmit').disabled=true">
            {{csrf_field() }}
            {{method_field('PUT')}}                       
            <div class="box-body">
                <input type="hidden" id="role_id" value="{{$role->id}}"> 
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">{{__('field.code')}}<span style="color:red">*</span></label>
                        @if ($role->system)
                        <input type="text" class="form-control" value="{{$role->code}}" name="code" tabindex="1" required autoficus readonly>
                        @else
                            <input type="text" class="form-control" value="{{$role->code}}" name="code" tabindex="1" required autoficus> 
                        @endif            
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">{{__('field.name')}}<span style="color:red">*</span></label>
                        <input type="text" class="form-control" value="{{$role->name}}" name="name" required tabindex="2">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @component('components.group-checkbox', [
                            'title' => __('field.role_system'),
                            'id' => 'system',
                            'name' => 'system',
                            'title_active' => __('label.yes'),
                            'title_inactive' => __('label.no'),
                            'value_active' => 1,
                            'value_inactive' => 0,
                            'value' => $role->system,       
                        ])
                        @endcomponent
                    </div>        
                    <div class="col-md-6">
                        <label class="control-label">{{__('field.note')}}<span style="color:red">*</span></label>
                        <input type="text" class="form-control" name="description" required value="{{$role->description}}" tabindex="3">
                    </div>        
                </div>
                <hr>
                <p class="lead">{{__('title.functions')}}</p>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">{{__('field.menu')}}<span style="color:red">*</span></label>                        
                        @component('components.select', ['data' => $menu_all, 
                            'text' => 'name', 
                            'name' => 'menu_id', 
                            'value' => 'id',
                            'id' => 'menu_id',
                            'all' => __('label.select.menu'),
                            'none_required' => true                         
                        ])
                        @endcomponent
                        <span class="help-block"></span>
                    </div>                    
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8">
                                @component('components.group-checkbox', [
                                    'title' => __('field.home_page'),
                                    'id' => 'home_router',
                                    'name' => 'home_router',
                                    'title_active' => __('label.yes'),
                                    'title_inactive' => __('label.no'),
                                    'value_active' => 1,
                                    'value_inactive' => 0,
                                    'value' => false,       
                                ])
                                @endcomponent
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-flat pull-left btn-block" tabindex="9" id="add_role_menu" style="margin-top: 20px;">
                                    <i class="fa fa-plus"></i> {{__('button.add')}}
                                </button>
                            </div>
                        </div>                        
                    </div>                                                                                                  
                </div>                
                <span id="message" class="message"></span>              
                <br/>
                <table class="table table-hover" id="tableRoleMenu">
                    <tbody>
                        <tr>
                            <th>{{__('field.name')}}</th>
                            <th style="text-align:center">{{__('field.home_page')}}</th>                            
                            <th style="text-align:center"></th>
                        </tr>
                        @if($role->functions->count() == 0)
                            <tr id="nonedata">
                                <td colspan="3" style="text-align:center; font-weight:300"><span>{{__('title.none_data')}}</span></td>
                            </tr>
                        @endif
                        @foreach ($role->functions as $function)
                            <tr>
                                <td>{{$function->menu->name}}</td>                                
                                @if ($function->home_router)
                                    <td align="center"><span class="label bg-maroon flat">{{__('label.home_page')}}</span></td>
                                @else
                                    <td align="center"><span class="label bg-olive flat">{{__('label.normal_page')}}</span></td>
                                @endif                                
                                <td align="center">
                                    <a href="#" class="delete-item" id={{$function->id}}>
                                         <i class="fa fa-trash-o btn"></i>
                                    </a>
                                </td>
                            </tr> 
                        @endforeach                                                                                                                                                                                      
                    </tbody>
                </table>
            </div>    
            <div class="box-footer">
                <button type="submit" id="onsubmit" class="btn bg-olive btn-flat pull-right" tabindex="27">
                    <i class="fa fa-check"></i> {{__('button.edit')}}
                </button>
                <a href="{{route('system.roles')}}" id="btnback" class="btn btn-default btn-flat" tabindex="28">
                    <i class="fa fa-undo"></i> {{__('button.back')}}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 

@section('script')
    <script src="{{ asset('js/min/system-role-detail.min.js') }}"></script>
@endsection