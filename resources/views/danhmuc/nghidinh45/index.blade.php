@extends('layouts.form')

@section ('css')
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Cơ sở pháp lý (Theo NĐ 45-2022 CP)
</h2>
<danhmuc-cosophaply-nd45 :usersystem="{{$usersystem}}"></danhmuc-cosophaply-nd45>
@endsection

@section('script')
<script src="{{ asset('js/app.js') }}"></script>
@endsection
