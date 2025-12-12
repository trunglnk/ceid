@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dist/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
<h1 class="title_master_form">Quyết định thành lập đoàn thanh tra</h1>
@endsection

@section('content')
<div class="box">
    <div class="box-header">
        <div class="row">
            <div class="col-xs-12 pull-right d-flex">
                <div>
                    @if($usersystem->role_code == 'admin' || $usersystem->role_code == 'nhanvientrungtam' || $usersystem->role_code == 'adminvaquanlydanhmuc')
                    <a href="{{route('show.doanthanhtra.add')}}" class="btn btn-flat bg-olive">
                        <i class="fa fa-plus"> Thêm mới</i>
                    </a>
                    @endif
                    <button class="btn btn-flat btn-default" id="btnXuatExcel" onclick="downloadExcel()">
                        <i class="fa fa-file-excel-o"> Xuất excel</i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <div class="col-md-3 col-lg-2">
                    @component('components.perpage',['options' => [10,30,50,100], 'default'=> $data->perPage(),'perPage' => $perPage, 'data' => $data, 'routerName' => 'doanthanhtra'])
                    @endComponent
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="dataTables_length">
                        <form class="form-horizontal" action="doanthanhtra" id="form_search_year" method="GET">
                            <input name="search_year" id="search_year" onchange="form_search_year.submit()" placeholder=" Nhập năm quyết định thành lập đoàn thanh tra" type="number" class="form-control" value="{{$search_year}}">
                        </form>
                    </div>
                </div>
                <div class="col-md-5 col-lg-7">
                    @include('thanhtra.doanthanhtra.box-search')
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-hover table-responsive">
                        <tbody>
                            <tr class="row-table-header">
                                <th>Mã định danh</th>
                                <th>Tên đoàn thanh tra</th>
                                <th>Số QĐ thành lập đoàn thanh tra</th>
                                <th>Thời gian ký QĐ thành lập đoàn thanh tra</th>
                                <th>Cơ quan ký quyết định thành lập đoàn thanh tra</th>
                                <th>Hình thức thanh tra</th>
                                @if($usersystem->role_code == 'admin' || $usersystem->role_code == 'nhanvientrungtam' || $usersystem->role_code == 'adminvaquanlydanhmuc')
                                <th>Người cập nhật</th>
                                <th style="width: 5%; text-align: center">Chỉnh sửa</th>
                                <th style="width: 5%; text-align: center">Xóa</th>
                                @else
                                <th style="width: 5%; text-align: center">Chi tiết</th>
                                @endif
                            </tr>
                            @foreach ($data as $item)
                            <tr>
                                <td> <a href="{{ URL::route('show.doanthanhtra.update', $item->id)}}"> {{$item->ma_dinh_danh}} </a> </td>
                                <td><a href="{{ URL::route('show.doanthanhtra.update', $item->id)}}"> {{$item->ten}} </a></td>
                                <td>
                                    <a href="{{ URL::route('show.doanthanhtra.update', $item->id)}}">
                                        {{$item->so_quyet_dinh}}
                                    </a>
                                </td>
                                <td>{{$item->ngay_ra_quyet_dinh}}</td>
                                <td>{{$item->coQuanQuyetDinh->ten}}</td>
                                <td>{{$item->hinhThucThanhTra->ten}}</td>
                                @if($usersystem->role_code == 'admin' || $usersystem->role_code == 'nhanvientrungtam' || $usersystem->role_code == 'adminvaquanlydanhmuc')
                                <td>{{$item->nguoiCapNhat->name}}</td>
                                <td align="center">
                                    <a href="{{ URL::route('show.doanthanhtra.update', $item->id)}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                                <td align="center">
                                    <a href="#" data-toggle="modal" data-target="{{ '#modal-delete-doanthanhtra-' . $item->id }}">
                                        <i class="fa fa-trash-o btn"></i>
                                    </a>
                                    @include('thanhtra.doanthanhtra.delete', ['data' => $item])
                                </td>
                                @else
                                <td align="center">
                                    <a href="{{ URL::route('show.doanthanhtra.update', $item->id)}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @component('components.pagination', ['pageShow' => 3, 'data' => $data])
            @endComponent
        </div>
    </div>
    <!-- /.box-body -->
</div>
@endsection

@section('script')
<script src="{{ asset('js/quyetdinhthanhtra/exportexcel.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@endsection