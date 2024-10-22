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
        <form action="/playgame">
            <input type="radio" id="c++" name="selection" value="C++">
            <label for="c++">C++</label><br>

            <input type="radio" id="java" name="selection" value="Java">
            <label for="java">Java</label><br>

            <input type="radio" id="python" name="selection" value="Python">
            <label for="python">Python</label><br>

            <input type="radio" id="javascript" name="selection" value="JavaScript">
            <label for="javascript">JavaScript</label><br><br>

            <input type="submit" value="Submit">
        </form>

        <!--Game Script-->
        <script>
            class Game {
                // Game class constructor
                constructor() {
                    // Attributes
                    this.currentRound = 0;
                }

                getRound() {
                    return this.currentRound;
                }
            }

            // Initialize new game
            game = new Game();

            // Update HTML elements
            document.getElementById("round").innerHTML = "ROUND " + game.getRound();
        </script>

    </div>
</div>

</div>
</x-app-layout>