@extends('layouts.form')

@section('content')
<h2 class="page-header title_master_form">
    {{__('title.edit_menu')}}
</h2>
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-7">
        <form method="POST" enctype="multipart/form-data" action="{{route('system.menus.update', $menu->id)}}" onsubmit="document.getElementById('onsubmit').disabled=true">
            {{csrf_field() }}
            {{method_field('PUT')}}                       
            <div class="box-body">
                <input type="hidden" id="menu_id" value="{{$menu->id}}"> 
                <div class="row">
                    <div class="col-md-6">
                        <label for={{ 'inputName-detail-'. $menu->id}} class="control-label">{{__('field.name')}}</label>
                        <input type="text" class="form-control" id={{'inputName-detail-' . $menu->id}} value="{{$menu->name}}" name="name" tabindex="1" autofocus required>
            
                        <label for={{ 'inputRouter_link-detail-'. $menu->id}} class="control-label">{{__('field.route_name')}}</label>
                        <input type="text" class="form-control" id={{'inputRouter_link-detail-' . $menu->id}} value="{{$menu->router_link}}" name="router_link" tabindex="2">
                        
                        <label for=id={{ 'fa_icon-detail-'. $menu->id}} class="control-label">{{__('field.icon')}}</label>
                        <input type="text" class="form-control" id={{ 'fa_icon-detail-' . $menu->id}} value="{{$menu->fa_icon}}" name="fa_icon" tabindex="3" required>
                    </div>
                    <div class="col-md-6">
                        <label for={{'parent_id_'. $menu->id}} class="control-label">{{__('field.parent_menu')}}</label>
                        @component('components.select', ['data' => $menu_parents, 
                            'text' => 'name', 
                            'name' => 'parent_id', 
                            'value' => 'id',
                            'id' => 'parent_id_'. $menu->id, 
                            'none_required' => true,
                            'idSelected' => $menu->parent_id,
                            ])
                        @endcomponent
            
                        <label for={{ 'order-detail-' . $menu->id}} class="control-label">{{__('field.order')}}</label>
                        <input type="number" class="form-control" id={{ 'order-detail-' . $menu->id}} value="{{$menu->order}}" name="order" required>
            
                        @component('components.group-checkbox', [
                            'title' => __('field.status'),
                            'id' => 'active_detail_' . $menu->id,
                            'name' => 'active',
                            'title_active' => __('label.active'),
                            'title_inactive' => __('label.inactive'),
                            'value_active' => 1,
                            'value_inactive' => 0,
                            'value' => 1,                         
                        ])
                        @endcomponent
                    </div>
                </div>
                <hr>
                <p class="lead">{{__('title.roles')}}</p>
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                        <label class="control-label">{{__('field.role')}}<span style="color:red">*</span></label>                        
                        @component('components.select', ['data' => $roles, 
                            'text' => 'name', 
                            'name' => 'role_id', 
                            'value' => 'id',
                            'id' => 'role_id',
                            'all' => __('label.select.role'),
                            'none_required' => true                         
                        ])
                        @endcomponent
                        <span class="help-block"></span>
                    </div>                    
                    <div class="col-md-8 col-lg-5">
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
                        @if($menu->functions->count() == 0)
                            <tr id="nonedata">
                                <td colspan="3" style="text-align:center; font-weight:300"><span>{{__('title.none_data')}}</span></td>
                            </tr>
                        @endif
                        @foreach ($menu->functions as $function)
                            <tr>
                                <td>{{$function->role->name}}</td>                                
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
                <a href="{{route('system.menus')}}" id="btnback" class="btn btn-default btn-flat" tabindex="28">
                    <i class="fa fa-undo"></i> {{__('button.back')}}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 

@section('script')
    <script src="{{ asset('js/min/system-menu-detail.min.js') }}"></script>
@endsection