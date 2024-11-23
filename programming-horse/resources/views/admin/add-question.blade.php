<!-- resources/views/admin/add-question.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Question') }}
        </h2>
    </x-slot>

    <div style="padding-top: 25px; padding-bottom: 25px; padding-left: 25px;">
        <!--Back Button-->
        <x-primary-button style="width: fit; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('question') }}'">Back</x-primary-button>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('questions.store') }}">
                        @csrf

                        <!-- Language Field -->
                        <div class="mb-4">
                            <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                            <input type="text" id="language" name="language" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
>
                         <!--    @error('language')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror -->
                        </div>

                        <!-- Topic ID Field -->
                        <div class="mb-4">
                            <label for="topic_id" class="block text-sm font-medium text-gray-700">Topic ID</label>
                            <input type="number" id="topic_id" name="topic_id"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   >
                           <!--  @error('topic_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror -->
                        </div>

                        <!-- Question Text Field -->
                        <div class="mb-4">
                            <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                            <textarea id="question" name="question"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                      required></textarea>
                            @error('question')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Correct Answer Field -->
                        <div class="mb-4">
                            <label for="correct_answer" class="block text-sm font-medium text-gray-700">Correct Answer</label>
                            <input type="text" id="correct_answer" name="correct_answer"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   required>
                            @error('correct_answer')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Incorrect Answers -->
                        @for ($i = 1; $i <= 3; $i++)
                            <div class="mb-4">
                                <label for="incorrect_{{ $i }}" class="block text-sm font-medium text-gray-700">Incorrect Answer {{ $i }}</label>
                                <input type="text" id="incorrect_{{ $i }}" name="incorrect_{{ $i }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       required>
                                @error("incorrect_{{ $i }}")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endfor

                        <!-- Validated Field -->
                        <div class="mb-4">
                            <label for="validated" class="block text-sm font-medium text-gray-700">Validated</label>
                            <select id="validated" name="validated"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('validated')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-split justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Add Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
