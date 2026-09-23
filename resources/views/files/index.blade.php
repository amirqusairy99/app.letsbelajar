@extends('layouts.app')

@section('title', 'Files — LetsBelajar')
@section('page-title', 'Files')

@section('content')
<div class="flex justify-between items-center mb-6">
 <h2 class="text-2xl font-bold tracking-tight text-foreground">Files - {{ $assignment->name }}</h2>
 <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
 <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
 </a>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm mb-6">
 <div class="p-6">
 <form method="POST" action="{{ route('files.upload', $assignment) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
 @csrf
 <div class="md:col-span-5 flex flex-col gap-2">
 <label class="text-sm font-medium text-foreground">Select File</label>
 <input type="file" name="file" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring @error('file') border-destructive @enderror" required>
 @error('file')<div class="text-xs text-destructive">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-4 flex flex-col gap-2">
 <label class="text-sm font-medium text-foreground">Folder</label>
 <select name="folder_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring">
 <option value="">No Folder</option>
 @foreach($folders as $folder)
 <option value="{{ $folder->id }}">{{ $folder->name }}</option>
 @endforeach
 </select>
 </div>
 <div class="md:col-span-3">
 <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 px-4 py-2 w-full">
 <i data-lucide="upload" class="w-4 h-4 mr-2"></i> Upload
 </button>
 </div>
 </form>
 </div>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
 <div class="w-full overflow-auto">
 <table class="w-full text-sm text-left">
 <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border">
 <tr>
 <th class="px-6 py-4 font-medium">File Name</th>
 <th class="px-6 py-4 font-medium">Folder</th>
 <th class="px-6 py-4 font-medium">Uploaded By</th>
 <th class="px-6 py-4 font-medium">Size</th>
 <th class="px-6 py-4 font-medium">Uploaded At</th>
 <th class="px-6 py-4 font-medium text-right">Actions</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-border">
 @forelse($files as $file)
 <tr class="hover:bg-muted/50 transition-colors">
 <td class="px-6 py-4 font-medium text-foreground">
 <div class="flex items-center gap-2">
 <i data-lucide="file" class="w-4 h-4 text-muted-foreground"></i>
 {{ $file->name }}
 </div>
 </td>
 <td class="px-6 py-4">{{ $file->folder->name ?? '-' }}</td>
 <td class="px-6 py-4">{{ $file->uploadedBy->name }}</td>
 <td class="px-6 py-4">{{ $file->size ? round($file->size / 1024, 1) . ' KB' : '-' }}</td>
 <td class="px-6 py-4">{{ $file->created_at->format('M j, Y') }}</td>
 <td class="px-6 py-4 text-right">
 <div class="flex justify-end gap-2">
 @if($file->isPdf())
 <a href="{{ route('files.preview', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 px-3">
 Preview
 </a>
 @endif
 <a href="{{ route('files.download', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 px-3">
 Download
 </a>
 <button class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 px-3" onclick="renameFile({{ $file->id }}, '{{ $file->name }}')">
 Rename
 </button>
 <form action="{{ route('files.destroy', [$assignment, $file]) }}" method="POST" class="inline-block">
 @csrf
 @method('DELETE')
 <button type="submit" class="inline-flex items-center justify-center rounded-md text-xs font-medium text-destructive border border-destructive/20 bg-background hover:bg-destructive hover:text-destructive-foreground h-8 px-3">
 Delete
 </button>
 </form>
 </div>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="6" class="px-6 py-8 text-center text-muted-foreground">
 <div class="flex flex-col items-center gap-2">
 <i data-lucide="folder-open" class="w-8 h-8 opacity-50"></i>
 <span>No files uploaded yet.</span>
 </div>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
</div>

<div class="modal fade" id="renameModal" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered">
 <div class="modal-content border border-border bg-card text-card-foreground shadow-lg rounded-xl">
 <div class="modal-header border-b border-border">
 <h5 class="modal-title font-semibold text-foreground text-lg">Rename File</h5>
 <button type="button" class="inline-flex items-center justify-center rounded-md text-sm font-medium hover:bg-accent hover:text-accent-foreground h-8 w-8" data-bs-dismiss="modal">
 <i data-lucide="x" class="w-4 h-4"></i>
 </button>
 </div>
 <form method="POST" id="renameForm">
 @csrf
 @method('PATCH')
 <div class="modal-body p-6">
 <label class="text-sm font-medium text-foreground mb-2 block">New Name</label>
 <input type="text" name="name" id="renameInput" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" required>
 </div>
 <div class="modal-footer border-t border-border p-4 flex justify-end gap-2">
 <button type="button" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2" data-bs-dismiss="modal">Cancel</button>
 <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 px-4 py-2">Save</button>
 </div>
 </form>
 </div>
 </div>
</div>

@push('scripts')
<script>
function renameFile(fileId, currentName) {
 const modal = new bootstrap.Modal(document.getElementById('renameModal'));
 const input = document.getElementById('renameInput');
 const form = document.getElementById('renameForm');
 input.value = currentName;
 form.action = `/assignments/{{ $assignment->id }}/files/${fileId}`;
 modal.show();
}
</script>
@endpush
@endsection
