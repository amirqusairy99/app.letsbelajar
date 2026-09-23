@extends('layouts.app')

@section('title', 'Files — LetsBelajar')
@section('page-title', 'Files')

@section('content')
<div class="flex justify-between items-center mb-4">
 <text-4xl font-extrabold tracking-tight lg:text-5xl class="text-2xl font-semibold tracking-tight font-semibold text-light">Files - {{ $assignment->name }}</text-4xl font-extrabold tracking-tight lg:text-5xl>
 <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2">Back</a>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3 mb-3">
 <div class="p-6">
 <form method="POST" action="{{ route('files.upload', $assignment) }}" enctype="multipart/form-data" class="row g-2 items-end">
 @csrf
 <div class="col-12 col-md-5">
 <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Select File</label>
 <input type="file" name="file" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('file') is-invalid @enderror" required>
 @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12 col-md-4">
 <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Folder</label>
 <select name="folder_id" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2">
 <option value="">No Folder</option>
 @foreach($folders as $folder)
 <option value="{{ $folder->id }}">{{ $folder->name }}</option>
 @endforeach
 </select>
 </div>
 <div class="col-12 col-md-3">
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2 w-full">Upload</button>
 </div>
 </form>
 </div>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3">
 <div class="w-full overflow-auto">
 <w-full caption-bottom text-sm class="w-full caption-bottom text-sm w-full caption-bottom text-sm-hover mb-0 align-middle">
 <thead class="bg-light">
 <tr>
 <th class="ps-4">File Name</th>
 <th>Folder</th>
 <th>Uploaded By</th>
 <th>Size</th>
 <th>Uploaded At</th>
 <th class="text-right pe-4">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($files as $file)
 <tr>
 <td class="ps-4">{{ $file->name }}</td>
 <td>{{ $file->folder->name ?? '-' }}</td>
 <td>{{ $file->uploadedBy->name }}</td>
 <td>{{ $file->size ? round($file->size / 1024, 1) . ' KB' : '-' }}</td>
 <td>{{ $file->created_at->format('M j, Y') }}</td>
 <td class="text-right pe-4">
 @if($file->isPdf())
 <a href="{{ route('files.preview', [$assignment, $file]) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary rounded-2">Preview</a>
 @endif
 <a href="{{ route('files.download', [$assignment, $file]) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary rounded-2">Download</a>
 <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary rounded-2" onclick="renameFile({{ $file->id }}, '{{ $file->name }}')">Rename</button>
 <form action="{{ route('files.destroy', [$assignment, $file]) }}" method="POST" class="d-inline">
 @csrf
 @method('DELETE')
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-danger rounded-2">Delete</button>
 </form>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="6" class="text-center py-4 text-secondary">No files uploaded.</td>
 </tr>
 @endforelse
 </tbody>
 </w-full caption-bottom text-sm>
 </div>
</div>

<div class="modal fade" id="renameModal" tabindex="-1">
 <div class="modal-dialog">
 <div class="modal-content border-0 rounded-3">
 <div class="modal-header border-secondary">
 <text-lg font-semibold tracking-tight class="modal-title text-light">Rename File</text-lg font-semibold tracking-tight>
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-close" data-bs-dismiss="modal"></button>
 </div>
 <form method="POST" id="renameForm">
 @csrf
 @method('PATCH')
 <div class="modal-body">
 <input type="text" name="name" id="renameInput" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2" required>
 </div>
 <div class="modal-footer border-secondary">
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2" data-bs-dismiss="modal">Cancel</button>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2">Save</button>
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
