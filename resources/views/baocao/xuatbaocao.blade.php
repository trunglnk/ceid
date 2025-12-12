@extends('layouts.form')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
<h2 class="page-header title_master_form">Xem trước báo cáo tổng hợp</h2>
<div class="box-body">
    <report-preview ></report-preview>
</div>

@endsection

@section('script')
<script src="{{asset('js/app.js')}}"></script>
<script>
</script>
@endsection
