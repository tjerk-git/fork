<div class="space-y-2">
    <div class="space-y-1">
        <label for="attachment" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
            Selecteer een video of afbeelding (max. 5MB)
        </label>
        <div class="flex items-center justify-center w-full">
            <label for="attachment" 
                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-2 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-semibold">Klik om te uploaden</span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Video (MP4) of afbeelding
                    </p>
                </div>
                <input type="file" 
                       id="attachment"
                       name="attachment"
                       accept="image/*,video/mp4"
                       class="hidden"
                       onchange="handleFileSelect(event)">
            </label>
        </div>
        <div id="preview-container" class="hidden mt-4">
            <img id="image-preview" class="hidden max-h-48 rounded-lg mx-auto" alt="Preview">
            <video id="video-preview" class="hidden max-h-48 rounded-lg mx-auto" controls>
                Your browser does not support the video tag.
            </video>
        </div>
        @error('attachment')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const videoPreview = document.getElementById('video-preview');

    // Reset previews
    imagePreview.classList.add('hidden');
    videoPreview.classList.add('hidden');
    previewContainer.classList.remove('hidden');

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else if (file.type === 'video/mp4') {
        const url = URL.createObjectURL(file);
        videoPreview.src = url;
        videoPreview.classList.remove('hidden');
    }
}
</script>
