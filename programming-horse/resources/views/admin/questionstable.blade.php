<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Questions Table') }}
        </h2>
    </x-slot>

    <div style="padding-top: 25px; padding-bottom: 25px; padding-left: 25px;">
        <!--Back Button-->
        <x-primary-button style="width: fit; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('admin') }}'">Back</x-primary-button>
    </div>

    <div class="py-12">
        <div class="table-container mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Filtering Form -->
                <div class="mb-6">
                    <form method="POST" action="{{ route('questions.filter') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- Topic ID Filter -->
                            <div>
                                <label for="topic_id" class="block text-sm font-medium dark:text-white text-gray-700">Topic ID</label>
                                <select id="topic_id" name="topic_id" 
                                        class=" mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <option class="dark:text-gray-800" value="1">Data Types</option>
                                    <option value="2">Object Oriented Programming</option>
                                    <option value="3">Data Structures</option>
                                    <option value="4">Variable Types & Declarations</option>
                                </select>
                            </div>

                                <!-- Language Filter -->
                                <div>
                                    <label for="language" class="dark:text-white block text-sm font-medium text-gray-700">Language</label>
                                    <select id="language" name="language" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            required>
                                        <option value="Python">Python</option>
                                        <option value="Java">Java</option>
                                        <option value="C++">C++</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold text-sm tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('questions.create') }}" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Add Question
                        </a>
                    </div>  

                    @if(isset($questions) && $questions->isNotEmpty())
                    <!-- Responsive Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-sm border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Question ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Language</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Topic ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Question</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Correct Answer</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Incorrect Answer 1</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Incorrect Answer 2</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Incorrect Answer 3</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Validated</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $question)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-2">{{ $question->question_id }}</td>
                                        <td class="px-4 py-2">{{ $question->language }}</td>
                                        <td class="px-4 py-2">{{ $question->topic_id }}</td>
                                        <td class="px-4 py-2 break-words">{{ $question->question }}</td>
                                        <td class="px-4 py-2">{{ $question->correct_answer }}</td>
                                        <td class="px-4 py-2">{{ $question->incorrect_1 }}</td>
                                        <td class="px-4 py-2">{{ $question->incorrect_2 }}</td>
                                        <td class="px-4 py-2">{{ $question->incorrect_3 }}</td>
                                        <td class="px-4 py-2">{{ $question->validated ? 'Yes' : 'No' }}</td>
                                        <td class="px-4 py-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('questions.edit', $question->question_id) }}" class="text-blue-500 hover:underline">Edit</a>
                                            <!-- Delete Button -->
                                            <form action="{{ route('questions.destroy', $question->question_id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this question?');"
                                                        class="text-red-500 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p>No questions found. Please use the filter above to search for questions.</p>
                    @endif

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
