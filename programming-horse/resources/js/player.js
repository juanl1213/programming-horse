/*
 * CEN4090C - Software Engineering Capstone
 * Programmer: Ava Adams
 * Git Repository: Programming-HORSE
 * 
 * * Description:   This file contains the source code for the Player module.
 * 
 */


export class Player {
    // Player class constructor
    // Accepts a player name as a parameter
    constructor(playerName) {
        this.name = playerName;
        this.points = 0;
        this.currentLetters = ['_', '_', '_', '_', '_'];  // Initialize with underscores
        this.HORSE = ['H', 'O', 'R', 'S', 'E'];  // Constant array for 'HORSE'
        this.topicScores = {};  // Object to hold topic scores (similar to Java's HashMap)
    }

    // Returns the Player's name
    getName() {
        return this.name;
    }

    // Returns the number of points the Player has earned
    getPoints() {
        return this.points;
    }

    // Displays the Player's progress towards spelling HORSE based on points
    displayHORSE() {
        console.log(`${this.name}: `);

        // Update currentLetters based on points counter
        if (this.points > 0) {
            this.currentLetters[this.points - 1] = this.HORSE[this.points - 1];
        }

        // Display current letters
        console.log(this.currentLetters.join(''));  // Joins the array to print as a string
    }

    // Increments Player points
    updatePoints() {
        this.points++;
    }

    // Reset points counter and currentLetters
    resetPoints() {
        this.points = 0;
        this.currentLetters = ['_', '_', '_', '_', '_'];  // Reset currentLetters to underscores
    }

    // Set points counter
    setPoints(points) {
        this.points = points;
    }
}
