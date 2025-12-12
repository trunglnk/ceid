@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Kết quả đo đạc và phân tích mẫu Môi trường
</h2>
<ketquaphantichmau :usersystem="{{$usersystem}}"></ketquaphantichmau>
@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/checkbox.js') }}"></script>
@endsection