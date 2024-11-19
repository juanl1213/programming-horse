<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('New Study Guide') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Performance Section -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Your Performance</h2>

                
            @php
                $data = [
                    'Python' => [
                        'Data Types' => 75,
                        'OOP' => 50,
                        'Data Structures' => 90,
                        'Variable Types & Declarations' => 40,
                    ],
                    'Java' => [
                        'Data Types' => 65,
                        'OOP' => 80,
                        'Data Structures' => 55,
                        'Variable Types & Declarations' => 30,
                    ],
                    'C++' => [
                        'Data Types' => 45,
                        'OOP' => 70,
                        'Data Structures' => 85,
                        'Variable Types & Declarations' => 60,
                    ],
                ];
            @endphp

            <!-- Loop through each language -->
            @foreach ($data as $language => $topics)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h3 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-4">{{ $language }} Performance</h3>

                    <!-- Topics Table -->
                    <div class="space-y-4">
                        @foreach ($topics as $topic => $percentage)
                            <div class="flex items-center">
                                <!-- Topic Name -->
                                <p class="text-[#4e6797] font-semibold w-48">{{ $topic }}</p>
                                
                                <!-- Progress Bar -->
                                <div class="relative flex-1 h-6 bg-gray-200 rounded-full shadow-inner">
                                    <div class="green absolute left-0 top-0 h-full rounded-full bg-green-500 shadow-md" 
                                         style="width: {{ $percentage }}%; box-shadow: inset 0px 1px 4px rgba(0, 0, 0, 0.4);">
                                        <span class="text-white text-sm font-semibold flex justify-center items-center h-full">
                                            {{ $percentage }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            </div>

            <!-- Review Recommendations Section -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em]">Review Recommendations</h2>

                @php
                    $recommendations = [
                        'Data Types' => 'Read more about data types in the official JavaScript documentation.',
                        'Operators' => 'Learn how to use operators in JavaScript from this YouTube video.',
                    ];
                @endphp
                @foreach ($recommendations as $topic => $description)
                    <div class="flex items-center justify-between gap-4 bg-[#f8f9fc] rounded-lg p-4 my-3 shadow">
                        <div>
                            <p class="text-[#0e121b] text-base font-medium">{{ $topic }}</p>
                            <p class="text-[#4e6797] text-sm">{{ $description }}</p>
                        </div>
                        <a href="#" class="text-blue-600 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256">
                                <path d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"/>
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col items-center mt-8 space-y-3">
                <a href="#" class="flex items-center justify-center w-full max-w-md bg-blue-600 text-white font-bold py-3 rounded-lg shadow hover:bg-blue-700">
                    Download as Text File
                </a>
                <a href="#" class="flex items-center justify-center w-full max-w-md bg-gray-200 text-gray-800 font-bold py-3 rounded-lg shadow hover:bg-gray-300">
                    Save for Later
                </a>
            </div>
        </div>
    </div>
    <style>
        .green{
            background-color: green;
        }
    </style>
</x-app-layout>
