@extends('layouts.form')
@section('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
<style>
.google-map {
    height: 250px !important;
}
</style>
@endsection

@section('content')
<div id="app">
    <cososanxuat-add></cososanxuat-add>
</div>
@endsection

@section('script')
<script type="text/javascript"
    src="{{'https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry,places,visualization,drawing&key=' . config('app.google_api_key')}}">
</script>
<script src="{{asset('js/app.js')}}"></script>
@endsection