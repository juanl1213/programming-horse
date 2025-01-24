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
                    // Retrieve session variables for the language, topic, and percentage
                    $language = session('programming_language', 'Unknown');
                    $topicId = session('topic_id', 0);
                    $scorePercentage = session('user_score_percentage', 0);

                    // Map topic ID to topic names
                    $topics = [
                        1 => 'Data Types',
                        2 => 'Object Oriented Programming',
                        3 => 'Data Structures',
                        4 => 'Variable Types & Declarations',
                    ];
                    $topicName = $topics[$topicId] ?? 'Unknown Topic';
                @endphp

            <!-- Loop through each language -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h3 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-4">
                        Performance for {{ $language }} - {{ $topicName }}
                    </h3>

                    <!-- Topic Progress -->
                    <div class="flex items-center">
                        <p class="text-[#4e6797] font-semibold w-48">{{ $topicName }}</p>

                        <!-- Progress Bar -->
                        <div class="relative flex-1 h-6 bg-gray-200 rounded-full shadow-inner">
                            <div class="green absolute left-0 top-0 h-full rounded-full bg-green-500 shadow-md"
                                 style="width: {{ $scorePercentage }}%; box-shadow: inset 0px 1px 4px rgba(0, 0, 0, 0.4);">
                                <span class="text-white text-sm font-semibold flex justify-center items-center h-full">
                                    {{ $scorePercentage }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Review Recommendations Section -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 box">
                <h2 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Review Recommendations</h2>

                <div id="recommendations" class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-inner">
                    <p class="mb-4 text-[#0e121b] text-lg font-medium">Fetching recommendations for {{ $topicName }} in {{ $language }}...</p>
                </div>
            </div>

            <!-- Main Menu Button -->
            <div class="flex flex-col items-center mt-8 space-y-3">
            <x-primary-button style="width: fit; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('dashboard') }}'">Return to Main Menu</x-primary-button>
            </div>
        </div>
    </div>
    <style>
        .green{
            background-color: green;
        }
        .width {
            width: 50%;
        }
        #recommendations {
            font-size: 12px;
        }
        .box {
            width: 50%;
            margin-left: 25%;
        }
        @media (max-width: 480px) {
        .box {
            width: 90%;
            margin-left: 5%;
        }


    }
        
        /* Recommendations Container */
        .recommendations-container {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .recommendations-container ul {
            list-style-type: decimal;
            padding-left: 20px;
        }

        .recommendations-container li {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .recommendations-container li a {
            color: #2563eb; /* Blue */
            text-decoration: underline;
            transition: color 0.2s ease;
        }

        .recommendations-container li a:hover {
            color: #1e40af; /* Darker blue */
        }
    </style>


<script>
    // Fetch recommendations dynamically
    const topicId = {{ $topicId }};
    const language = "{{ $language }}";

    fetch(`{{ route('recommendations.for_game') }}?topic_id=${topicId}&language=${encodeURIComponent(language)}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const recommendationsDiv = document.getElementById('recommendations');
            if (data.recommendations) {
                const lines = data.recommendations.split('\n').filter(line => line.trim() !== '');
                const formattedRecommendations = lines.map(line => {
                    // Regex to find URLs
                    const urlRegex = /\(?https?:\/\/[^\s]+\)?/g;                    // Replace URLs with clickable links
                    const formattedLine = line.replace(urlRegex, url => {
                        const cleanUrl = url.replace(/^\(|\)$/g, ''); // Remove parentheses from start and end
                        return `<a href="${cleanUrl}" target="_blank" rel="noopener noreferrer" class="text-blue-600 underline">${cleanUrl}</a>`;
                    });
                    return `<li>${formattedLine}</li>`;
                }).join('');

                recommendationsDiv.innerHTML = `
                    <ul class="list-decimal list-inside dark:text-white text-gray-800 leading-relaxed">
                        ${formattedRecommendations}
                    </ul>`;
            } else {
                recommendationsDiv.innerHTML = `<p class="mb-4 text-[#0e121b] text-lg font-medium">No recommendations available.</p>`;
            }
        })
        .catch(error => {
            console.error('Error fetching recommendations:', error);
            document.getElementById('recommendations').innerHTML = `<p class="text-gray-800 italic">Failed to fetch recommendations.</p>`;
        });
</script>
</x-app-layout>
