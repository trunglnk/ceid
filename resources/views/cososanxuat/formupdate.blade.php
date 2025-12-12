@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<update-co-so-san-xuat :usersystem="{{$usersystem}}" :tinhs="{{$tinhs}}" :huyens="{{$huyens}}" :chudautus="{{$chudautus}}" :chudautu="{{$chudautu}}" :cososanxuat="{{$cososanxuat}}"></update-co-so-san-xuat>
@endsection

@section('script')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/checkbox.js') }}"></script>
@endsection