<x-app-layout>
   
    <header class="bg-white dark:bg-gray-800 shadow">            
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                {{ __('Topic and Language Selection') }}
            </h2>
        </div>
    </header>
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
    </div>
    <style>
       /* Grid Layouts */
       .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        width: 600px;
        margin: 20px auto;
        padding-bottom: 25px;
    }

    .grid2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        width: 600px;
        margin: 20px auto;
        padding-bottom: 25px;
    }

    /* Button Styling */
    .start-button {
        display: block;
        margin: 30px auto 0;
        background-color: #3b82f6;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .start-button:hover {
        background-color: #2563eb; /* Darker blue */
    }

    /* Selection Cards */
    .selection-card {
        background-color: white;
        border: 2px solid transparent;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s ease, transform 0.2s ease;
    }

    .selection-card:hover {
        border-color: #3b82f6; /* Blue-500 */
        transform: scale(1.05); /* Slight zoom effect */
    }

    .selection-card.selected {
        border-color: #3b82f6;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.5); /* Blue glow */
    }

    @media (prefers-color-scheme: dark) {
        .selection-card {
            background-color: #1f2937; /* Gray-800 */
            color: #d1d5db; /* Gray-300 */
        }

        .selection-card.selected {
            border-color: #60a5fa; /* Light blue */
            box-shadow: 0 0 10px rgba(96, 165, 250, 0.5); /* Light blue glow */
        }
    }

    /* Error Messages */
    .bg-red-100 {
        background-color: #fee2e2; /* Light red */
        padding: 10px;
        border-radius: 8px;
    }

    .text-red-700 {
        color: #b91c1c; /* Dark red */
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .grid, .grid2 {
            grid-template-columns: repeat(2, 1fr);
            width: 90%; /* Adjust width for smaller screens */
        }

        .start-button {
            width: 80%; /* Adjust button size for tablets */
        }

        h2 {
            font-size: 20px; /* Adjust heading size */
        }

        .selection-card {
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .grid, .grid2 {
            grid-template-columns: 1fr; /* Single-column layout for mobile */
            width: 100%; /* Full width for mobile */
        }

        .start-button {
            width: 90%; /* Full-width button with margins */
        }

        h2 {
            font-size: 18px; /* Smaller heading size for mobile */
        }

        .selection-card {
            padding: 8px;
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