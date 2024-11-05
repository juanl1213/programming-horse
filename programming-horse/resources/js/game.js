/*
 * CEN4090C - Software Engineering Capstone
 * Programmer: Ava Adams
 * Git Repository: Programming-HORSE
 * 
 * * Description:   This file contains the source code for the Game module.
 * 
 */

export class Game {
    // Game class constructor
    // Accepts a list of players as a parameter
    constructor(players) {
        this.rng = Math.random;
        this.numRounds = 0;
        this.players = players;
        this.playerAnswerSelections = Array(players.length).fill(0);
        this.currentPlayer = null;
        this.currentQuestion = null;
    }

    // Play multiple rounds until a Player wins the game
    playGame() {
        let winner = -1;

        // Reset players' points
        this.players.forEach(player => player.resetPoints());

        /*
        while (winner === -1) {
            this.playRound();   // Play a single round
            winner = this.checkWinCondition(); // Check if any Players won
        }
        */
    }

    // Play a single round
    playRound() {
        console.log(`\nRound ${this.numRounds + 1}:`);

        this.getQuestion();   // Load a question
        console.log("");      // Empty line

        // Get player responses
        this.players.forEach((player, index) => {
            let answer = 0;
            while (answer === 0) {
                answer = this.getAnswerSelection(index);
            }
            this.playerAnswerSelections[index] = answer;
        });

        // Determine the round winner
        this.determineRound();

        // Display player progress
        this.players.forEach(player => player.displayHORSE());

        // Update round counter
        this.numRounds++;
    }

    // Load in a randomized question
    getQuestion() {
        this.currentQuestion = new Question();
        const randomID = Math.floor(1 + this.rng() * 50); // Random ID for question
        this.currentQuestion.loadQuestionByID(randomID);
        this.currentQuestion.displayQuestion();
    }

    // Prompt player for answer choice
    getAnswerSelection(i) {
        const currentPlayer = this.players[i];
        let answer = prompt(`${currentPlayer.getName()}, what is your answer?`);

        // Validate answer
        answer = parseInt(answer);
        if (isNaN(answer) || answer < 1 || answer > 4) {
            console.log("Invalid answer. Try again.");
            return 0;  // Return 0 to continue prompting
        }

        return answer;
    }

    // Compare player answers and update points
    determineRound() {
        const choices = this.currentQuestion.getAnswerChoices();
        const player1Answer = this.playerAnswerSelections[0] - 1;
        const player2Answer = this.playerAnswerSelections[1] - 1;
        const correctAnswer = this.currentQuestion.getCorrectAnswer();
        const currentTopic = this.currentQuestion.getTopic();

        // Initialize topic scores if not present
        this.players.forEach(player => {
            if (!player.topicScores.hasOwnProperty(currentTopic)) {
                player.topicScores[currentTopic] = [0, 0];
            }
        });

        // Check if answers are correct
        if (choices[player1Answer] === correctAnswer) {
            this.players[0].topicScores[currentTopic][0]++;  // Increment correct answers for Player 1
        } else {
            this.players[0].topicScores[currentTopic][1]++;  // Increment wrong answers for Player 1
        }

        if (choices[player2Answer] === correctAnswer) {
            this.players[1].topicScores[currentTopic][0]++;  // Increment correct answers for Player 2
        } else {
            this.players[1].topicScores[currentTopic][1]++;  // Increment wrong answers for Player 2
        }

        // Determine round winner
        if (player1Answer !== player2Answer) {
            if (choices[player1Answer] === correctAnswer) {
                this.players[0].updatePoints();
            } else if (choices[player2Answer] === correctAnswer) {
                this.players[1].updatePoints();
            }
        }
    }

    // Check if a player has won (has 5 points)
    checkWinCondition() {
        for (let i = 0; i < this.players.length; i++) {
            if (this.players[i].getPoints() === 5) {
                console.log(`\n${this.players[i].getName()} spelled HORSE! You win!`);

                // Display stats for each player
                this.players.forEach((player, index) => {
                    console.log(`Player ${index + 1} Stats:`);
                    for (let [topic, score] of Object.entries(player.topicScores)) {
                        console.log(`Topic: ${topic} Correct: ${score[0]} Wrong: ${score[1]}`);
                    }
                });

                const playerNames = this.players.map(player => player.getName());
                // Store data in DB (to be handled by backend PHP)
                DataManager.saveResponses(playerNames, this.players[0].topicScores, this.players[1].topicScores);
                return i;
            }
        }
        return -1;
    }
}
