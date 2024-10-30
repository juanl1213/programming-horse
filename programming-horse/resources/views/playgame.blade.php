<x-app-layout>

    <header class="bg-white dark:bg-gray-800 shadow">            
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="text-align: center">
                {{ __('Play Game') }}
            </h2>
        </div>
    </header>

    <div class="dark:bg-gray-800 bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-white" style="margin-top: 50px; font-family:'Urbanist';padding-bottom: 20px; border-radius: 25px; padding-top: 10px;">
        <!-- Round Counter -->
        <h1 id="round" style="font-size: 30px; margin-bottom: 10px; font-weight: 900;">ROUND 1</h1>
        
        <!-- Current Topic -->
        <p style="font-size: 20px" id="topic"></p>
        <!-- Current Question -->
        <p style="font-size: 20px" id="question"></p>
        <!-- TEST: Current Question's Answer -->
        <p style="font-size: 20px" id="correctAnswer" style="display:none;"></p>

        <!-- TODO: Set default value for loading page -->
        <!-- User Answer Selection -->
        <form id="gameForm" action="/playgame" method="GET">
            <input type="radio" id="answer_0" name="selection" value=0>
            <label for="answer_0" id="answer_0_label"></label><br>

            <input type="radio" id="answer_1" name="selection" value=1>
            <label for="answer_1" id="answer_1_label"></label><br>

            <input type="radio" id="answer_2" name="selection" value=2>
            <label for="answer_2" id="answer_2_label"></label><br>

            <input type="radio" id="answer_3" name="selection" value=3>
            <label for="answer_3" id="answer_3_label"></label><br><br>

            <input id="submitBtn" type="submit" value="Submit">
        </form>

        <br></br>
        <!-- COM Selection -->
        <p style="font-size: 20px" id="com_selection"></p>
        <!-- USER Selection -->
        <p style="font-size: 20px" id="user_selection"></p>

        <br></br>
        <!-- Current COM Points -->
        <p style="font-size: 20px" id="COM"></p>
        <!-- Current USER Points -->
        <p style="font-size: 20px" id="USER"></p>

        <!-- Winner -->
        <p style="font-size: 20px" id="winner">[no winner yet]</p>
        <button id="nextQuestionBtn" style="display: none;">Move on to next question</button>


        <!--Game Script-->
        <script>
            class Game {
    constructor() {
        this.currentRound = 1;
        this.playerNames = ["COM", "USER"];
        this.HORSE = ["H", "O", "R", "S", "E"];
        this.resetPoints();
        this.gameResponses = [];
        this.userAnswer = null;
        this.questionData = null;
        this.isRoundActive = true;
    }

    resetPoints() {
        this.playerPoints = [0, 0];
        this.playerLetters = [["_","_","_","_","_"], ["_","_","_","_","_"]];
    }

    async loadQuestion() {
        try {
            const gameId = 1;
            const topicId = 2;
            const language = 'Java';

            const response = await fetch(`/playgame/load-new-question/${gameId}/${topicId}/${language}`);
            this.questionData = await response.json();

            if (this.questionData.answers && this.questionData.answers.length === 4) {
                this.updateQuestionDisplay();
                this.isRoundActive = true;
                this.enableFormControls();
            } else {
                console.error("Error: Question data missing answers array or insufficient answers");
            }
        } catch (error) {
            console.error("Error loading question:", error);
        }
    }

    updateQuestionDisplay() {
        document.getElementById("round").innerHTML = "ROUND " + this.currentRound;
        document.getElementById("topic").innerHTML = `Topic ID: ${this.questionData.topic_id}`;
        document.getElementById("question").innerHTML = this.questionData.question;
        document.getElementById("correctAnswer").innerHTML = `TEST Correct Answer: ${this.questionData.correct_answer}`;
        
        // Update answer labels
        for (let i = 0; i < 4; i++) {
            document.getElementById(`answer_${i}_label`).innerHTML = this.questionData.answers[i];
        }
    }

    enableFormControls() {
        document.getElementById("submitBtn").disabled = false;
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => radio.disabled = false);
    }

    disableFormControls() {
        document.getElementById("submitBtn").disabled = true;
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => radio.disabled = true);
    }

    async playRound() {
        if (!this.isRoundActive) return;
        
        let round = this.getRoundResponses();
        this.determineRound(round);
        this.gameResponses.push(round);
        this.updateHTML();
        
        this.isRoundActive = false;
        this.disableFormControls();
        
        // Show next question button only after round is complete
        document.getElementById("nextQuestionBtn").style.display = "block";
        
        // Check for winner
        if (this.checkWinCondition() !== -1) {
            this.handleGameEnd();
        }
    }

    handleGameEnd() {
        const winner = this.checkWinCondition();
        document.getElementById("winner").innerHTML = this.getPlayerName(winner) + " spelled HORSE!";
        document.getElementById("nextQuestionBtn").style.display = "none";
        // You might want to add a "Play Again" button here
    }

    getRoundResponses() {
        const comAnswer = Math.floor(Math.random() * 4);  // 0-3 instead of 1-3
        const userAnswerElement = document.querySelector('input[name="selection"]:checked');
        
        const userAnswerValue = userAnswerElement ? parseInt(userAnswerElement.value, 10) : null;
        
        const userAnswerText = userAnswerElement ? 
            document.querySelector(`label[for="${userAnswerElement.id}"]`).innerText : "";
        const comAnswerText = document.querySelector(`label[for="answer_${comAnswer}"]`).innerText;

        const correctAnswerLabel = Array.from(document.querySelectorAll("label"))
            .find(label => label.innerText.trim() === this.questionData.correct_answer.trim());
        const correctAnswer = correctAnswerLabel ? 
            parseInt(correctAnswerLabel.getAttribute("for").split("_")[1]) : null;

        return [
            { value: comAnswer, text: comAnswerText}, 
            { value: userAnswerValue, text: userAnswerText }, 
            correctAnswer
        ];
    }

    updateHTML() {
        const latestRound = this.gameResponses[this.gameResponses.length - 1];
        document.getElementById("com_selection").innerHTML = "COM selected: " + latestRound[0].text;
        document.getElementById("user_selection").innerHTML = "USER selected: " + latestRound[1].text;

        document.getElementById("COM").innerHTML = `${this.getPlayerName(0)}: ${this.getPlayerPoints(0)} ${this.getPlayerLetters(0)}`;
        document.getElementById("USER").innerHTML = `${this.getPlayerName(1)}: ${this.getPlayerPoints(1)} ${this.getPlayerLetters(1)}`;
    }

    determineRound(round) {
        const comAnswer = round[0].value;
        const userAnswer = round[1].value;
        const correctAnswer = round[2];

        if (comAnswer !== userAnswer) {
            if (comAnswer === correctAnswer) {
                this.updatePoints(0);
            } else if (userAnswer === correctAnswer) {
                this.updatePoints(1);
            }
        }
    }

    updatePoints(player) {
        const points = this.playerPoints[player];
        if (points > -1 && points < 5) {
            this.playerPoints[player]++;
            this.playerLetters[player][points] = this.HORSE[points];
        }
    }

    loadNextQuestion() {
        this.currentRound++;
        this.resetRound();
        this.loadQuestion();
    }

    resetRound() {
        // Reset the form using the form's reset() method
        document.getElementById('gameForm').reset();
        
        // Explicitly uncheck all radio buttons
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.checked = false;
        });

        // Clear the selections display
        document.getElementById("com_selection").innerHTML = "";
        document.getElementById("user_selection").innerHTML = "";
        
        // Hide the next question button
        document.getElementById("nextQuestionBtn").style.display = "none";
        
        // Reset round state
        this.isRoundActive = true;
        this.userAnswer = null;
    }

    // Getter methods remain the same
    getRoundNum() { return this.currentRound; }
    getPlayerName(player) { return this.playerNames[player]; }
    getPlayerPoints(player) { return this.playerPoints[player]; }
    getPlayerLetters(player) {
        return this.playerLetters[player].join("");
    }
    checkWinCondition() {
        for (let i = 0; i < this.playerPoints.length; i++) {
            if (this.getPlayerPoints(i) >= 5) return i;
        }
        return -1;
    }
}

// Initialize game and set up event listeners
let game;
document.addEventListener('DOMContentLoaded', () => {
    game = new Game();
    game.loadQuestion();

    document.getElementById('gameForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        const selectedAnswer = document.querySelector('input[name="selection"]:checked');
        if (selectedAnswer) {
            game.userAnswer = parseInt(selectedAnswer.value);
            await game.playRound();
        } else {
            alert('Please select an answer before submitting.');
        }
    });

    document.getElementById("nextQuestionBtn").addEventListener("click", () => {
        game.loadNextQuestion();
    });
});
            
        </script>

    </div>
</div>

</div>
</x-app-layout>