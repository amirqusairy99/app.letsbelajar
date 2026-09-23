<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\File;
use App\Models\Folder;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $files = File::where('assignment_id', $assignment->id)
            ->with('uploadedBy')
            ->latest()
            ->get()
            ->map(fn ($f) => $this->filePayload($f));

        return response()->json(['data' => $files]);
    }

    public function folders(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $folders = Folder::where('assignment_id', $assignment->id)->get()->map(fn ($f) => [
            'id' => $f->id,
            'assignment_id' => $f->assignment_id,
            'name' => $f->name,
            'path' => $f->path,
            'created_by' => $f->created_by,
            'created_at' => $f->created_at,
        ]);

        return response()->json(['data' => $folders]);
    }

    public function upload(Request $request, Assignment $assignment, NotificationService $notifications)
    {
        $this->authorize('upload', $assignment);

        $request->validate([
            'file' => ['required', 'file', 'max:102400'],
            'folder_id' => ['nullable', 'exists:folders,id'],
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store("assignments/{$assignment->id}", 'public');

        $name = preg_replace('/[\/\\\\]/', '', $uploaded->getClientOriginalName());
        $name = mb_substr($name, 0, 255);

        $file = File::create([
            'assignment_id' => $assignment->id,
            'folder_id' => $request->folder_id,
            'name' => $name,
            'path' => $path,
            'mime_type' => Storage::disk('public')->mimeType($path) ?: $uploaded->getClientMimeType(),
            'size' => $uploaded->getSize(),
            'uploaded_by' => Auth::id(),
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'file_uploaded',
            'description' => "File '{$file->name}' was uploaded.",
            'subject_type' => 'file',
            'subject_id' => $file->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $notifications->notifyAssignmentMembers($assignment, 'file_uploaded', [
            'title' => 'New file uploaded',
            'message' => "{$file->name} was uploaded to {$assignment->name}.",
            'assignment_id' => $assignment->id,
            'file_id' => $file->id,
        ], Auth::user());

        return response()->json(['data' => $this->filePayload($file->load('uploadedBy'))], 201);
    }

    public function download(Request $request, Assignment $assignment, File $file)
    {
        $this->authorize('view', $file);

        [$disk] = $this->getStorageDisk($file);
        if (! $disk) {
            return response()->json(['message' => 'File not found in storage.'], 404);
        }

        return Storage::disk($disk)->download($file->path, $file->name, [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function content(Request $request, Assignment $assignment, File $file)
    {
        $this->authorize('view', $file);

        [$disk, $fullPath] = $this->getStorageDisk($file);
        if (! $disk || ! $fullPath) {
            return response()->json(['message' => 'File not found in storage.'], 404);
        }

        return response()->file($fullPath, [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . addslashes($file->name) . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function destroy(Request $request, Assignment $assignment, File $file)
    {
        $this->authorize('delete', $file);

        foreach (['public', 'local'] as $disk) {
            if (Storage::disk($disk)->exists($file->path)) {
                Storage::disk($disk)->delete($file->path);
            }
        }

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'file_deleted',
            'description' => "File '{$file->name}' was deleted.",
            'subject_type' => 'file',
            'subject_id' => $file->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $file->delete();
        return response()->json(['message' => 'File deleted successfully.']);
    }

    public function createFolder(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $request->validate(['name' => ['required', 'string', 'max:255']]);

        $folder = Folder::create([
            'assignment_id' => $assignment->id,
            'name' => $request->name,
            'path' => "assignments/{$assignment->id}",
            'created_by' => Auth::id(),
        ]);

        return response()->json(['data' => $folder], 201);
    }

    protected function filePayload(File $file): array
    {
        return [
            'id' => $file->id,
            'assignment_id' => $file->assignment_id,
            'folder_id' => $file->folder_id,
            'name' => $file->name,
            'path' => $file->path,
            'mime_type' => $file->mime_type,
            'size' => $file->size,
            'uploaded_by' => $file->uploaded_by,
            'created_at' => $file->created_at,
            'is_pdf' => $file->isPdf(),
            'uploaded_user' => $file->uploadedBy ? [
                'id' => $file->uploadedBy->id,
                'name' => $file->uploadedBy->name,
                'email' => $file->uploadedBy->email,
            ] : null,
        ];
    }

    protected function getStorageDisk(File $file): array
    {
        if (Storage::disk('public')->exists($file->path)) {
            return ['public', Storage::disk('public')->path($file->path)];
        }
        if (Storage::disk('local')->exists($file->path)) {
            return ['local', Storage::disk('local')->path($file->path)];
        }
        return [null, null];
    }
}
