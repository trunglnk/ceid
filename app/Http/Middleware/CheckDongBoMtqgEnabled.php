<?php

namespace App\Http\Middleware;

use App\TrangThaiDongBoCsdlMtqg;
use Closure;

class CheckDongBoMtqgEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
    {
        $trangThai = TrangThaiDongBoCsdlMtqg::first();
        if (!$trangThai || !$trangThai->trang_thai) {
            return response()->json([
                'message' => 'Chức năng đồng bộ CSDL MTQG hiện đang bị tắt. Vui lòng liên hệ quản trị viên.'
            ], 403);
        }
        return $next($request);
    }
}
