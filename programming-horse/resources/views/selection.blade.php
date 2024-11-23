<x-app-layout>
   
    <header class="bg-white dark:bg-gray-800 shadow">            
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                {{ __('Topic and Language Selection') }}
            </h2>
        </div>
    </header>

    <div style="padding-top: 25px; padding-left: 25px;">
        <!--Main Menu Button-->
        <x-primary-button style="width: fit; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('dashboard') }}'">Return to Main Menu</x-primary-button>
    </div>

    <form action="{{ route('games.start') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Programming Language Selection -->
            <div>
                <h2 class="text-2xl font-bold mb-4 text-center">Please choose a programming language</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach(['Python', 'C++', 'Java'] as $language)
                        <label class="cursor-pointer">
                            <input type="radio" name="programming_language" value="{{ $language }}" class="hidden" required>
                            <div class="selection-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md 
                                      border-2 border-transparent 
                                      hover:border-blue-400 dark:hover:border-blue-500 
                                      dark:text-gray-200
                                      transition-all duration-200 text-center">
                                <span class="text-lg font-medium">{{ $language }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Topic Selection -->
            <div>
                <h2 class="text-2xl font-bold mb-4 text-center">Please choose a topic</h2>
                <div class="grid2">
                    @php
                    $topics = [
                        1 => 'Data Types',
                        2 => 'Object Oriented Programming',
                        3 => 'Data Structures',
                        4 => 'Variable Types & Declarations'
                        ];
                    @endphp

                    @foreach($topics as $id => $topic)
                    <label class="cursor-pointer">
                        <input type="radio" name="topic_id" value="{{ $id }}" class="hidden" required>                            <div class="selection-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md 
                                      border-2 border-transparent 
                                      hover:border-blue-400 dark:hover:border-blue-500
                                      dark:text-gray-200
                                      transition-all duration-200 text-center">
                                <span class="text-lg font-medium">{{ $topic }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Submit Button -->
            <button type="submit" 
                    class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-semibold start-button">
                Start Game
            </button>
        </form>
        <br>
    </div>
    <style>
    .grid {
            display: grid; grid-template-columns: repeat(3, 0.4fr); gap: 5px; padding-bottom: 25px; width: 600px; margin: 0 auto; margin-top: 16px; border-radius: 25px;
        }
    .grid2 {
        display: grid; grid-template-columns: repeat(2, 0.6fr); gap: 5px; padding-bottom: 25px; width: 600px; margin: 0 auto; margin-top: 16px; border-radius: 25px;
        justify-content:space-evenly;
    }
    .start-button {
       margin-left: 900px;
    }
    .selection-card.selected {
            border-color: #3b82f6; /* Blue-500 */
        }
        
    @media (prefers-color-scheme: dark) {
        .selection-card.selected {
            border-color: #60a5fa; /* Blue-400 */
        }
    }
    </style>
    <script>
        // Add active state to selection cards
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all siblings
                const name = this.getAttribute('name');
                document.querySelectorAll(`input[name="${name}"]`).forEach(sibling => {
                    sibling.parentElement.querySelector('.selection-card').classList.remove('selected');
                });
                
                // Add selected class to chosen card
                if (this.checked) {
                    this.parentElement.querySelector('.selection-card').classList.add('selected');
                }
            });
        });
    </script>

</x-app-layout>