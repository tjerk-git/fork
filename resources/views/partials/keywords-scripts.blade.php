<script>
    document.getElementById('question_type_selector')?.addEventListener('change', function() {
        const openQuestionDiv = document.getElementById('open_question');
        const multipleChoiceDiv = document.getElementById('multiple_c');
        const tussenstapDiv = document.getElementById('tussenstap_div');
        const questionType = document.getElementById('question_type');

        openQuestionDiv.style.display = 'none';
        multipleChoiceDiv.style.display = 'none';
        tussenstapDiv.style.display = 'none';

        if (this.value === 'open_question') {
            openQuestionDiv.style.display = 'block';
            questionType.value = 'open_question';
        } else if (this.value === 'multiple_c') {
            multipleChoiceDiv.style.display = 'block';
            questionType.value = 'multiple_choice_question';
        }
        else if (this.value === 'tussenstap') {
            tussenstapDiv.style.display = 'block';
            questionType.value = 'tussenstap';
        }
    });

    function addKeyword() {
        const container = document.getElementById('keywords-container');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" 
                   name="keywords[]" 
                   class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                   placeholder="Sleutelwoord" />
            <button type="button" 
                    class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-red-600 hover:bg-red-700 active:bg-red-800 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                    onclick="removeKeyword(this)">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        `;
        container.appendChild(div);
    }

    function removeKeyword(button) {
        button.closest('.flex').remove();
    }
</script>
