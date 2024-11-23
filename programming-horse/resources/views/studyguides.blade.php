<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Study Guides') }}
        </h2>
    </x-slot>

    <div style="padding-top: 25px; padding-left: 25px;">
        <!--Main Menu Button-->
        <x-primary-button style="width: fit; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('dashboard') }}'">Return to Main Menu</x-primary-button>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Performance Section -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h1 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Your Performance</h1>

                <!-- Loop through each language -->
                @foreach ($performanceData as $language => $topics)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                        <h3 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-4">
                            {{ $language }} Performance
                        </h3>

                        <!-- Topics Table -->
                        <div class="space-y-4">
                            @foreach ($topics as $topic => $percentage)
                                <div class="flex items-center">
                                    <!-- Topic Name -->
                                    <p class="text-[#4e6797] font-semibold w-48">{{ $topic }}</p>
                                    
                                    <!-- Progress Bar or No Data Message -->
                                    @if (!is_null($percentage))
                                        <div class="relative flex-1 h-6 bg-gray-200 rounded-full shadow-inner">
                                            <div class="green absolute left-0 top-0 h-full rounded-full bg-green-500 shadow-md" 
                                                 style="width: {{ $percentage }}%;">
                                                <span class="text-white text-sm font-semibold flex justify-center items-center h-full">
                                                    {{ $percentage }}%
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                    <div class="relative flex-1 h-6 bg-gray-200 rounded-full shadow-inner">
                                            
                                                
                                                <span class="text-sm font-semibold flex justify-center items-center h-full">
                                                    No data available
                                                </span>
                                            
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Review Recommendations Section -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-[#0e121b] text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Recommendations</h2>

                @if ($lowestTopic && $lowestLanguage)
                    <p class="mb-4 text-[#0e121b] text-lg font-medium">
                        Recommendations for <strong>{{ $lowestTopic }}</strong> in <strong>{{ $lowestLanguage }}</strong>:
                    </p>
                    <div id="recommendations">
                        <!-- Add JavaScript or dynamic fetching for OpenAI recommendations -->
                        <p>Fetching recommendations...</p>
                    </div>
                @else
                    <p class="text-gray-800 italic">No recommendations available.</p>
                @endif
            </div>
        </div>

        <br>

    </div>
    <style>
        .green {
            background-color: green;
        }
    </style>
    <script>
        // Use AJAX to fetch recommendations from OpenAIController
        fetch('{{ route('openai.lowest_score_recommendation') }}')
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
                    recommendationsDiv.innerHTML = `<p>No recommendations available.</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching recommendations:', error);
                document.getElementById('recommendations').innerHTML = `<p>Error fetching recommendations.</p>`;
            });
    </script>
</x-app-layout>
