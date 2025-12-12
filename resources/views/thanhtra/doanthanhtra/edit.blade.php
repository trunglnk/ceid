@extends('layouts.form')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection
@section('content')    
    <doanthanhtra-edit :value="{{json_encode($data)}}" :chitiet="{{json_encode($chitiet)}}" :usersystem="{{$usersystem}}"></doanthanhtra-edit>
@endsection

@section('script')
    <script src="{{asset('js/app.js')}}"></script>
@endsection
