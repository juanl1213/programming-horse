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

            <input type="submit" value="Submit">
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

        <!--Game Script-->
        <script>
            class Game {
                
                constructor() {
                    this.currentRound = 2;
                    this.playerNames = ["COM", "USER"];
                    this.HORSE = ["H", "O", "R", "S", "E"];
                    this.resetPoints();
                    this.gameResponses = [];
                    this.userAnswer = 0;
                    this.loadQuestion();
                    this.questionData = [];
                }

        async loadQuestion() {
            try {
                const gameId = 1; // Replace with actual game ID
                const topicId = 2; // Replace with actual topic ID
                const language = 'Java'; // Replace with desired language

                const response = await fetch(`/playgame/load-new-question/${gameId}/${topicId}/${language}`);
                this.questionData = await response.json();

                console.log('Fetched question data:', this.questionData); // Log the data to check response

                if (this.questionData.answers && this.questionData.answers.length === 4) {
                    // Populate HTML elements
                    document.getElementById("topic").innerHTML = `Topic ID: ${this.questionData.topic_id}`;
                    document.getElementById("question").innerHTML = this.questionData.question;
                    document.getElementById("correctAnswer").innerHTML = `TEST Correct Answer:  ${this.questionData.correct_answer}`;
                    document.getElementById("answer_0_label").innerHTML = this.questionData.answers[0];
                    document.getElementById("answer_1_label").innerHTML = this.questionData.answers[1];
                    document.getElementById("answer_2_label").innerHTML = this.questionData.answers[2];
                    document.getElementById("answer_3_label").innerHTML = this.questionData.answers[3];
                } else {
                    console.error("Error: Question data missing answers array or insufficient answers");
                }
            } catch (error) {
                console.error("Error loading question:", error);
            }
        }



                // Reset round, points counter, and player letters
                resetPoints() {
                    // Reset playerPoints to zeros
                    // players' points: int [COM_points, USER_points]
                    this.playerPoints = [0, 0];
                    
                    // Reset playerLetters to underscores
                    // players' letters: char [[COM_letters], [USER_letters]]
                    this.playerLetters = [["_","_","_","_","_",], ["_","_","_","_","_",]];
                }

                // Play multiple rounds until a Player wins the game
                playGame() {
                    let winner = -1;

                    // Reset players' points
                    this.resetPoints();

                    // TEST
                    this.playRound();
                    winner = this.checkWinCondition(); // Check if any Players won

                    //TODO: fix gameplay loop freezing page. Route to new view per round?
                    /*
                    while (winner === -1) {
                        this.playRound();   // Play a single round
                        winner = this.checkWinCondition(); // Check if any Players won
                    }
                    */
                }



                async playRound() {
                    document.getElementById("round").innerHTML = "ROUND " + game.getRoundNum();
                    await this.loadQuestion();  // Load a new question each round

                    let round = this.getRoundResponses();
                    this.currentRound++;
                    this.determineRound(round);
                    this.gameResponses.push(round);
                    this.updateHTML();
                    
                }


                getRoundNum() {
                    return this.currentRound;
                }

                getPlayerName(player) {
                    return this.playerNames[player];
                }

                getPlayerPoints(player) {
                    return this.playerPoints[player];
                }

                getPlayerLetters(player) {
                    let letters = ""
                    this.playerLetters[player].forEach(letter => {
                        letters += letter;
                    });
                    return letters;
                }

                // Update points for the given player
                updatePoints(player) {
                    // Get player's current points
                    let points = this.playerPoints[player];

                    // If points is in the valid range
                    if (points > -1 && points < 5) {
                        // Give player 1 point and a letter
                        this.playerPoints[player]++;
                        this.playerLetters[player][points-1] = this.HORSE[points-1];
                    } 
                }

                // Set points for the given player
                setPoints(player, points) {
                    // If points is in the valid range
                    if (points > -1 && points < 6) {
                        // assign player points
                        this.playerPoints[player] = points;
                    }
                    // Else if points too low, assign player 0 points
                    else if (points < 0) {
                        this.playerPoints[player] = 0;
                    }
                    // Else if points too high, assign player 0 points
                    else if (points > 5) {
                        this.playerPoints[player] = 5;
                    }

                    // Loop through and update player letters based on points
                    for (let i = 0; i < points && i < 5; i++) {
                        this.playerLetters[player][i] = this.HORSE[i];
                    }
                }

                // Return the players' responses for the current round
                getRoundResponses() {
                    let comAnswer = Math.ceil(Math.random() * 3);  // Integer value for COM's answer
                    let userAnswerElement = document.querySelector('input[name="selection"]:checked');
                    
                    // Get the integer value from the input tag's value attribute
                    let userAnswerValue = userAnswerElement ? parseInt(userAnswerElement.value, 10) : null;
                    
                    // Get the label text for display purposes
                    let userAnswerText = "";
                    if (userAnswerElement) {
                        let userAnswerLabel = document.querySelector(`label[for="${userAnswerElement.id}"]`);
                        userAnswerText = userAnswerLabel ? userAnswerLabel.innerText : "";
                    }

                    let comAnswerText = document.querySelector(`label[for="answer_${comAnswer}"]`).innerText || "";


                    let correctAnswer = document.querySelector(`label[for="answer_${this.questionData.correctAnswer}"]`).innerText || "";  // replace with correct answer from database

                    // Store both integer value and text in gameResponses
                    return [{ value: comAnswer, text: comAnswerText}, { value: userAnswerValue, text: userAnswerText }, correctAnswer];
                }


    
                // Update HTML elements
                updateHTML() {  
                    console.log(this.gameResponses)
                    const latestRound = this.gameResponses[this.gameResponses.length - 1]; // Access the most recent round
                    document.getElementById("com_selection").innerHTML = "COM selected: " + latestRound[0].text;
                    document.getElementById("user_selection").innerHTML = "USER selected: " + latestRound[1].text;

                    document.getElementById("COM").innerHTML = game.getPlayerName(0) + ": " 
                                                                + game.getPlayerPoints(0) + " " 
                                                                + game.getPlayerLetters(0);
                    document.getElementById("USER").innerHTML = game.getPlayerName(1) + ": " 
                                                                + game.getPlayerPoints(1) + " " 
                                                                + game.getPlayerLetters(1);
                }

                // Compare player answers and update points
                determineRound(round) {
                    const comAnswer = round[0].value;
                    const userAnswer = round[1].value;
                    const correctAnswer = round[2];

                    // Determine round winner
                    // If there was no tie
                    if (comAnswer !== userAnswer) {
                        // If the computer answered correctly
                        if (comAnswer === correctAnswer) {
                            // Update computer's points
                            this.updatePoints(0);
                        // If the user answered correctly
                        } else if (userAnswer === correctAnswer) {
                            // Update user's points
                            this.updatePoints(1);
                        }
                    }
                }

                // Check if a player has won (has 5 points)
                checkWinCondition() {
                    // For each player
                    for (let i = 0; i < this.playerPoints.length; i++) {
                        // Check if player has at least 5 points
                        if (this.getPlayerPoints(i) >= 5) {
                            document.getElementById("winner").innerHTML = this.getPlayerName(i) +  " spelled HORSE!";

                            // Return index of player
                            return i;
                        }
                    }

                    // Else, return -1 (no winner yet)
                    return -1;
                }
            }

    /*         // Initialize new game
            game = new Game();
            game.playGame();  */
            let userAnswer = 0
            game = new Game();


            document.getElementById('gameForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting normally

                // Find the selected radio button
                userAnswer = document.querySelector('input[name="selection"]:checked');
                if (userAnswer) {
                    // Update userAnswer in the Game instance
                    game.userAnswer = parseInt(userAnswer.value);
                    // Now call any function that uses the user answer, e.g., playRound
                    game.playRound();
                } else {
                    alert('Please select an answer before submitting.');
                }
            });
            
        </script>

    </div>
</div>

</div>
</x-app-layout>