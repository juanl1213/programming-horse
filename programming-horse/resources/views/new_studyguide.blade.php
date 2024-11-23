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
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
    <h2 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Review Recommendations</h2>

    <div id="recommendations" class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-inner">
        <p class="text-gray-800 italic">Fetching recommendations for {{ $topicName }} in {{ $language }}...</p>
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
    </style>


<script>
    // Fetch recommendations dynamically
    const topicId = {{ $topicId }};
    const language = "{{ $language }}";

    fetch(`{{ route('recommendations.for_game') }}?topic_id=${topicId}&language=${encodeURIComponent(language)}`)
        .then(response => response.json())
        .then(data => {
            const recommendationsDiv = document.getElementById('recommendations');
            if (data.recommendations) {
                const lines = data.recommendations.split('\n').filter(line => line.trim() !== '');
                    const formattedRecommendations = lines.map(line => `<li>${line.trim()}</li>`).join('');

                    recommendationsDiv.innerHTML = `
                    <ul class="list-decimal list-inside dark:text-white text-gray-800 leading-relaxed">
                        ${formattedRecommendations}
                    </ul>`;
            } else {
                recommendationsDiv.innerHTML = `<p class="text-gray-800 italic">No recommendations available.</p>`;
            }
        })
        .catch(error => {
            console.error('Error fetching recommendations:', error);
            document.getElementById('recommendations').innerHTML = `<p class="text-gray-800 italic">Failed to fetch recommendations.</p>`;
        });
</script>
</x-app-layout>
