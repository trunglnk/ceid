@extends('layouts.app')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/dist/dataTables.bootstrap.min.css') }}">  
@endsection 

@section('title')
<h1 class="title_master_form">{{__('model.menu')}}</h1>
@endsection

@section('content')
<div class="box">
  <div class="box-header">    
    <div class="row">     
      <div class="col-xs-12 pull-right">
        <a href="#" data-toggle="modal" data-target="{{ '#modal-add-menu'}}" class="btn btn-flat bg-olive">
          <i class="fa fa-plus"> {{__('button.add')}}</i>
        </a>                   
        @include('system.menus.add') 
      </div>
    </div>
  </div>
  <div class="box-body">
    <div class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-6">
          @component('components.perpage',['options' => [10,20,50,100], 'default'=> $data->perPage(),'perPage' => $perPage, 'data' => $data, 'routerName' => 'system.menus'])
          @endComponent
        </div>
        <div class="col-sm-6">          
          <div id="search" class="dataTables_filter">
            @component('components.search',['title' => __('label.search'), 'routerName' => 'system.menus', 'search' => (empty($search)?null:$search)])
            @endComponent           
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-hover table-responsive">            
            <tbody>  
                <tr class="row-table-header">
                  <th>{{__('field.parent_menu')}}</th>
                  <th>{{__('field.name')}}</th>
                  <th>{{__('field.route_name')}}</th>
                  <th style="width: 5%;text-align:center;">{{__('field.icon')}}</th>                
                  <th style="width: 5%;text-align:center;">{{__('field.order')}}</th>
                  <th style="width: 10%;text-align:center;">{{__('field.status')}}</th>
                  <th style="width: 5%;text-align:center;">{{__('field.edit')}}</th>
                  <th style="width: 5%;text-align:center;">{{__('field.delete')}}</th>
                </tr>           
              @foreach ($data as $menu)                
                <tr>
                  <td>
                    @if(isset($menu->parent))
                      {{$menu->parent->name}}
                    @else
                      ---
                    @endif                    
                  </td>
                  <td>{{$menu->name}}</td>
                  <td>{{$menu->router_link}}</td>
                  <td align="center">{{$menu->fa_icon}}</td>
                  <td align="center">{{$menu->order}}</td> 
                  <td align="center">
                    @if ($menu->active)
                      <small class="label bg-olive flat">{{__('label.active')}}</small>                  
                    @else
                      <small class="label bg-navy flat">{{__('label.inactive')}}</small>
                    @endif                                                 
                  </td>                                   
                  <td align="center">
                    <a href="{{route('system.menus.update', $menu->id)}}">
                      <i class="fa fa-edit"></i>
                    </a>                                                                            
                  </td>
                  <td align="center">
                    <a href="#" data-toggle="modal" data-target="{{ '#modal-delete-menu-' . $menu->id }}">
                       <i class="fa fa-trash-o btn"></i>
                    </a>                   
                    @include('system.menus.delete', ['menu' => $menu])                   
                  </td>
                </tr>
              @endforeach              
            </tbody>
            <tfoot>
              @if (count($data) > 10)
                <tr>
                  <th>{{__('field.parent_menu')}}</th>
                  <th>{{__('field.name')}}</th>
                  <th>{{__('field.route_name')}}</th>
                  <th style="width: 5%;text-align:center;">{{__('field.icon')}}</th>                
                  <th style="width: 5%;text-align:center;">{{__('field.order')}}</th>
                  <th style="width: 10%;text-align:center;">{{__('field.status')}}</th>
                  <th style="width: 5%;text-align:center;">{{__('field.edit')}}</th>
                  <th style="width: 5%;text-align:center;">{{__('field.delete')}}</th>
                </tr>
              @endif              
            </tfoot>
          </table>
        </div>
      </div>
      @component('components.pagination', ['pageShow' => 3, 'data' => $data])
      @endComponent
    </div>
  </div>
  </div>
@endsection

@section('script')             
@endsection

      