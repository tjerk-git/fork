document.addEventListener('DOMContentLoaded', function() {
    init();
});

function showConfetti() {
    confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 }
    });
}



function replaceName(name){
    // search all text on the page for double brackets
    const textWithBrackets = document.body.innerHTML;
    const textWithBracketsArray = textWithBrackets.match(/\[\[(.*?)\]\]/g);

    // replace [[naam]] with Tjerk 
    if (textWithBracketsArray) {
        for (let i = 0; i < textWithBracketsArray.length; i++) {
            const textWithBracketsArrayElement = textWithBracketsArray[i];
            const textWithBracketsArrayElementClean = textWithBracketsArrayElement.replace(/[\[\]]/g, '');
            const textWithBracketsArrayElementCleanReplaced = textWithBracketsArrayElementClean.replace('naam', name);

            document.body.innerHTML = document.body.innerHTML.replace(textWithBracketsArrayElement, textWithBracketsArrayElementCleanReplaced);
        }
    }
}

function init() {

    // Array of success GIFs
    const successGifs = [
        'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcDdtY2JrY2N2Ynhxc3E2NWx0Z2E1Z2RwbWR6NXF6YnB0ZTFwYjh6eCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/xT5LMHxhOfscxPfIfm/giphy.gif',
        'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExYXFxemRwZWRxbGx0Y2RqcWJ1NXdqbzNyM2Zha3BnNm8yYTdwcWJzaCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/YRuFixSNWFVcXaxpmX/giphy.gif',
        'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMzR5Y3NyYmRkNnBxbWdxaWJyMWM2NHZ6ZnJnOXd0ZDdwbXJ5MXF6dyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3ohzdIuqJoo8QdKlnW/giphy.gif',
        'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExbWVqbWRyYnVyYWFxbHd0NHhzZnlqcWRnbHRyeWFyaHBhOWZ0bHF1eiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/QJvwBSGaoc4eI/giphy.gif',
        'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcWRyeXRwbGZnZm5xdnBhbmE2aW9xbzVxbm1yYnFtNmZxdWJyOHRybiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/artj92V8o75VPL7AeQ/giphy.gif'
    ];

    // Function to get random GIF
    const getRandomGif = () => {
        return successGifs[Math.floor(Math.random() * successGifs.length)];
    };

    // Initialize Tingle modal
    const modal = new tingle.modal({
        footer: true,
        stickyFooter: false,
        closeMethods: ['overlay', 'button', 'escape'],
        closeLabel: "Close",
        cssClass: ['custom-modal']
    });

    // Add a button to the modal footer
    modal.addFooterBtn('Ga verder', 'tingle-btn tingle-btn--primary', function() {
        modal.close();
        showConfetti();
    });

    // Listen for changes on text inputs with keywords
    document.querySelectorAll('input[type="text"][data-keywords]').forEach(input => {
        input.addEventListener('change', (e) => {
            const keywords = JSON.parse(e.target.dataset.keywords);
            const enteredText = e.target.value.toLowerCase().trim();
            
            if (keywords.some(keyword => enteredText.includes(keyword.toLowerCase()))) {
                const modalContent = `
                    <div class="text-center p-6">
                        <div class="text-5xl mb-6">🎉</div>
                        <h2 class="text-green-600 text-2xl font-bold mb-4">Goed gedaan!</h2>
                        <p class="text-lg mb-4">Je hebt het juiste antwoord gegeven!</p>
                        <img src="${getRandomGif()}" alt="Success!" class="max-w-[300px] mx-auto rounded-lg shadow-md">
                    </div>
                `;
                modal.setContent(modalContent);
                modal.open();
            }
        });
    });

    // Navigation logic
    const stepDivs = document.querySelectorAll('.slide');
    const next = document.getElementById('next');
    const prev = document.getElementById('prev');
    let steps = Array.from(stepDivs).map(div => parseInt(div.dataset.slide));
    let index = 0;

    // Hide all steps except first
    stepDivs.forEach((stepDiv, i) => {
        if (i !== 0) stepDiv.style.display = "none";
    });

    // Add event listeners for navigation
    next.addEventListener('click', (event) => {
        event.preventDefault();

        const currentStep = document.querySelector(`[data-slide="${steps[index]}"]`);
        const radioInputs = currentStep.querySelectorAll('input[type="radio"]');

        const condition = currentStep.dataset.condition;
        const forkStep = parseInt(currentStep.dataset.forkStep);
        
        if (radioInputs.length > 0 && forkStep && condition) {
            const selectedOption = Array.from(radioInputs).find(input => input.checked);
            if (!selectedOption) {
                alert('Selecteer een antwoord voordat je verder gaat.');
                return;
            }

            if (selectedOption.value === condition) {
                removeNumbers([forkStep]);
            } else {
                addNumbers([forkStep]);
            }
        }

        if (index < steps.length - 1) {
            index++;
            stepDivs.forEach(stepDiv => {
                stepDiv.style.display = "none";
            });
            document.querySelector(`[data-slide="${steps[index]}"]`).style.display = "block";

            if (index === steps.length - 1) {
                next.style.display = "none";
                showConfetti();
            }
        }
    });

    prev.addEventListener('click', (event) => {
        event.preventDefault();
        next.style.display = "block";

        if (index > 0) {
            index--;
            stepDivs.forEach(stepDiv => {
                stepDiv.style.display = "none";
            });
            document.querySelector(`[data-slide="${steps[index]}"]`).style.display = "block";
        }
    });

    function removeNumbers(numbersToRemove) {
        steps = steps.filter(num => !numbersToRemove.includes(num));
        index = Math.min(index, steps.length - 1);
    }

    function addNumbers(numbersToAdd) {
        if (steps.some(num => numbersToAdd.includes(num))) {
            return;
        }
        steps.splice(index + 1, 0, ...numbersToAdd);
    }
}