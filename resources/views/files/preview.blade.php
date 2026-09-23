@extends('layouts.app')

@section('title', 'Preview: ' . $file->name . ' — LetsBelajar')
@section('page-title', 'PDF Preview')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-4 gap-2">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <span class="badge bg-danger rounded-1 uppercase font-semibold" style="font-size: 0.75rem;">PDF</span>
 <text-4xl font-extrabold tracking-tight lg:text-5xl class="text-2xl font-semibold tracking-tight font-bold mb-0 text-light" style="letter-spacing: -0.5px;">{{ $file->name }}</text-4xl font-extrabold tracking-tight lg:text-5xl>
 </div>
 <p class="mb-0 text-sm text-secondary">
 Uploaded by <strong>{{ $file->uploadedBy->name }}</strong> on {{ $file->created_at->format('M j, Y \a\t g:i A') }} ({{ $file->size ? round($file->size / 1024, 1) . ' KB' : 'Unknown size' }})
 </p>
 </div>
 <div class="flex items-center gap-2">
 <a href="{{ route('files.index', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2">
 <i data-lucide="arrow-left" class="w-4 h-4 me-1" style="vertical-align: -2px;"></i>Back to Files
 </a>
 </div>
</div>

{{-- Viewer Toolbar --}}
<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3 mb-3 bg-dark" style="border: 1px solid rgba(255, 255, 255, 0.1) !important;">
 <div class="p-6 py-2 px-3">
 <div class="flex flex-wrap justify-between items-center gap-6">
 {{-- Page Navigation Controls --}}
 <div class="flex items-center gap-2">
 <button type="button" id="prevPage" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary rounded-2" disabled>
 <i data-lucide="chevron-left" class="w-4 h-4" style="vertical-align: -2px;"></i>
 </button>
 <div class="flex items-center gap-1 text-light text-sm font-medium">
 <span>Page</span>
 <input type="number" id="pageNumberInput" value="1" min="1" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50-sm text-center bg-secondary text-light border-0 rounded-2" style="width: 55px;" />
 <span>of <span id="pageCountSpan">-</span></span>
 </div>
 <button type="button" id="nextPage" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary rounded-2" disabled>
 <i data-lucide="chevron-right" class="w-4 h-4" style="vertical-align: -2px;"></i>
 </button>
 </div>

 {{-- Zoom & Layout Controls --}}
 <div class="flex items-center gap-2">
 <button type="button" id="zoomOut" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary rounded-2" title="Zoom Out">
 <i data-lucide="zoom-out" class="w-4 h-4" style="vertical-align: -2px;"></i>
 </button>
 <span id="zoomLabel" class="text-light text-sm font-medium px-2" style="min-width: 50px; text-align: center;">100%</span>
 <button type="button" id="zoomIn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary rounded-2" title="Zoom In">
 <i data-lucide="zoom-in" class="w-4 h-4" style="vertical-align: -2px;"></i>
 </button>
 <button type="button" id="fitWidth" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary rounded-2" title="Fit Width">
 <i data-lucide="maximize-2" class="w-4 h-4 me-1" style="vertical-align: -2px;"></i>Fit Width
 </button>
 </div>
 </div>
 </div>
</div>

{{-- Document Canvas Container --}}
<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3 bg-dark overflow-hidden" style="border: 1px solid rgba(255, 255, 255, 0.1) !important; min-height: 600px;">
 <div id="pdfViewerContainer" class="position-relative flex justify-center items-center p-3 p-md-4 overflow-auto" style="min-height: 600px; background-color: #1a1d21;">
 
 {{-- Loading Spinner --}}
 <div id="pdfLoadingSpinner" class="text-center py-5">
 <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
 <span class="visually-hidden">Loading PDF...</span>
 </div>
 <p class="text-secondary text-sm mb-0">Loading document preview...</p>
 </div>

 {{-- Error Container --}}
 <div id="pdfErrorContainer" class="text-center py-5 d-none">
 <i data-lucide="alert-triangle" class="w-12 h-12 text-amber-600 dark:text-amber-400 mb-3"></i>
 <text-lg font-semibold tracking-tight class="text-light mb-2">Unable to render PDF preview</text-lg font-semibold tracking-tight>
 <p id="pdfErrorMessage" class="text-secondary text-sm mb-3">An error occurred while loading the document.</p>
 <a href="{{ route('files.download', [$assignment, $file]) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary rounded-2">
 Download PDF to View
 </a>
 </div>

 {{-- Render Canvas --}}
 <canvas id="pdfCanvas" class="shadow-lg rounded-1 d-none" style="max-width: 100%; height: auto;"></canvas>
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
 const container mx-auto px-4 md:px-8 = document.getElementById('pdfViewerContainer');
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
 canvas.classList.remove('d-none');
 loadingSpinner.classList.add('d-none');

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
 loadingSpinner.classList.add('d-none');
 canvas.classList.add('d-none');
 errorContainer.classList.remove('d-none');
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
 const containerWidth = container mx-auto px-4 md:px-8.clientWidth - 48;
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
