@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Thêm mới chủ đầu tư
</h2>
<addchudautu :tinhs="{{$tinhs}}" :quans="{{$quans}}" :coquans="{{$coquans}}" :noicaps="{{$noicaps}}"></addchudautu>
@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/checkbox.js') }}"></script>
@endsection