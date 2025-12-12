@extends('layouts.form')

@section('content')
<h2 class="page-header title_master_form">
    {{__('title.edit_user')}}
</h2>
<div class="row">
    <div class="col-md-12">
        <form method="POST" enctype="multipart/form-data" action="{{route('users.update', $user->id)}}" onsubmit="$('.onsubmit').attr('disabled',true)">
            {{csrf_field() }}   
            <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">         
            <div class="box-body">
                <div class="row">
                    <div class="col col-md-5">
                        <label class="control-label">{{__('field.name')}}<span style="color:red">*</span></label>
                        <input type="text" class="form-control" value="{{$user->name}}" name="name" tabindex="1" required autofocus>
                
                        <label class="control-label">{{__('field.email')}}<span style="color:red">*</span></label>
                        <input type="email" class="form-control" value="{{$user->email}}" name="email" tabindex="2" required>        
                              
                        <label class="control-label" >{{__('field.password')}}</label>
                        <input type="password" class="form-control" name="password" tabindex="3">
                    </div>
                    <div class="col col-md-5">
                        <label class="control-label">{{__('field.username')}}<span style="color:red">*</span></label>
                        <input type="text" class="form-control" value="{{$user->username}}" tabindex="4" name="username" required>
                        
                        <label class="control-label">{{__('field.role')}}<span style="color:red">*</span></label>                                                
                        @component('components.select', ['data' => $roles, 
                            'text' => 'name', 
                            'name' => 'role_id', 
                            'value' => 'id',
                            'id' => 'role_id',                         
                            'idSelected' => $user->role_id                            
                        ])
                        @endcomponent        
                        @component('components.group-checkbox', [
                                'title' => __('field.status'),
                                'id' => 'inputActive-detail-' . $user->id,
                                'name' => 'active',
                                'title_active' => __('field.active'),
                                'title_inactive' => __('field.inactive'),
                                'value_active' => 1,
                                'value_inactive' => 0,
                                'value' => $user->active,
                            ])
                        @endcomponent
                    </div>
                    @if($user->role->code == 'thuky')
                    <div class="col col-md-2">
                        @component('components.multiple-select',[
                            'label' => 'Phụ trách tỉnh',
                            'data'=> $tinhs,
                            'text' => 'ten',
                            'value' => 'id',
                            'name' => 'tinh_thanh_ids',
                            'selected'=> $tinh_thanh_ids,
                            'tatca'=> 'Tất cả'])
                        @endcomponent
                    </div>
                    @endif
                </div>                               
            </div>
            <div class="box-footer">
                <button type="submit" class="btn bg-olive btn-flat pull-right onsubmit" tabindex="27">
                    <i class="fa fa-check"></i> {{__('button.edit')}}
                </button>
                <a href="{{route('users')}}" id="btnback" class="btn btn-default btn-flat" tabindex="28">
                    <i class="fa fa-undo"></i> {{__('button.back')}}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 

@section('script')
    
@endsection