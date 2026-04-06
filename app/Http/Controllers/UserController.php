<?php

namespace App\Http\Controllers;

use App\Models\ScanResult;
use App\Models\TbModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        return view('user.dashboard', [
            'recentScans' => $user->scanResults()->with('tbModel')->latest()->take(5)->get(),
            'totalScans' => $user->scanResults()->count(),
            'tbCount' => $user->scanResults()->where('result', 'TB Detected')->count(),
            'hasActiveModel' => TbModel::where('is_active', true)->exists(),
        ]);
    }

    public function scan()
    {
        $activeModel = TbModel::where('is_active', true)->first();
        return view('user.scan', compact('activeModel'));
    }

    public function submitScan(Request $request)
    {
        $request->validate([
            'xray' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $activeModel = TbModel::where('is_active', true)->firstOrFail();

        $xrayPath = $request->file('xray')->store('xrays', 'public');

        // Call Python ML API
        $response = Http::timeout(60)->attach(
            'file',
            file_get_contents($request->file('xray')->getRealPath()),
            'xray.jpg'
        )->post(config('app.ml_api_url', 'http://localhost:8002') . '/predict');

        if ($response->failed()) {
            return back()->with('error', 'Analysis failed. Please try again.');
        }

        $data = $response->json();
        $prediction = $data['prediction'] ?? 0;
        $heatmapBase64 = $data['heatmap'] ?? null;

        $heatmapPath = null;
        if ($heatmapBase64) {
            $heatmapPath = 'heatmaps/' . uniqid() . '.jpg';
            \Storage::disk('public')->put($heatmapPath, base64_decode($heatmapBase64));
        }

        $scan = ScanResult::create([
            'user_id' => auth()->id(),
            'tb_model_id' => $activeModel->id,
            'xray_image' => $xrayPath,
            'heatmap_image' => $heatmapPath,
            'result' => $prediction > 0.5 ? 'TB Detected' : 'Normal',
            'confidence' => $prediction > 0.5 ? $prediction * 100 : (1 - $prediction) * 100,
        ]);

        return redirect()->route('user.result', $scan);
    }

    public function result(ScanResult $scan)
    {
        abort_if($scan->user_id !== auth()->id(), 403);
        return view('user.result', compact('scan'));
    }

    public function history()
    {
        return view('user.history', [
            'scans' => auth()->user()->scanResults()->with('tbModel')->latest()->paginate(10),
        ]);
    }
}
