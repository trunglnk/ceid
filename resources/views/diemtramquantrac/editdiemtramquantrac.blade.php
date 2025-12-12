@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Cập nhật điểm trạm quan trắc
</h2>
<editdiemtramquantrac :tinhs="{{$tinhs}}" :quans="{{$quans}}" :chitiet="{{$data}}" :xas="{{$xas}}"></editdiemtramquantrac>
@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/checkbox.js') }}"></script>
@endsection