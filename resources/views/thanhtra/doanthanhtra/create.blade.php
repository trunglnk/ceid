@extends('layouts.form')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection
@section('content')   
 <doanthanhtra-add></doanthanhtra-add>
@endsection

@section('script')
    <script src="{{asset('js/app.js')}}"></script>
@endsection