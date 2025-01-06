<div class="space-y-4 flex justify-center items-center">
    @if(isset($step) && $step->attachment)
        @php
            $fileExtension = strtolower(pathinfo($step->attachment, PATHINFO_EXTENSION));
            $isVideo = $fileExtension === 'mp4';
        @endphp
        
        <div class="relative w-full overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex justify-center items-center">
            @if ($isVideo)
                <video controls 
                       class="w-full h-[300px] rounded-lg object-contain mx-auto">
                    <source src="{{ url('public/images/'.$step->attachment) }}" type="video/{{ $fileExtension }}">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Your browser does not support the video tag.
                    </p>
                </video>
            @else
                <img src="{{ url('public/images/'.$step->attachment) }}" 
                     class="w-full h-[300px] rounded-lg object-cover"
                     alt="{{ $step->description ?? '' }}">
            @endif
        </div>
    @endif
</div>
