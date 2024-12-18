<div class="form-group">
    <div class="form-group" id="attachment">
        <label for="attachment">Selecteer een video of afbeelding (max. 5MB)</label>
        <input type="file" 
               class="form-control-file @error('attachment') is-invalid @enderror" 
               id="attachment"
               name="attachment"
               accept="image/*,video/mp4">
        @error('attachment')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
