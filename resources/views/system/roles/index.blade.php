@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dist/dataTables.bootstrap.min.css') }}">  
@endsection

@section('title')
<h1 class="title_master_form">{{__('model.role')}}</h1>
@endsection 

@section('content')
    <div class="box">
        <div class="box-header">            
            <div class="row">
                <div class="col-xs-12 pull-right">
                    <a href="#" data-toggle="modal" data-target="{{ '#modal-add-role'}}" class="btn btn-flat bg-olive">
                        <i class="fa fa-plus"> {{__('button.add')}}</i>
                    </a>
                    @include('system.roles.create')
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        @component('components.perpage',['options' => [10,20,50,100], 'default'=> $data->perPage(),'perPage' => $perPage, 'data' => $data, 'routerName' => 'system.roles'])
                        @endComponent
                    </div>
                    <div class="col-sm-6">
                        <div id="search" class="dataTables_filter">
                            @component('components.search',['title' => __('label.search'), 'routerName' => 'system.roles', 'search' => (empty($search) ? null : $search)])
                            @endComponent
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover table-responsive" role="grid">                            
                            <tbody>
                            <tr class="row-table-header">
                                <th style="width: 20%;">{{__('field.code')}}</th>
                                <th style="width: 20%;">{{__('field.name')}}</th>
                                <th style="width: 20%;text-align: center">{{__('field.role_system')}}</th>
                                <th style="width: 20%;">{{__('field.note')}}</th>
                                <th style="width: 10%; text-align: center">{{__('field.edit')}}</th>
                                <th style="width: 10%; text-align: center">{{__('field.delete')}}</th>
                            </tr>
                            @foreach ($data as $role)
                                <tr role="row">
                                    <td>{{$role->code}}</td>
                                    <td>{{$role->name}}</td>
                                    <td style="text-align:center;">
                                        @if ($role->system)
                                            <input type="radio" checked>
                                        @else
                                            <input type="radio" disabled>
                                        @endif
                                    </td>
                                    <td>{{$role->description}}</td>
                                    <td align="center">
                                        <a href="{{route('system.roles.update', $role->id)}}">
                                            <i class="fa fa-edit"></i>
                                        </a>                                                                            
                                    </td>
                                    <td align="center">
                                        @if ($role->system)
                                             <i class="fa fa-trash-o btn"></i>
                                        @else
                                            <a href="#" data-toggle="modal" data-target="{{ '#modal-delete-role-' . $role->id }}">
                                                 <i class="fa fa-trash-o btn"></i>
                                            </a>
                                            @include('system.roles.delete',['role' => $role]) 
                                        @endif                                                                               
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            @if (count($data) > 10)
                                <tr>
                                    <th style="width: 20%;">{{__('field.code')}}</th>
                                    <th style="width: 20%;">{{__('field.role')}}</th>
                                    <th style="width: 20%;text-align: center">{{__('field.role_system')}}</th>
                                    <th style="width: 20%;">{{__('field.note')}}</th>
                                    <th style="width: 10%; text-align: center">{{__('field.edit')}}</th>
                                    <th style="width: 10%; text-align: center">{{__('field.delete')}}</th>
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
        <!-- /.box-body -->
    </div>
@endsection

@section('script')        
@endsection