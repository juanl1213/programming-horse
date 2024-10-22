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
        <h1 id="round" style="font-size: 30px; margin-bottom: 10px; font-weight: 900;">ROUND</h1>
        
        <!-- Current Question -->
        <p style="font-size: 20px" id="question">[insert question text here]</p>

        <!-- User Answer Selection -->
        <form action="/playgame" method="GET">
            <input type="radio" id="answer_a" name="selection" value=0>
            <label for="answer_a" id="answer_a_label"></label><br>

            <input type="radio" id="answer_b" name="selection" value=1>
            <label for="answer_b" id="answer_b_label"></label><br>

            <input type="radio" id="answer_c" name="selection" value=2>
            <label for="answer_c" id="answer_c_label"></label><br>

            <input type="radio" id="answer_d" name="selection" value=3>
            <label for="answer_d" id="answer_d_label"></label><br><br>

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
                // Game class constructor
                constructor() {
                    // Attributes  

                    // current round
                    this.currentRound = 0;

                    // templates for spelling HORSE
                    this.playerNames = ["COM", "USER"];
                    this.HORSE = [["_","_","_","_","_",], ["H","O","R","S","E"]];

                    // Reset player points and responses
                    this.resetPoints();

                    // players' responses for the entire game: int [[COM_response, USER_response]]
                    this.gameResponses = [];
                }

                // Play multiple rounds until a Player wins the game
                playGame() {
                    let winner = -1;

                    // Reset players' points
                    this.resetPoints();

                    // TEST
                    this.setPoints(1, 5);
                    this.playRound();
                    winner = this.checkWinCondition(); // Check if any Players won

                    /*
                    while (winner === -1) {
                        this.playRound();   // Play a single round
                        winner = this.checkWinCondition(); // Check if any Players won
                    }
                    */
                }

                // Play a single round
                playRound() {
                    // Update round counter
                    document.getElementById("round").innerHTML = "ROUND " + game.getRound();

                    // Load a question
                    this.loadQuestion();

                    // Get player responses
                    let round = this.getRoundResponses();

                    // Determine the round winner
                    //TODO: this.determineRound(round);

                    // Append player responses to the game tracker
                    this.gameResponses.push(round);

                    // Display player progress
                    this.updateHTML();

                    // Update round counter
                    this.currentRound++;
                }

                // Check if a player has won (has 5 points)
                checkWinCondition() {
                    for (let i = 0; i < this.playerPoints.length; i++) {
                        if (this.getPlayerPoints(i) === 5) {
                            document.getElementById("winner").innerHTML = this.getPlayerName(i) +  " spelled HORSE!";

                            return i;
                        }
                    }
                    return -1;
                }

                getRound() {
                    return this.currentRound;
                }

                getPlayerName(player) {
                    return this.playerNames[player];
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
                    this.playerPoints[player] += 1;
                }

                // Set points for the given player
                setPoints(player, points) {
                    this.playerPoints[player] = points;
                }

                // Return the players' responses for the current round
                getRoundResponses() {
                    let com_answer = Math.ceil(Math.random() * 3);
                    return [com_answer, <?php echo $_GET["selection"]?>];
                }

                // Retrieve a question from the database and update HTML form
                loadQuestion() {
                    document.getElementById("answer_a_label").innerHTML = "C++";
                    document.getElementById("answer_b_label").innerHTML = "Java";
                    document.getElementById("answer_c_label").innerHTML = "Python";
                    document.getElementById("answer_d_label").innerHTML = "JavaScript";
                }

                // Update HTML elements
                updateHTML() {  
                    document.getElementById("com_selection").innerHTML = "COM selected: " + this.gameResponses[this.currentRound][0];
                    document.getElementById("user_selection").innerHTML = "USER selected: " + this.gameResponses[this.currentRound][1];

                    document.getElementById("COM").innerHTML = game.getPlayerName(0) + ": " 
                                                                + game.getPlayerPoints(0) + " " 
                                                                + game.getPlayerLetters(0);
                    document.getElementById("USER").innerHTML = game.getPlayerName(1) + ": " 
                                                                + game.getPlayerPoints(1) + " " 
                                                                + game.getPlayerLetters(1);
                }
            }

            // Initialize new game
            game = new Game();
            game.playGame();
            
        </script>

    </div>
</div>

</div>
</x-app-layout>