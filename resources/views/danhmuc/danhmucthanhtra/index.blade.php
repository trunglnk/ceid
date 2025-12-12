@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">
    danh mục
</h2>
<danhmuc-thanhtra :usersystem="{{$usersystem}}"></danhmuc-thanhtra>
@endsection

@section('script')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/checkbox.js') }}"></script>
@endsection