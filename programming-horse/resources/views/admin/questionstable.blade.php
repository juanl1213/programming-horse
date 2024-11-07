<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Questions Table') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="table mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

              

                    <!-- Display Questions Data in a Table -->
                    <table class="min-w-full table-auto text">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="px-4 py-2">Question ID</th>
                                <th class="px-4 py-2">Language</th>
                                <th class="px-4 py-2">Topic ID</th>
                                <th class="px-4 py-2">Question</th>
                                <th class="px-4 py-2">Correct Answer</th>
                                <th class="px-4 py-2">Incorrect Answer 1</th>
                                <th class="px-4 py-2">Incorrect Answer 2</th>
                                <th class="px-4 py-2">Incorrect Answer 3</th>
                                <th class="px-4 py-2">Validated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $question)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $question->question_id }}</td>
                                    <td class="px-4 py-2">{{ $question->language }}</td>
                                    <td class="px-4 py-2">{{ $question->topic_id }}</td>
                                    <td class="px-4 py-2">{{ $question->question }}</td>
                                    <td class="px-4 py-2">{{ $question->correct_answer }}</td>
                                    <td class="px-4 py-2">{{ $question->incorrect_1 }}</td>
                                    <td class="px-4 py-2">{{ $question->incorrect_2 }}</td>
                                    <td class="px-4 py-2">{{ $question->incorrect_3 }}</td>
                                    <td class="px-4 py-2">{{ $question->validated ? 'Yes' : 'No' }}</td>
                                    <td class="px-2 py-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('questions.edit', $question->question_id) }}" class="text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <style>
    .text {
        font-size: 13px;
    }
    .table {
        width: 700px;
    }
    </style>
</x-app-layout>
