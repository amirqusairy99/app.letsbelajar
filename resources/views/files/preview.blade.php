@extends('layouts.app')

@section('title', 'Preview: ' . $file->name . ' — LetsBelajar')
@section('page-title', 'PDF Preview')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-6 gap-2">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-xs font-semibold bg-destructive/10 text-destructive uppercase">PDF</span>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">{{ $file->name }}</h2>
        </div>
        <p class="text-sm text-muted-foreground">
            Uploaded by <strong class="text-foreground">{{ $file->uploadedBy->name }}</strong> on {{ $file->created_at->format('M j, Y \a\t g:i A') }} ({{ $file->size ? round($file->size / 1024, 1) . ' KB' : 'Unknown size' }})
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('files.index', $assignment) }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">
            <i data-lucide="arrow-left" class="w-4 h-4 me-2"></i>Back to Files
        </a>
    </div>
</div>

{{-- Viewer Toolbar --}}
<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm mb-4">
    <div class="p-3">
        <div class="flex flex-wrap justify-between items-center gap-4">
            {{-- Page Navigation Controls --}}
            <div class="flex items-center gap-2">
                <button type="button" id="prevPage" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:pointer-events-none" disabled>
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <div class="flex items-center gap-2 text-sm font-medium text-foreground mx-1">
                    <span>Page</span>
                    <input type="number" id="pageNumberInput" value="1" min="1" class="flex h-9 w-16 rounded-md border border-input bg-background px-2 py-1 text-sm shadow-sm transition-colors text-center focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary" />
                    <span>of <span id="pageCountSpan">-</span></span>
                </div>
                <button type="button" id="nextPage" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:pointer-events-none" disabled>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Zoom & Layout Controls --}}
            <div class="flex items-center gap-2">
                <button type="button" id="zoomOut" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground" title="Zoom Out">
                    <i data-lucide="zoom-out" class="w-4 h-4"></i>
                </button>
                <span id="zoomLabel" class="text-sm font-medium text-foreground w-12 text-center">100%</span>
                <button type="button" id="zoomIn" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground" title="Zoom In">
                    <i data-lucide="zoom-in" class="w-4 h-4"></i>
                </button>
                <div class="w-px h-6 bg-border mx-1"></div>
                <button type="button" id="fitWidth" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground" title="Fit Width">
                    <i data-lucide="maximize-2" class="w-4 h-4 me-2"></i>Fit Width
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Document Canvas Container --}}
<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden min-h-[600px] flex items-center justify-center" style="background-color: hsl(var(--background));">
    <div id="pdfViewerContainer" class="relative flex justify-center items-center p-4 w-full h-full overflow-auto" style="min-height: 600px;">
        
        {{-- Loading Spinner --}}
        <div id="pdfLoadingSpinner" class="flex flex-col items-center justify-center py-12">
            <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin mb-4"></div>
            <p class="text-sm text-muted-foreground">Loading document preview...</p>
        </div>

        {{-- Error Container --}}
        <div id="pdfErrorContainer" class="hidden flex-col items-center justify-center py-12">
            <i data-lucide="alert-triangle" class="w-12 h-12 text-amber-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-foreground mb-1">Unable to render PDF</h3>
            <p id="pdfErrorMessage" class="text-sm text-muted-foreground mb-6">An error occurred while loading the document.</p>
            <a href="{{ route('files.download', [$assignment, $file]) }}" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-6 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                <i data-lucide="download" class="w-4 h-4 me-2"></i> Download PDF Instead
            </a>
        </div>

        {{-- Render Canvas --}}
        <canvas id="pdfCanvas" class="hidden shadow-lg rounded-sm bg-white" style="max-width: 100%; height: auto;"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof pdfjsLib === 'undefined') {
            console.error('PDF.js library failed to load.');
            return;
        }

        // Configure PDF.js Worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfUrl = "{{ route('files.content', [$assignment, $file]) }}";

        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.25;

        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');
        const viewerContainer = document.getElementById('pdfViewerContainer');
        const loadingSpinner = document.getElementById('pdfLoadingSpinner');
        const errorContainer = document.getElementById('pdfErrorContainer');
        const errorMessage = document.getElementById('pdfErrorMessage');

        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const pageNumInput = document.getElementById('pageNumberInput');
        const pageCountSpan = document.getElementById('pageCountSpan');
        const zoomInBtn = document.getElementById('zoomIn');
        const zoomOutBtn = document.getElementById('zoomOut');
        const zoomLabel = document.getElementById('zoomLabel');
        const fitWidthBtn = document.getElementById('fitWidth');

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function (page) {
                const viewport = page.getViewport({ scale: scale });
                const outputScale = window.devicePixelRatio || 1;

                canvas.width = Math.floor(viewport.width * outputScale);
                canvas.height = Math.floor(viewport.height * outputScale);
                canvas.style.width = Math.floor(viewport.width) + "px";
                canvas.style.height = Math.floor(viewport.height) + "px";

                const transform = outputScale !== 1
                    ? [outputScale, 0, 0, outputScale, 0, 0]
                    : null;

                const renderContext = {
                    canvasContext: ctx,
                    transform: transform,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);

                renderTask.promise.then(function () {
                    pageRendering = false;
                    canvas.classList.remove('hidden');
                    loadingSpinner.classList.add('hidden');

                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            }).catch(function (error) {
                console.error('Error rendering page:', error);
                showError('Failed to render page ' + num);
            });

            pageNumInput.value = num;
            prevBtn.disabled = (num <= 1);
            nextBtn.disabled = (num >= pdfDoc.numPages);
            zoomLabel.textContent = Math.round(scale * 100) + '%';
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function onPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        function showError(msg) {
            loadingSpinner.classList.add('hidden');
            canvas.classList.add('hidden');
            errorContainer.classList.remove('hidden');
            errorContainer.classList.add('flex');
            errorMessage.textContent = msg;
        }

        prevBtn.addEventListener('click', onPrevPage);
        nextBtn.addEventListener('click', onNextPage);

        pageNumInput.addEventListener('change', function () {
            let val = parseInt(this.value, 10);
            if (isNaN(val) || val < 1) val = 1;
            if (val > pdfDoc.numPages) val = pdfDoc.numPages;
            pageNum = val;
            queueRenderPage(pageNum);
        });

        zoomInBtn.addEventListener('click', function () {
            if (scale >= 3.0) return;
            scale = parseFloat((scale + 0.25).toFixed(2));
            queueRenderPage(pageNum);
        });

        zoomOutBtn.addEventListener('click', function () {
            if (scale <= 0.5) return;
            scale = parseFloat((scale - 0.25).toFixed(2));
            queueRenderPage(pageNum);
        });

        fitWidthBtn.addEventListener('click', function () {
            if (!pdfDoc) return;
            pdfDoc.getPage(pageNum).then(function (page) {
                const containerWidth = viewerContainer.clientWidth - 48;
                const unscaledViewport = page.getViewport({ scale: 1.0 });
                if (unscaledViewport.width > 0) {
                    scale = parseFloat((containerWidth / unscaledViewport.width).toFixed(2));
                    queueRenderPage(pageNum);
                }
            });
        });

        // Load PDF Document
        pdfjsLib.getDocument({
            url: pdfUrl,
            withCredentials: true
        }).promise.then(function (pdfDoc_) {
            pdfDoc = pdfDoc_;
            pageCountSpan.textContent = pdfDoc.numPages;
            pageNumInput.max = pdfDoc.numPages;
            prevBtn.disabled = false;
            nextBtn.disabled = (pdfDoc.numPages <= 1);
            renderPage(pageNum);
        }).catch(function (error) {
            console.error('Error loading PDF document:', error);
            showError('Could not load PDF document. ' + (error.message || ''));
        });
    });
</script>
@endpush
@endsection
