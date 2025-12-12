@extends('layouts.app')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/dist/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
<h1 class="title_master_form">{{__('model.user')}}</h1>
@endsection

@section('content')
<div class="box">
  <div class="box-header">
    <div class="row">
      <div class="col-xs-12 pull-right">
        <a href="#" data-toggle="modal" data-target="#modal-add-user" class="btn btn-flat bg-olive">
          <i class="fa fa-plus"> {{__('button.add')}}</i>
        </a>
        @include('system.user.add', ['roles' => $roles])
      </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <div class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-md-6">
          @component('components.perpage',['options' => [10,20,50,100], 'default'=> $data->perPage(),'data' => $data, 'perPage' =>
          $perPage, 'routerName' => 'users'])
          @endComponent
        </div>
          <div class="col-md-6">
              @include('system.user.box-search')
          </div>
      </div>
      <br/>
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-hover table-responsive" role="grid">
            <tbody>
              <tr class="row-table-header">
                <th>{{__('field.name')}}</th>
                <th>{{__('field.username')}}</th>
                <th>{{__('field.email')}}</th>
                <th>{{__('field.role')}}</th>
                <th style="width: 10%;text-align:center;">{{__('field.status')}}</th>
                <th style="width: 5%;text-align:center;">{{__('field.edit')}}</th>
                <th style="width: 5%;text-align:center">{{__('field.delete')}}</th>
              </tr>
              @foreach ($data as $user)
                <tr role="row" class="odd">
                  <td>
                    <a href="{{route('users.update', $user->id)}}">
                      {{$user->name}}</i>
                    </a>
                  </td>
                  <td>
                    <a href="{{route('users.update', $user->id)}}">
                      {{$user->username}}</i>
                    </a>
                  </td>
                  <td>{{$user->email}}</td>
                  <td>{{$user->role->name}}</td>
                  <td style="text-align:center;">
                    @if ($user->active)
                    <strong>
                      <small class="text-olive">{{__('label.active')}}</small>
                    </strong>
                    @else
                    <strong>
                      <small class="text-gray">{{__('label.inactive')}}</small>
                    </strong>
                    @endif
                  </td>
                  <td align="center">
                    <a href="{{route('users.update', $user->id)}}">
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                  <td align="center">
                    <a href="#" data-toggle="modal" data-target="{{ '#modal-delete-user-' . $user->id }}">
                       <i class="fa fa-trash-o btn"></i>
                    </a>
                    @include('system.user.delete',['user' => $user])
                  </td>
                </tr>
              @endforeach
            </tbody>
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
