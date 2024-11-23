<x-app-layout>
    <header class="bg-white dark:bg-gray-800 shadow">            
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="text-align: center">
                {{ __('Play Game') }}
            </h2>
        </div>
    </header>

    <div class="dark:bg-gray-800 bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-white" style="margin-top: 50px; font-family:'Urbanist';padding-bottom: 20px; border-radius: 25px; padding-top: 10px;">

        <div style="padding-top: 25px;padding-bottom: 25px;">
            <!--Main Menu Button-->
            <x-primary-button style="width: fit; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('dashboard') }}'">Return to Main Menu</x-primary-button>
        </div>
        
        <h1 id="round" style="font-size: 30px; margin-bottom: 10px; font-weight: 900;">ROUND {{ session('round_num', 1) }}</h1>

        <form id="gameForm" action="{{ route('rounds.store') }}" method="POST" style="padding-bottom: 10px;">
            @csrf

            <!-- Hidden fields for game data -->
            <input type="hidden" name="game_id" id="game_id" value="{{ session('game_id') }}">
            <input type="hidden" name="round_num" id="round_num" value="{{ session('round_num', 1) }}">
            <input type="hidden" name="question_id" value="{{ session('question_id') ?? '' }}">

            <!-- Display topic and question data -->
            <!--<p id="topic">Topic ID: {{ session('topic_id') ?? 'Topic Loading...' }}</p>-->
            <p id="question">Question: {{ session('prompt') ?? 'Question Loading...' }}</p>

            <p id="correct_answer">Correct Answer: {{ session('correct_answer') ?? 'Correct Answer Loading...' }}</p>


            @if (session('question'))
                @foreach([
                    session('question')->correct_answer,
                    session('question')->incorrect_1,
                    session('question')->incorrect_2,
                    session('question')->incorrect_3
                ] as $index => $answer)
                    <input type="radio" id="answer_{{ $index }}" name="selection" value="{{ $answer }}" required>
                    <label for="answer_{{ $index }}">{{ $answer }}</label><br>
                @endforeach
            @else
                <p>Answers Loading...</p>
            @endif


            <button type="submit">Submit</button>
        </form>

        @if (session('user_selection'))
            <p id="com_selection">COM selected: {{ session('com_selection') }}</p>    
            <p id="user_selection">USER selected: {{ session('user_selection') }}</p>
            <!--<p id="round_winner">Round Winner: {{ session('round_winner') }}</p>-->
        @endif

        <!-- Display scores -->
        <p>COM points: {{ session('com_points', 0) }}</p>
        <p>USER points: {{ session('user_points', 0) }}</p>

        <!-- Button for next question, only visible after form submission -->
        @if (session('round_winner') !== null)
            @if (session('user_points', 0) >= 5)
                <p>[Winner: USER]</p>
                <p class="mt-4">Your final score: <strong>{{ session('user_score_percentage') }}%</strong></p>
                <x-primary-button style="width: 270px; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('new_studyguide') }}'">Generate Study Guide</x-primary-button>
            @elseif (session('com_points', 0) >= 5)
            <p class="mt-4">Your final score: <strong>{{ session('user_score_percentage') }}%</strong></p>
                <p>[Winner: COM]</p>
                <x-primary-button style="width: 270px; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('new_studyguide') }}'">Generate Study Guide</x-primary-button>

            @else
                <form action="{{ route('rounds.next') }}" method="POST" style="padding-top: 10px;">
                    @csrf
                    <button type="submit">Move on to Next Question</button>
                </form>
            @endif
        @else
            <p>[no winner yet]</p>
        @endif
    </div>
</x-app-layout>
