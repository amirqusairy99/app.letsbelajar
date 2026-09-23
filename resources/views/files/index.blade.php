@extends('layouts.app')

@section('title', 'Files — LetsBelajar')
@section('page-title', 'Files')

@section('content')
<div x-data="fileManager()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
            </a>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Files - {{ $assignment->name }}</h2>
        </div>
        <div class="flex gap-2">
            <button @click="folderModalOpen = true" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                <i data-lucide="folder-plus" class="w-4 h-4 mr-2"></i> New Folder
            </button>
            <button @click="uploadModalOpen = true" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                <i data-lucide="upload" class="w-4 h-4 mr-2"></i> Upload File
            </button>
        </div>
    </div>

    <!-- Folders & Files Layout -->
    <div class="flex flex-col gap-6">
        @foreach($folders as $folder)
        @php $folderFiles = $files->where('folder_id', $folder->id); @endphp
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="px-6 py-4 border-b border-border bg-muted/20 flex justify-between items-center">
                <h3 class="font-semibold flex items-center gap-2 text-foreground">
                    <i data-lucide="folder" class="w-5 h-5 text-blue-500"></i> {{ $folder->name }}
                </h3>
                <span class="text-xs text-muted-foreground">{{ $folderFiles->count() }} file(s)</span>
            </div>
            <div class="w-full overflow-auto">
                @if($folderFiles->isEmpty())
                <div class="px-6 py-8 text-center text-muted-foreground text-sm">
                    No files in this folder.
                </div>
                @else
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-muted-foreground uppercase bg-muted/10 border-b border-border">
                        <tr>
                            <th class="px-6 py-3 font-medium">File Name</th>
                            <th class="px-6 py-3 font-medium">Uploaded By</th>
                            <th class="px-6 py-3 font-medium">Size</th>
                            <th class="px-6 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($folderFiles as $file)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="px-6 py-3 font-medium text-foreground">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="file" class="w-4 h-4 text-muted-foreground"></i>
                                    {{ $file->name }}
                                </div>
                            </td>
                            <td class="px-6 py-3 text-muted-foreground">{{ $file->uploadedBy->name }}</td>
                            <td class="px-6 py-3 text-muted-foreground">{{ $file->size ? round($file->size / 1024, 1) . ' KB' : '-' }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($file->isPdf())
                                    <a href="{{ route('files.preview', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-7 px-2">
                                        Preview
                                    </a>
                                    @endif
                                    <a href="{{ route('files.download', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-7 px-2">
                                        Download
                                    </a>
                                    <button @click="openRenameModal({{ $file->id }}, '{{ addslashes($file->name) }}')" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-7 px-2">
                                        Rename
                                    </button>
                                    <form action="{{ route('files.destroy', [$assignment, $file]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-xs font-medium text-destructive border border-destructive/20 bg-background hover:bg-destructive hover:text-destructive-foreground h-7 px-2">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        @endforeach

        @php $unsortedFiles = $files->whereNull('folder_id'); @endphp
        @if($unsortedFiles->isNotEmpty() || $folders->isEmpty())
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="px-6 py-4 border-b border-border bg-muted/20 flex justify-between items-center">
                <h3 class="font-semibold flex items-center gap-2 text-foreground">
                    <i data-lucide="folder-open" class="w-5 h-5 text-gray-500"></i> {{ $folders->isEmpty() ? 'Files' : 'Unsorted Files' }}
                </h3>
                <span class="text-xs text-muted-foreground">{{ $unsortedFiles->count() }} file(s)</span>
            </div>
            <div class="w-full overflow-auto">
                @if($unsortedFiles->isEmpty())
                <div class="px-6 py-8 text-center text-muted-foreground text-sm">
                    No files uploaded yet.
                </div>
                @else
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-muted-foreground uppercase bg-muted/10 border-b border-border">
                        <tr>
                            <th class="px-6 py-3 font-medium">File Name</th>
                            <th class="px-6 py-3 font-medium">Uploaded By</th>
                            <th class="px-6 py-3 font-medium">Size</th>
                            <th class="px-6 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($unsortedFiles as $file)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="px-6 py-3 font-medium text-foreground">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="file" class="w-4 h-4 text-muted-foreground"></i>
                                    {{ $file->name }}
                                </div>
                            </td>
                            <td class="px-6 py-3 text-muted-foreground">{{ $file->uploadedBy->name }}</td>
                            <td class="px-6 py-3 text-muted-foreground">{{ $file->size ? round($file->size / 1024, 1) . ' KB' : '-' }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($file->isPdf())
                                    <a href="{{ route('files.preview', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-7 px-2">
                                        Preview
                                    </a>
                                    @endif
                                    <a href="{{ route('files.download', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-7 px-2">
                                        Download
                                    </a>
                                    <button @click="openRenameModal({{ $file->id }}, '{{ addslashes($file->name) }}')" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-7 px-2">
                                        Rename
                                    </button>
                                    <form action="{{ route('files.destroy', [$assignment, $file]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-xs font-medium text-destructive border border-destructive/20 bg-background hover:bg-destructive hover:text-destructive-foreground h-7 px-2">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Upload Modal -->
    <div x-show="uploadModalOpen" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="uploadModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-card text-card-foreground rounded-xl shadow-lg w-full max-w-md border border-border" @click.stop>
                <div class="flex items-center justify-between p-6 border-b border-border">
                    <h3 class="text-lg font-semibold">Upload File</h3>
                    <button @click="uploadModalOpen = false" class="text-muted-foreground hover:text-foreground">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('files.upload', $assignment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">Select File</label>
                            <input type="file" name="file" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" required>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">Folder (Optional)</label>
                            <select name="folder_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring">
                                <option value="">No Folder</option>
                                @foreach($folders as $folder)
                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-6 border-t border-border bg-muted/20 rounded-b-xl">
                        <button type="button" @click="uploadModalOpen = false" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 px-4 py-2">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div x-show="folderModalOpen" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="folderModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-card text-card-foreground rounded-xl shadow-lg w-full max-w-md border border-border" @click.stop>
                <div class="flex items-center justify-between p-6 border-b border-border">
                    <h3 class="text-lg font-semibold">New Folder</h3>
                    <button @click="folderModalOpen = false" class="text-muted-foreground hover:text-foreground">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('folders.store', $assignment) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">Folder Name</label>
                            <input type="text" name="name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" required autofocus>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-6 border-t border-border bg-muted/20 rounded-b-xl">
                        <button type="button" @click="folderModalOpen = false" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 px-4 py-2">
                            Create Folder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div x-show="renameModalOpen" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="renameModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-card text-card-foreground rounded-xl shadow-lg w-full max-w-md border border-border" @click.stop>
                <div class="flex items-center justify-between p-6 border-b border-border">
                    <h3 class="text-lg font-semibold">Rename File</h3>
                    <button @click="renameModalOpen = false" class="text-muted-foreground hover:text-foreground">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form :action="renameFormAction" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="p-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">File Name</label>
                            <input type="text" name="name" x-model="renameCurrentName" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-6 border-t border-border bg-muted/20 rounded-b-xl">
                        <button type="button" @click="renameModalOpen = false" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 px-4 py-2">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<style>
    [x-cloak] { display: none !important; }
</style>
<script>
function fileManager() {
    return {
        uploadModalOpen: false,
        folderModalOpen: false,
        renameModalOpen: false,
        renameCurrentName: '',
        renameFormAction: '',
        assignmentId: {{ $assignment->id }},
        openRenameModal(fileId, currentName) {
            this.renameCurrentName = currentName;
            this.renameFormAction = `/assignments/${this.assignmentId}/files/${fileId}`;
            this.renameModalOpen = true;
        }
    }
}
</script>
@endpush
@endsection
