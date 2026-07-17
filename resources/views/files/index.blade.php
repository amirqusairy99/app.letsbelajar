@extends('layouts.app')

@section('title', 'Files — AyuhStudy')
@section('page-title', 'Files')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-semibold text-light">Files - {{ $assignment->name }}</h1>
    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-outline-secondary rounded-2">Back</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-3">
    <div class="card-body">
        <form method="POST" action="{{ route('files.upload', $assignment) }}" enctype="multipart/form-data" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-5">
                <label class="form-label text-light">Select File</label>
                <input type="file" name="file" class="form-control rounded-2 @error('file') is-invalid @enderror" required>
                @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label text-light">Folder</label>
                <select name="folder_id" class="form-select rounded-2">
                    <option value="">No Folder</option>
                    @foreach($folders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary rounded-2 w-100">Upload</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">File Name</th>
                    <th>Folder</th>
                    <th>Uploaded By</th>
                    <th>Size</th>
                    <th>Uploaded At</th>
                    <th class="text-end pe-4">Actions</th>
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
                        <td class="text-end pe-4">
                            <a href="{{ route('files.download', [$assignment, $file]) }}" class="btn btn-sm btn-outline-primary rounded-2">Download</a>
                            <button class="btn btn-sm btn-outline-secondary rounded-2" onclick="renameFile({{ $file->id }}, '{{ $file->name }}')">Rename</button>
                            <form action="{{ route('files.destroy', [$assignment, $file]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-secondary">No files uploaded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="renameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-3">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-light">Rename File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="renameForm">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <input type="text" name="name" id="renameInput" class="form-control rounded-2" required>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary rounded-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-2">Save</button>
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
