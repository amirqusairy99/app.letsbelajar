<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\File;
use App\Models\Folder;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $assignment->load('members.user');
        
        $folders = Folder::where('assignment_id', $assignment->id)->get();
        $files = File::where('assignment_id', $assignment->id)
            ->with('folder', 'uploadedBy')
            ->latest()
            ->get();

        return view('files.index', compact('assignment', 'folders', 'files'));
    }

    public function upload(UploadFileRequest $request, Assignment $assignment, NotificationService $notifications)
    {
        $this->authorize('upload', $assignment);

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
            'uploaded_by' => auth()->id(),
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'file_uploaded',
            'description' => "File '{$file->name}' was uploaded.",
            'subject_type' => 'file',
            'subject_id' => $file->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $notifications->notifyAssignmentMembers(
            $assignment,
            'file_uploaded',
            [
                'title' => 'New file uploaded',
                'message' => "{$file->name} was uploaded to {$assignment->name}.",
                'assignment_id' => $assignment->id,
                'file_id' => $file->id,
            ],
            auth()->user()
        );

        return back()->with('success', 'File uploaded successfully.');
    }

    public function download(Assignment $assignment, File $file)
    {
        $this->authorize('view', $file);

        [$disk] = $this->getStorageDiskAndPath($file);
        if (! $disk) {
            abort(404, 'File not found in storage.');
        }

        return Storage::disk($disk)->download($file->path, $file->name, [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function preview(Assignment $assignment, File $file)
    {
        $this->authorize('view', $file);

        if (! $file->isPdf()) {
            return back()->with('error', 'Only PDF files can be previewed.');
        }

        [$disk] = $this->getStorageDiskAndPath($file);
        if (! $disk) {
            return back()->with('error', 'File not found in storage.');
        }

        return view('files.preview', compact('assignment', 'file'));
    }

    public function content(Assignment $assignment, File $file)
    {
        $this->authorize('view', $file);

        if (! $file->isPdf()) {
            abort(400, 'Only PDF files can be previewed.');
        }

        [$disk, $fullPath] = $this->getStorageDiskAndPath($file);
        if (! $disk || ! $fullPath) {
            abort(404, 'File not found.');
        }

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.addslashes($file->name).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function update(Request $request, Assignment $assignment, File $file)
    {
        $this->authorize('view', $file);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $file->update(['name' => $request->name]);

        return back()->with('success', 'File renamed successfully.');
    }

    public function destroy(Request $request, Assignment $assignment, File $file)
    {
        $this->authorize('delete', $file);
        
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        if (Storage::disk('local')->exists($file->path)) {
            Storage::disk('local')->delete($file->path);
        }
        $file->delete();

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'file_deleted',
            'description' => "File '{$file->name}' was deleted.",
            'subject_type' => 'file',
            'subject_id' => $file->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'File deleted successfully.');
    }

    public function createFolder(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Folder::create([
            'assignment_id' => $assignment->id,
            'name' => $request->name,
            'path' => "assignments/{$assignment->id}",
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Folder created successfully.');
    }

    private function getStorageDiskAndPath(File $file): array
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
