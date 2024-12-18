{{-- Keywords Section --}}
<div id="keywords-section" class="mt-3">
    <label>Sleutelwoorden</label>
    <p>Wanneer een sleutelwoord gevonden wordt in een antwoord, krijgt de gebruiker een extra pop-up met daarin positieve feedback.</p>
    <p>Voeg elk woord los toe</p>
    <div id="keywords-container">
        @if(isset($step) && $step->keywords)
            @foreach($step->keywords as $keyword)
                <div class="keyword-input">
                    <input type="text" name="keywords[]" value="{{ $keyword->word }}" class="form-control" placeholder="Sleutelwoord" />
                    <button type="button" class="btn btn-danger" onclick="removeKeyword(this)">
                        <i class="fas fa-times"></i>
                        Verwijder
                    </button>
                </div>
            @endforeach
        @elseif(old('keywords'))
            @foreach(old('keywords') as $keyword)
                <div class="keyword-input">
                    <input type="text" name="keywords[]" value="{{ $keyword }}" class="form-control" placeholder="Sleutelwoord" />
                    <button type="button" class="btn btn-danger" onclick="removeKeyword(this)">
                        Verwijder<i class="fas fa-times"></i>
                    </button>
                </div>
            @endforeach
        @endif
    </div>
    <button type="button" class="btn btn-secondary mt-2" onclick="addKeyword()">
        <i class="fas fa-plus"></i>Voeg sleutelwoord toe 🔑
    </button>
</div>
