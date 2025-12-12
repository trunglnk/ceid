<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use DB;
use App\Attachment;

class FileController extends Controller
{
    public function download($file_id)
    {
        Auth::user();
        $file = Attachment::query()
            ->where('file_id', $file_id)->first();
        if (!empty($file)) {
            if (Storage::exists('public/files/' . $file->file_id . '-' . $file->name)) {
                try {
                    return response()->download(storage_path('app/public/files/' . $file->file_id . '-' . $file->name));
                } catch (\Exception $exception) {
                }
            }
        }
    }

    public function delete($file_id)
    {
        Attachment::query()
            ->where('file_id', $file_id)->delete();
        return response()->json([
            'message' => 'Thành công',
            'result' => [],
        ], 200, []);
    }

    function addFile(Request $request)
    {
        $info = $request->all();
        $file = $info['file'];
        if (!empty($file)) {
            $file_id = time();
            $fileName = $file_id . '-' . $file->getClientOriginalName();
            $file->storeAs('public/files/', $fileName);

            $attachment = Attachment::create([
                'name' => $file->getClientOriginalName(),
                'link' => 'app/public/files/' . $fileName,
                'file_id' => $file_id,
            ]);
        }
        return response()->json([
            'message' => 'Thành công',
            'result' => $attachment,
        ], 200, []);
    }

    function getFileByKetLuanThanhTra($id)
    {
        $attachment = Attachment::query()->where('reference_type', 'ket_luan_thanh_tra')
            ->where('reference_id', $id)->get();
        return response()->json([
            'message' => 'Thành công',
            'result' => $attachment,
        ], 200, []);
    }
}
