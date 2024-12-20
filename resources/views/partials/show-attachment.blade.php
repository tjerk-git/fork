<div class="attachment_container">
    @if(isset($step) && $step->attachment)
        @php
            $fileExtension = strtolower(pathinfo($step->attachment, PATHINFO_EXTENSION));
            $isVideo = $fileExtension === 'mp4';
        @endphp
        
        @if ($isVideo)
            <video controls style="height: 300px;">
                <source src="{{ url('public/images/'.$step->attachment) }}" type="video/{{ $fileExtension }}">
                Your browser does not support the video tag.
            </video>
        @else
            <img src="{{ url('public/images/'.$step->attachment) }}" 
                style="height: 300px; width: 550px; object-fit: cover;" 
                alt="{{ $step->description ?? '' }}">
        @endif
    @endif
</div>
