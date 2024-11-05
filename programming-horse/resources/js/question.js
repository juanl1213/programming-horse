/*
 * CEN4090C - Software Engineering Capstone
 * Programmer: Ava Adams
 * Git Repository: Programming-HORSE
 * 
 * * Description:   This file contains the source code for the Question module.
 * 
 */

export class Question {
    // Question class constructor
    constructor() {
        this.topic = "";
        this.textPrompt = "";
        this.answerChoices = [];  // Initialize an array of 4 elements for answer choices
        this.correctAnswer = "";
    }

    // Method to load a question by its ID (Simulating the PHP backend interaction)
    async loadQuestionByID(ID) {
        try {
            // Simulate an AJAX call to PHP backend to fetch the question data by ID
            const response = await fetch(`loadQuestion.php?id=${ID}`);
            const qNa = await response.json();  // Assume the response is JSON

            this.topic = qNa[0];
            this.textPrompt = qNa[1];
            this.correctAnswer = qNa[2];

            // Shuffle the answer choices
            let randomIndices = [2, 3, 4, 5]; // Based on the assumption from Java code
            randomIndices = this.shuffleArray(randomIndices);

            // Assign shuffled answers to answerChoices
            for (let i = 0; i < randomIndices.length; i++) {
                this.answerChoices[i] = qNa[randomIndices[i]];
            }
        } catch (error) {
            console.log("Error: The question could not be loaded", error);
        }
    }

    // Shuffle array function (Helper method for shuffling answers)
    shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    // Display the question and its answer choices
    displayQuestion() {
        console.log(this.textPrompt + "\n");
        for (let i = 0; i < this.answerChoices.length; i++) {
            console.log(`(${i + 1}) ${this.answerChoices[i]}`);
        }
    }

    // Getter methods
    getCorrectAnswer() {
        return this.correctAnswer;
    }

    getTopic() {
        return this.topic;
    }

    getTextPrompt() {
        return this.textPrompt;
    }

    getAnswerChoices() {
        return this.answerChoices;
    }
}
