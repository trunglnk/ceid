@extends('layouts.form')

@section ('css')
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Cơ sở pháp lý
</h2>
<danhmuc-cosophaply-index-page :usersystem="{{$usersystem}}"></danhmuc-cosophaply-index-page>
@endsection

@section('script')
<script src="{{ asset('js/app.js') }}"></script>
@endsection
