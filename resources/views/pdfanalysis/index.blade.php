@extends('layouts.app')

@section('title')
    <h1 class="title_master_form">Trích xuất thông tin từ file kết luận thanh tra (PDF)</h1>
@endsection

@section('content')
    <pdfanalysis></pdfanalysis>
@endsection

@section('script')
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/markdown-it/dist/markdown-it.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/build/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/styles/github.min.css"> --}}
    <script defer src="{{ asset('js/app.js') }}"></script>
@endsection
