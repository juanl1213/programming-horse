<x-app-layout>
    <header class="bg-white dark:bg-gray-800 shadow">            
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="text-align: center">
                {{ __('Play Game') }}
            </h2>
        </div>
    </header>

    <div class="dark:bg-gray-800 bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-white" style="margin-top: 50px; font-family:'Urbanist';padding-bottom: 20px; border-radius: 25px; padding-top: 10px;">
        <h1 id="round" style="font-size: 30px; margin-bottom: 10px; font-weight: 900;">ROUND {{ session('round_num', 1) }}</h1>

        <form id="gameForm" action="{{ route('rounds.store') }}" method="POST">
    @csrf

    <!-- Hidden fields for game data -->
    <input type="hidden" name="game_id" id="game_id" value="{{ session('game_id') }}">
    <input type="hidden" name="round_num" id="round_num" value="{{ session('round_num', 1) }}">
    <input type="hidden" name="question_id" value="{{ $question->question_id ?? '' }}">

    <!-- Display topic and question data -->
    <p id="topic">Topic ID: {{ $question->topic_id ?? 'Topic Loading...' }}</p>
    <p id="question">Question: {{ $question->question ?? 'Question Loading...' }}</p>

    <!-- Display answer options, with error handling -->
    @if(isset($question))
        @foreach([$question->correct_answer, $question->incorrect_1, $question->incorrect_2, $question->incorrect_3] as $index => $answer)
            <input type="radio" id="answer_{{ $index }}" name="selection" value="{{ $answer }}" required>
            <label for="answer_{{ $index }}">{{ $answer }}</label><br>
        @endforeach
    @else
        <p>Answers Loading...</p>
    @endif

    <input type="submit" value="Submit">
</form>

       <!-- Display user and COM selection only after submission -->
      <!-- Display User and COM selection after submission -->
      <p id="user_selection" style="display: none;">USER selected: <span id="user_answer_text"></span></p>
        <p id="com_selection" style="display: none;">COM selected: <span id="com_answer_text"></span></p>

        <p id="COM">COM points: {{ session('com_points', 0) }}</p>
        <p id="USER">USER points: {{ session('user_points', 0) }}</p>

        <p id="winner">{{ session('winner', '[no winner yet]') }}</p>
    </div>
</x-app-layout>
