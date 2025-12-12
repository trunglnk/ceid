@extends('layouts.form')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection

@section('content')
    <report-general :data="{{$data}}"></report-general>

@endsection

@section('script')
<script src="{{asset('js/app.js')}}"></script>
<script>
  function labelFormatter(label, series) {
    return '<div style="font-size:11px; text-align:center; padding:2px; color: #fff; font-weight: 800;margin-top:7px;">'
      + label
      + '<br>'
      + Math.round(series.percent) + '%</div>'
  }
</script>
@endsection
