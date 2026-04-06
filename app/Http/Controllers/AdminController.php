<?php

namespace App\Http\Controllers;

use App\Models\ScanResult;
use App\Models\TbModel;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalScans' => ScanResult::count(),
            'tbDetected' => ScanResult::where('result', 'TB Detected')->count(),
            'activeModel' => TbModel::where('is_active', true)->first(),
        ]);
    }

    public function models()
    {
        return view('admin.models', ['models' => TbModel::with('uploader')->latest()->get()]);
    }

    public function uploadModel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'version' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'model_file' => 'required|file|max:512000',
        ]);

        $allowed = ['h5', 'pkl', 'pt', 'pth', 'keras'];
        $ext = strtolower($request->file('model_file')->getClientOriginalExtension());
        if (!in_array($ext, $allowed)) {
            return back()->withErrors(['model_file' => 'File must be one of: h5, pkl, pt, pth, keras'])->withInput();
        }

        $filename = $request->file('model_file')->store('models', 'local');

        TbModel::create([
            'name' => $request->name,
            'filename' => $filename,
            'version' => $request->version,
            'description' => $request->description,
            'is_active' => false,
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Model uploaded successfully.');
    }

    public function activateModel(TbModel $model)
    {
        TbModel::where('is_active', true)->update(['is_active' => false]);
        $model->update(['is_active' => true]);

        // Copy active model with .h5 extension for ML server
        $src = storage_path('app/private/' . $model->filename);
        $dest = storage_path('app/private/models/active_model.h5');
        if (file_exists($src)) {
            copy($src, $dest);
        }

        return back()->with('success', "Model '{$model->name}' is now active.");
    }

    public function deleteModel(TbModel $model)
    {
        \Storage::disk('local')->delete($model->filename);
        $model->delete();
        return back()->with('success', 'Model deleted.');
    }

    public function users()
    {
        return view('admin.users', ['users' => User::where('role', 'user')->withCount('scanResults')->latest()->get()]);
    }

    public function userScans(User $user)
    {
        return view('admin.user-scans', [
            'user' => $user,
            'scans' => $user->scanResults()->with('tbModel')->latest()->paginate(10),
        ]);
    }

    public function allScans()
    {
        return view('admin.scans', [
            'scans' => ScanResult::with(['user', 'tbModel'])->latest()->paginate(15),
        ]);
    }
}
