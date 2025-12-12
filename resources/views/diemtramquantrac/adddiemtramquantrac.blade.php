@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Thêm mới điểm trạm quan trắc
</h2>
<adddiemtramquantrac :tinhs="{{$tinhs}}" :quans="{{$quans}}" :coquans="{{$coquans}}" :noicaps="{{$noicaps}}" :xas="{{$xas}}"></adddiemtramquantrac>
@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/checkbox.js') }}"></script>
@endsection