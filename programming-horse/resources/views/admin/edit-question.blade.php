<!-- resources/views/admin/edit-question.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Question') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Edit Question: {{ $editQuestion->question }}</h3>
                    
                    <form method="POST" action="{{ route('questions.update', $editQuestion->question_id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Hidden Fields for Redirection -->
                        <input type="hidden" name="topic_id" value="{{ $editQuestion->topic_id }}">
                        <input type="hidden" name="language" value="{{ $editQuestion->language }}">

                        <!-- Language Field -->
                        <div class="mb-4">
                            <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                            <input type="text" id="language" name="language" 
       value="{{ old('language', $editQuestion->language) }}" 
       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                      <!--       @error('language')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror -->
                        </div>

                        <!-- Question Text Field -->
                        <div class="mb-4">
                            <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                            <textarea id="question" name="question"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                      >{{ old('question', $editQuestion->question) }}</textarea>
                            @error('question')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Correct Answer Field -->
                        <div class="mb-4">
                            <label for="correct_answer" class="block text-sm font-medium text-gray-700">Correct Answer</label>
                            <input type="text" id="correct_answer" name="correct_answer" 
                                   value="{{ old('correct_answer', $editQuestion->correct_answer) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   >
                            @error('correct_answer')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Incorrect Answer 1 Field -->
                        <div class="mb-4">
                            <label for="incorrect_1" class="block text-sm font-medium text-gray-700">Incorrect Answer 1</label>
                            <input type="text" id="incorrect_1" name="incorrect_1" 
                                   value="{{ old('incorrect_1', $editQuestion->incorrect_1) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   >
                           <!--  @error('incorrect_1')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror -->
                        </div>

                        <!-- Incorrect Answer 2 Field -->
                        <div class="mb-4">
                            <label for="incorrect_2" class="block text-sm font-medium text-gray-700">Incorrect Answer 2</label>
                            <input type="text" id="incorrect_2" name="incorrect_2" 
                                   value="{{ old('incorrect_2', $editQuestion->incorrect_2) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   >
                            @error('incorrect_2')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Incorrect Answer 3 Field -->
                        <div class="mb-4">
                            <label for="incorrect_3" class="block text-sm font-medium text-gray-700">Incorrect Answer 3</label>
                            <input type="text" id="incorrect_3" name="incorrect_3" 
                                   value="{{ old('incorrect_3', $editQuestion->incorrect_3) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   >
                            @error('incorrect_3')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Validated Field -->
                        <div class="mb-4">
                            <label for="validated" class="block text-sm font-medium text-gray-700">Validated</label>
                            <select id="validated" name="validated"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                <option value="1" {{ old('validated', $editQuestion->validated) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('validated', $editQuestion->validated) ? '' : 'selected' }}>No</option>
                            </select>
                            @error('validated')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Update Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
