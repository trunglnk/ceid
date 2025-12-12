<?php

namespace App\Traits;

use App\DongBoMtqgLog;
use Illuminate\Support\Facades\Auth;

trait DongBoMtqgLogger
{
    /**
     * Ghi log đồng bộ dữ liệu từ CSDL quốc gia.
     *
     * @param  string  $action         Tên hành động (VD: "Đồng bộ cơ sở sản xuất")
     * @param  string  $endpoint       Tên endpoint (VD: "T_MoiTruongCoSo")
     * @param  mixed   $data           Mảng dữ liệu đã đồng bộ
     * @param  mixed   $from           Mốc thời gian from (Carbon|null)
     * @return void
     */
    protected function logDongBo($action, $endpoint, $data, $from = null)
    {
        $cleanData = json_decode(json_encode($data), true);

        $requestData = [
            'endpoint' => $endpoint,
            'from' => $from,
            'record_count' => count($cleanData),
            'triggered_by' => Auth::user()->name ?? 'Hệ thống',
            'executed_at' => now()->format('Y-m-d H:i:s'),
        ];

        DongBoMtqgLog::log(
            $action,
            'Đã đồng bộ thành công ' . count($cleanData) . " bản ghi từ endpoint {$endpoint}",
            $requestData,
            $cleanData
        );
    }
}
