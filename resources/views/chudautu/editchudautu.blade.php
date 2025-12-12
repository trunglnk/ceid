@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Cập nhật chủ đầu tư
</h2>
<editchudautu :tinhs="{{$tinhs}}" :quans="{{$quans}}" :xas="{{$xas}}" :chitiet="{{$data}}" :coquans="{{$coquans}}" :noicaps="{{$noicaps}}"></editchudautu>
@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/checkbox.js') }}"></script>
@endsection