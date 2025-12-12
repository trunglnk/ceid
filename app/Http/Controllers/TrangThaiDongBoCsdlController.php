<?php

namespace App\Http\Controllers;

use App\DongBoMtqgLog;
use App\TrangThaiDongBoCsdlMtqg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TrangThaiDongBoCsdlController extends Controller
{
    public function viewIndex()
    {
        $data = TrangThaiDongBoCsdlMtqg::findOrFail(1);
        return view('dongbocsdl.dongbocsdl', [
            'data' => json_encode($data),
        ]);
    }

    public function getTrangThaiDongBoCsdl(Request $request)
    {
        $query = TrangThaiDongBoCsdlMtqg::select(
            'id',
            'trang_thai',
        );

        $page = $request->get('page', 1);
        $perPage = $request->get('perPage', 10);

        $query->orderBy('updated_at', 'DESC')->orderBy('id', 'DESC');

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return $data;
    }

    public function updateTrangThaiDongBoCsdl(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'trang_thai' => 'required|boolean', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = TrangThaiDongBoCsdlMtqg::findOrFail($id);

        $item->trang_thai = $request->input('trang_thai');

        $item->save();

        // 🧩 Ghi log
        $action = $item->trang_thai ? 'Bật đồng bộ' : 'Tắt đồng bộ';
        $desc = "Người dùng " . (Auth::user()->name ?? 'System') . " đã {$action} CSDL MTQG";
        DongBoMtqgLog::log($action, $desc);

        return response()->json(['message' => 'Cập nhật trạng thái đồng bộ thành công!', 'data' => $item], 200);
    }

    public function getLogDongBoCsdl(Request $request)
    {
        $logs = \App\DongBoMtqgLog::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($logs);
    }
}
