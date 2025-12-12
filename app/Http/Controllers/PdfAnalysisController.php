<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use App\Attachment;
use App\PdfAnalysisResult;

class PdfAnalysisController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth', ['except' => ['pdfAnalysis', 'checkPdfResult']]);
    // }
    public function index()
    {
        return view('pdfanalysis.index');
    }

    public function pdfAnalysis(Request $request)
    {
        set_time_limit(0);
        $request->validate([
            'file' => 'nullable|mimes:pdf|max:51200|required_without_all:id,link',
            'id'   => 'nullable|integer|required_without_all:file,link',
            'link' => 'nullable|string|required_without_all:file,id',
        ]);

        $file = $request->file('file');
        $streamPath = null;
        $filename   = null;

        if ($file) {
            // Trường hợp upload trực tiếp
            $streamPath = $file->getRealPath();
            $filename   = $file->getClientOriginalName();
        } else {
            $resolvedPath = null;

            if (!$resolvedPath && $request->input('link')) {
                $resolvedPath = $request->input('link');
            } else {
                if ($request->filled('id')) {
                    $attachment = Attachment::find((int) $request->input('id'));
                    // đổi 'path' thành tên cột thực tế trong DB nếu khác
                    $resolvedPath = $attachment->link;
                }
            }

            if ($resolvedPath) {
                if (strpos($resolvedPath, 'app/public/') === 0) {
                    $resolvedPath = substr($resolvedPath, 4);
                }
                if (strpos($resolvedPath, '/app/public/') === 0) {
                    $resolvedPath = substr($resolvedPath, 5);
                }
                $abs = storage_path('app/' . $resolvedPath);
                if (!file_exists($abs)) {
                    $alt = storage_path('app/public/files/' . basename($resolvedPath));
                    if (file_exists($alt)) {
                        $abs = $alt;
                    } else {
                        return response()->json(['message' => 'Không tìm thấy file từ attachment_id/path.'], 422);
                    }
                }
                $mime = @mime_content_type($abs) ?: '';
                if (stripos($mime, 'pdf') === false) {
                    return response()->json(['message' => 'File không phải PDF.'], 422);
                }
                if ((filesize($abs) / 1024) > 51200) {
                    return response()->json(['message' => 'File vượt quá 50MB.'], 422);
                }
                $streamPath = $abs;
                $filename   = basename($abs);
            }
        }

        if (!$streamPath) {
            return response()->json(['message' => 'Cần truyền file hoặc attachment_id hoặc path.'], 422);
        }
        $hash = null;
        if ($streamPath && file_exists($streamPath)) {
            $hash = @hash_file('sha256', $streamPath) ?: null;
        }

        $client = new Client();
        try {
            $response = $client->request('POST', config('services.pdfanalysis.url') . '/summarize', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($streamPath, 'rb'),
                        'filename' => $filename,
                    ],
                ],
                'headers' => ['Accept' => 'application/json'],
                'connect_timeout' => 30,
                'timeout' => 600,
            ]);
            $payload = json_decode((string) $response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'message' => 'Lỗi khi gọi API PDF Analysis.',
                'error'   => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi không xác định khi phân tích PDF.',
                'error'   => $e->getMessage(),
            ], 500);
        }
        if (is_array($payload) && $hash && (isset($payload['summary']) || isset($payload['content']))) {
            $model = PdfAnalysisResult::where('hash', $hash)->first();
            $attachmentId = $request->input('id') ?? null;
            if (!$model) {
                $model = new PdfAnalysisResult();
                $model->hash = $hash;
            }
            if (!empty($attachmentId)) {
                $model->attachment_id = (int) $attachmentId;
            }
            $model->summary = $payload['summary'] ?? null;
            $model->content = $payload['content'] ?? null;
            $model->json    = $payload['json'] ?? null;
            $model->save();
        }
        return response()->json($payload);
    }

    public function checkPdfResult(Request $request)
    {
        $request->validate([
            'file' => 'nullable|mimes:pdf|max:51200|required_without_all:id,link',
            'id'   => 'nullable|integer|required_without_all:file,link',
            'link' => 'nullable|string|required_without_all:file,id',
        ]);

        $hash = null;
        $resolvedPath = null;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $hash = hash_file('sha256', $uploaded->getRealPath());
        } elseif ($request->filled('link')) {
            $resolvedPath = (string) $request->input('link');
        } elseif ($request->filled('id')) {
            $attachment = Attachment::find((int) $request->input('id'));
            if (!$attachment || empty($attachment->link)) {
                return response()->json(['message' => 'Không tìm thấy attachment hoặc thiếu link.'], 422);
            }
            $resolvedPath = (string) $attachment->link;
        }

        if ($resolvedPath !== null && $hash === null) {
            $path = ltrim($resolvedPath, '/');
            if (strpos($path, 'app/public/') === 0) {
                $path = substr($path, 4);
            } elseif (strpos($path, '/app/public/') === 0) {
                $path = substr($path, 5);
            }
            $abs = storage_path('app/' . ltrim($path, '/'));
            if (!file_exists($abs)) {
                $alt = storage_path('app/public/files/' . basename($path));
                if (file_exists($alt)) {
                    $abs = $alt;
                } else {
                    return response()->json(['message' => 'Không tìm thấy file Lưu trữ trong hệ thống'], 200);
                }
            }

            $hash = @hash_file('sha256', $abs) ?: null;
        }

        if ($hash === null) {
            return response()->json(['message' => 'Không xác định được file từ file/link/id.'], 422);
        }
        $result = PdfAnalysisResult::where('hash', $hash)->first();

        return response()->json([
            'message'     => 'Thành công',
            'is_analysis' => (bool) $result,
            'result'      => $result,
        ]);
    }
}
