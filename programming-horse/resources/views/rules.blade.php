<x-app-layout>
   
    <header class="bg-white dark:bg-gray-800 shadow">            
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                {{ __('Rules') }}
            </h2>
        </div>
    </header>
    <div class="bg-white dark:bg-gray-800 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 content-section text-gray-800 dark:text-white rules-box">
        <h1 class="section-title" style="padding-top: 25px;">Overview</h1>
        <p class="section-text" style="padding-bottom: 25px;">
            Welcome to Programming HORSE! This game teaches early coding concepts. To start a game of Programming HORSE, just click the "Play a Game" button in the main menu. Each round, you will take turns with the computer answering a question based on an introductory programming topic. Press the appropriate button based on your answer selection.
            If both players answer correctly, the round results in a tie and no letters are rewarded. Otherwise, the sole player who answers correctly will earn a letter towards spelling H-O-R-S-E. The player who earns all 5 letters first wins the game!
        </p>
    </div>

   

    <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-white rules-box" >
        <h1 style="font-size: 30px; margin-bottom: 10px; font-weight: 900;">Topics</h1>
        <p class="section-text">
            Review the current list of topics available for gameplay:
        </p>
        <div class="bg-gray-200 dark:bg-gray-600 grid">    
            <x-topic-box>All Topics</x-topic-box>
            <x-topic-box>Data Types</x-topic-box>
            <x-topic-box>Arrays</x-topic-box>
            <x-topic-box>Functions</x-topic-box>
            <x-topic-box>Objects</x-topic-box>
            <x-topic-box>Strings</x-topic-box>
            <x-topic-box>Syntax</x-topic-box>
            <x-topic-box>Loops</x-topic-box>
            <x-topic-box>Operators</x-topic-box>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 content-section text-gray-800 dark:text-white rules-box">
        <h1 class="section-title" style="padding-top: 25px;">Saving your Study Guide</h1>
        <p class="section-text" style="padding-bottom: 25px;">
        At the end of a game of Programming HORSE, the program will create a custom study guide based on your in-game performance. The study guide will highlight your strongest and weakest topics, and recommend topics to study before the next game.
        <br>
        <br>
        <b>NOTE:</b> Players will not receive a study guide for an unfinished game. 
        <br>
        <b>NOTE:</b> Discarding a study guide means it will no longer be available to download.
        </p>
    </div>
    <style>
        .rules-box{
            margin-top: 5%; border-radius: 25px; width: 1000px;
        }
        .grid {
            display: grid; grid-template-columns: repeat(3, 0.4fr); gap: 5px; padding-bottom: 25px; width: 600px; margin: 0 auto; margin-top: 16px; border-radius: 25px;
        }
        @media (max-width: 600px) {
            .section-text{
                font-size: 17px;
            }
            .rules-box{
                width: 330px;
                font-size: 10px;
                padding-bottom: 25px;
            }
            .grid{
                width: 300px;
                gap: 2px;
   
            }
        }

        @media (min-width: 650px) and (max-width: 900px) {
            .rules-box {
                width: 750px;
            }
        }
    </style>

</x-app-layout>