@extends('layouts.app')

@section('title', 'Files — LetsBelajar')
@section('page-title', 'Files')

@section('content')
<div x-data="fileManager()">
    <!-- Header & Toolbar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9" title="Back to Assignment">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-foreground">Files</h2>
                <p class="text-sm text-muted-foreground">{{ $assignment->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="folderModalOpen = true" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-colors">
                <i data-lucide="folder-plus" class="w-4 h-4 mr-2 text-blue-500"></i> New Folder
            </button>
            <button @click="uploadModalOpen = true" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 transition-colors">
                <i data-lucide="upload" class="w-4 h-4 mr-2"></i> Upload File
            </button>
        </div>
    </div>

    <!-- Main Table -->
    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-xs text-muted-foreground uppercase bg-muted/30 border-b border-border">
                    <tr>
                        <th class="px-6 py-4 font-medium">File Name</th>
                        <th class="px-6 py-4 font-medium">Folder</th>
                        <th class="px-6 py-4 font-medium">Size</th>
                        <th class="px-6 py-4 font-medium">Uploaded By</th>
                        <th class="px-6 py-4 font-medium">Date</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($files as $file)
                    <tr class="hover:bg-muted/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary">
                                    <i data-lucide="{{ $file->isPdf() ? 'file-text' : 'file' }}" class="w-4 h-4"></i>
                                </div>
                                <span class="font-medium text-foreground">{{ $file->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($file->folder)
                                <span class="inline-flex items-center rounded-full border border-border px-2.5 py-0.5 text-xs font-semibold bg-muted/50 text-muted-foreground">
                                    <i data-lucide="folder" class="w-3 h-3 mr-1"></i> {{ $file->folder->name }}
                                </span>
                            @else
                                <span class="text-muted-foreground">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-muted-foreground">
                            {{ $file->size ? round($file->size / 1024, 1) . ' KB' : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center text-xs font-medium text-secondary-foreground">
                                    {{ substr($file->uploadedBy->name, 0, 1) }}
                                </div>
                                <span class="text-muted-foreground">{{ $file->uploadedBy->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-muted-foreground">
                            {{ $file->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                @if($file->isPdf())
                                <a href="{{ route('files.preview', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Preview">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                @endif
                                <a href="{{ route('files.download', [$assignment, $file]) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Download">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                                <button @click="openRenameModal({{ $file->id }}, '{{ addslashes($file->name) }}')" class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Rename">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('files.destroy', [$assignment, $file]) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-xs font-medium text-destructive border border-destructive/20 bg-background hover:bg-destructive hover:text-destructive-foreground h-8 w-8" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-12 h-12 rounded-full bg-muted flex items-center justify-center">
                                    <i data-lucide="folder-open" class="w-6 h-6 text-muted-foreground"></i>
                                </div>
                                <div class="text-lg font-medium text-foreground">No files uploaded yet</div>
                                <p class="text-sm text-muted-foreground">Get started by uploading a file or creating a folder.</p>
                                <button @click="uploadModalOpen = true" class="mt-2 inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                                    Upload your first file
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upload Modal -->
    <div x-show="uploadModalOpen" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" x-transition.opacity @click="uploadModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-card text-card-foreground rounded-xl shadow-xl w-full max-w-md border border-border" x-transition @click.stop>
                <div class="flex items-center justify-between p-5 border-b border-border">
                    <h3 class="text-lg font-semibold flex items-center gap-2"><i data-lucide="upload" class="w-5 h-5 text-muted-foreground"></i> Upload File</h3>
                    <button @click="uploadModalOpen = false" class="text-muted-foreground hover:text-foreground rounded-full p-1 hover:bg-muted transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('files.upload', $assignment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 space-y-5">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Select File</label>
                            <input type="file" name="file" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Folder (Optional)</label>
                            <select name="folder_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring">
                                <option value="">No Folder</option>
                                @foreach($folders as $folder)
                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-5 border-t border-border bg-muted/20 rounded-b-xl">
                        <button type="button" @click="uploadModalOpen = false" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div x-show="folderModalOpen" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" x-transition.opacity @click="folderModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-card text-card-foreground rounded-xl shadow-xl w-full max-w-md border border-border" x-transition @click.stop>
                <div class="flex items-center justify-between p-5 border-b border-border">
                    <h3 class="text-lg font-semibold flex items-center gap-2"><i data-lucide="folder-plus" class="w-5 h-5 text-blue-500"></i> New Folder</h3>
                    <button @click="folderModalOpen = false" class="text-muted-foreground hover:text-foreground rounded-full p-1 hover:bg-muted transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('folders.store', $assignment) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Folder Name</label>
                            <input type="text" name="name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" placeholder="e.g. Reference Materials" required autofocus>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-5 border-t border-border bg-muted/20 rounded-b-xl">
                        <button type="button" @click="folderModalOpen = false" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                            Create Folder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div x-show="renameModalOpen" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" x-transition.opacity @click="renameModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-card text-card-foreground rounded-xl shadow-xl w-full max-w-md border border-border" x-transition @click.stop>
                <div class="flex items-center justify-between p-5 border-b border-border">
                    <h3 class="text-lg font-semibold flex items-center gap-2"><i data-lucide="edit-2" class="w-5 h-5 text-muted-foreground"></i> Rename File</h3>
                    <button @click="renameModalOpen = false" class="text-muted-foreground hover:text-foreground rounded-full p-1 hover:bg-muted transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form :action="renameFormAction" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="p-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">File Name</label>
                            <input type="text" name="name" x-model="renameCurrentName" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-5 border-t border-border bg-muted/20 rounded-b-xl">
                        <button type="button" @click="renameModalOpen = false" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                            Save Changes
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
