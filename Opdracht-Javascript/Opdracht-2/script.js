/* De vragenlijst met antwoorden en feedback voor de gebruiker */
const questionsList = [
    {
        "question": "Wat is de hoofdstad van Spanje?",
        "answers": "Madrid",
        "feedback": "Madrid is de hoofdstad van Spanje"
    },{
        "question": "In welke zee ligt het eiland Mallorca?",
        "answers": "Middellandse Zee",
        "feedback": "Mallorca ligt in de Middellandse Zee"
    },{
        "question": "Hoeveel paar poten hee een duizendpoot?",
        "answers": "15 Paar",
        "feedback": "Een duizendpoot heeft 15 paar poten"
    }
]

/* Alle DOM elementen en variables */
var questionsDiv = document.getElementById("questions");
var checkAnswerButton = document.getElementById("checkAnswerButton");
var iDidNotCheat = document.getElementById("iDidNotCheatBox");
var allFilled = true;
var allCorrect = true;

/* Voeg alle vragen toe aan de DOM */
questionsList.forEach(function(question) {
    const div = document.createElement('div');

    div.className = 'field';
    div.innerHTML = `
    <label class="label">${question.question}</label>
    <div class="control">
        <input id="question" class="input" type="text" placeholder="Voer hier je antwoord in.">
    </div>
    `;
  
    questionsDiv.appendChild(div);
});

/* Moet hierna gedaan worden anders zijn er geen vragen. */
var allQuestions = document.querySelectorAll('#question');

/* Zorg ervoor dat alles is ingevuld en dat de speler niet vals speelt ;) */
function toggleAnswerButton() {
    allQuestions.forEach(function(input) {
        if (input.value == '') {
            allFilled = false;
        }
    });
    
    if (!iDidNotCheat.checked) {
        allFilled = false;
    }

    if (allFilled) {
        checkAnswerButton.disabled = false;
    } else {
        checkAnswerButton.disabled = true;
    }
}

/* Check of alle antwoorden goed zijn */
function checkAnswers() {
    if (!allFilled) {
        alert("Niet alle vragen zijn beantwoord!");
        return;
    }

    allQuestions.forEach(function(input, index) {
        input.disabled = true;
        if (input.value != questionsList[index].answers) {
            allCorrect = false;
            input.classList.add('is-danger');
            input.parentElement.innerHTML += `<p class="help is-danger">${questionsList[index].feedback}</p>`;
        } else {
            input.classList.add('is-success');
        }
    });

    if (allCorrect) {
        alert('Je hebt alle vragen goed beantwoord!');
    } else {
        alert('Je hebt niet alle vragen goed beantwoord!');
    }
}