@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection
@section('title')
<h1 class="title_master_form">Kết quả thanh tra</h1>
@endsection

@section('content')
<div class="box">
  <cososanxuat-component :usersystem="{{$usersystem}}" :data="{{json_encode($data)}}" :loaicosos="{{$loaicosos}}" :tinhthanhs="{{$tinhthanhs}}"></cososanxuat-component>
  {{-- <div class="box-body">
    <div class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-4">
          @component('components.perpage',['options' => [10,20,50,100], 'default'=> $data->perPage(),
          'perPage'=>$perPage, 'data' => $data, 'routerName' => 'danhmuc.item'])
          @endComponent
        </div>
        <div class="col-sm-8">
          @include('cososanxuat.box-search')
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-sm-12">
          <table-component :data="{{json_encode($data)}}"></table-component>
</div>
</div>

</div>
</div> --}}
</div>
@endsection

@section('script')
<script src="{{asset('js/app.js')}}"></script>
<script src="{{ asset('js/cososanxuat/exportexcel.js') }}"></script>
@endsection