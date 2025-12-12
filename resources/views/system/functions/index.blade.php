@extends('layouts.app')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/dist/dataTables.bootstrap.min.css') }}">  
@endsection

@section('title')
<h1 class="title_master_form">{{__('model.function')}}</h1>
@endsection

@section('content')
<div class="box">
  <div class="box-header">       
    <div class="row">     
      <div class="col-xs-12 pull-right">
        <a href="#" data-toggle="modal" data-target="#modal-add-function" class="btn btn-flat bg-olive">
          <i class="fa fa-plus"> {{__('button.add')}}</i>
        </a>                   
        @include('system.functions.add')    
      </div>
    </div>
  </div>
  <div class="box-body">
    <div class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-6">
          @component('components.perpage',['options' => [10,20,50,100], 'default'=> $data->perPage(),'perPage' => $perPage, 'data' => $data, 'routerName' => 'system.functions'])
          @endComponent
        </div>
        <div class="col-sm-6">
          @include('system.functions.box-search')
        </div>
      </div>
      <br/>
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-hover table-responsive">            
            <tbody>
              <tr class="row-table-header">
                <th style="width: 40%;">{{__('field.name')}}</th>
                <th style="width: 45%;">{{__('field.catagory')}}</th>
                <th style="width: 10%;text-align:center">{{__('field.home_page')}}</th>
                <th style="width: 5%;text-align:center">{{__('field.delete')}}</th>
              </tr>             
              @foreach ($data as $rolemenu)                
                <tr role="row">
                  <td>{{$rolemenu->role->name}}</td>
                  <td>{{$rolemenu->menu->name}}</td>               
                  <td style="text-align:center;">
                    @if ($rolemenu->home_router)
                      <small class="label bg-maroon block flat">{{__('label.home_page')}}</small>
                    @else
                      <small class="label bg-olive block flat">{{__('label.normal_page')}}</small>
                    @endif
                  </td>               
                  <td align="center">
                    <a href="#" data-toggle="modal" data-target="{{ '#modal-delete-rolemenu-' . $rolemenu->id }}">
                       <i class="fa fa-trash-o btn"></i>
                    </a>                   
                    @include('system.functions.delete',['rolemenu' => $rolemenu])            
                  </td>                   
                </tr>
              @endforeach              
            </tbody>
            <tfoot>
              @if (count($data) > 10)
                <tr>
                  <th style="width: 40%;">{{__('field.name')}}</th>
                  <th style="width: 45%;">{{__('field.catagory')}}</th>
                  <th style="width: 10%;text-align:center">{{__('field.home_page')}}</th>
                  <th style="width: 5%;text-align:center">{{__('field.delete')}}</th>
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
  <script src="{{ asset('js/min/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/min/dataTables.bootstrap.min.js') }}"></script>      
@endsection
      