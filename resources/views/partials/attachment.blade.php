<div class="space-y-6">
    @if(isset($step) && $step->attachment)
        @php
            $fileExtension = strtolower(pathinfo($step->attachment, PATHINFO_EXTENSION));
            $isVideo = $fileExtension === 'mp4';
        @endphp
        
        <div class="relative w-full rounded-lg border bg-card">
            @if ($isVideo)
                <video 
                    controls 
                    class="w-full max-h-[300px] rounded-lg object-cover"
                >
                    <source src="{{ url('public/images/'.$step->attachment) }}" type="video/{{ $fileExtension }}">
                    <p class="text-sm text-muted-foreground">Your browser does not support the video tag.</p>
                </video>
            @else
                <img 
                    src="{{ url('public/images/'.$step->attachment) }}" 
                    class="w-full h-[300px] rounded-lg object-cover"
                    alt="{{ $step->description ?? '' }}"
                >
            @endif
        </div>
    @endif

    @include('partials.show-attachment')
    @include('partials.add-attachment')
</div>

@push('scripts')
<script>
    document.getElementById('show_attachment')?.addEventListener('click', function() {
        const attachmentDiv = document.getElementById('attachment');
        this.style.display = 'none';
        attachmentDiv.style.display = 'block';
    });
</script>
@endpush