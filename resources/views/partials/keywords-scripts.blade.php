@push('scripts')
<script>
    function addKeyword() {
        const container = document.getElementById('keywords-container');
        const div = document.createElement('div');
        div.className = 'keyword-input';
        div.innerHTML = `
            <input type="text" name="keywords[]" class="form-control" placeholder="Sleutelwoord" />
            <button type="button" class="btn btn-danger" onclick="removeKeyword(this)">
                Verwijder<i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function removeKeyword(button) {
        button.parentElement.remove();
    }

</script>
@endpush
