@extends('layouts.form')

@section ('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">
    Kết quả quan trắc môi trường
</h2>
{{-- <ketquaqtmt :data='@json($data)' madinhdanh="{{$id}}"></ketquaqtmt> --}}
<ketquaqtmt :data='{{$data}}' madinhdanh="{{$id}}"></ketquaqtmt>

@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/checkbox.js') }}"></script>
@endsection