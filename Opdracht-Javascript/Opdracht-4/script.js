/* Here all the attack methods are defined */
const waysOfAttack = [
    {
        name: "Rock",
        emoji: "👊",
        fontawesomeIcon: "fa-hand-fist",
        beats: [
            "Scissors",
            "Lizard"
        ],
    }, {
        name: "Paper",
        emoji: "📜",
        fontawesomeIcon: "fa-hand",
        beats: [
            "Rock",
            "Spock",
        ],
    }, {
        name: "Scissors",
        emoji: "✂",
        fontawesomeIcon: "fa-hand-scissors",
        beats: [
            "Paper",
            "Lizard",
        ],
    }, {
        name: "Lizard",
        emoji: "🦎",
        fontawesomeIcon: "fa-hand-lizard",
        beats: [
            "Paper",
            "Spock",
        ],
    }, {
        name: "Spock",
        emoji: "🖖",
        fontawesomeIcon: "fa-hand-spock",
        beats: [
            "Rock",
            "Scissors",
        ],
    }
]

/* All DOM elements and other variables that are needed */
var attackButtonsDiv = document.getElementById("attackButtons");
var playerIcon = document.getElementById("playerIcon");
var cpuIcon = document.getElementById("cpuIcon");
var resultsModalText = document.getElementById("modal-header");
var modal = document.getElementById("modal");
var restartButton = document.getElementById("restartButton");
var playerAttack = null;
var cpuAttack = null;
var attackButtons = null;

/* Shows all attack methods */
waysOfAttack.forEach(function (attack) {
    const div = document.createElement('div');

    div.className = 'control';
    div.innerHTML = `<button id="attackButton" onclick="attack('${attack.name}')" class="button is-link is-light">${attack.emoji} ${attack.name}</button>`;

    attackButtonsDiv.appendChild(div);
});

/* Must be done after adding the buttons to the DOM */
attackButtons = document.querySelectorAll("#attackButton");

/* Starts the fight animation */
function beginFightAnimation() {
    /* Changes the players icons to fists and starts a animation */
    playerIcon.classList.remove("fa-spinner", "custom-spin")
    playerIcon.classList.add("fa-hand-fist", "custom-start-fight-player")
    playerIcon.style.rotate = "90deg";

    cpuIcon.classList.remove("fa-check")
    cpuIcon.classList.add("fa-hand-fist", "custom-start-fight-cpu")
    cpuIcon.style.rotate = "-90deg";

    /* Make sure the animation has finished before continuing */
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            resolve();
        }, 1500
        );
    });
}

/* Shows the players attack moves */
function showAttackMoves() {
    playerIcon.classList.remove("fa-hand-fist")
    playerIcon.classList.add(waysOfAttack.find(attack => attack.name === playerAttack).fontawesomeIcon)

    cpuIcon.classList.remove("fa-hand-fist")
    cpuIcon.classList.add(cpuAttack)
}

/* Checks who the winner is and displays a modal accordingly */
function checkWinner() {
    const playerAttackData = waysOfAttack.find(attack => attack.name === playerAttack);
    const cpuAttackData = waysOfAttack.find(attack => attack.fontawesomeIcon === cpuAttack);

    if (playerAttackData.beats.includes(cpuAttackData.name) && !cpuAttackData.beats.includes(playerAttackData.name)) {
        resultsModalText.innerHTML = "You won!";
        modal.style = "display: block";
    } else if (cpuAttackData.beats.includes(playerAttackData.name) && !playerAttackData.beats.includes(cpuAttackData.name)) {
        resultsModalText.innerHTML = "You lost!";
        modal.style = "display: block";
    } else {
        resultsModalText.innerHTML = "It's a tie!";
        modal.style = "display: block";
    }
}

/* Begins the fight */
async function attack(attack) {
    /* Lock in the players choises */
    playerAttack = attack;
    cpuAttack = waysOfAttack[Math.floor(Math.random() * waysOfAttack.length)].fontawesomeIcon;

    /* Disable all controls */
    attackButtons.forEach(function (button) {
        button.disabled = true;
    });

    await beginFightAnimation();
    showAttackMoves();
    checkWinner();
}

/* Resets the game */
function restart() {
    /* Hides the modal and restart button */
    modal.style = "display: none";
    restartButton.style = "display: none";

    /* Clear player selections */
    playerAttack = null;
    cpuAttack = null;

    /* Enable all controls */
    attackButtons.forEach(function (button) {
        button.disabled = false;
    });

    /* Reset the player icons */
    playerIcon.classList.remove("fa-hand-fist", "custom-start-fight-player")
    playerIcon.classList.add("fa-spinner", "custom-spin")
    playerIcon.style.rotate = "0deg";

    cpuIcon.classList.remove("fa-hand-fist", "custom-start-fight-cpu")
    cpuIcon.classList.add("fa-check")
    cpuIcon.style.rotate = "0deg";
}

/* Closes the modal */
function closeModal() {
    modal.style = "display: none";
    restartButton.style = "display: block";
}