<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style=" text-align: center">
            {{ __('Main Menu') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg" style="width: 400px; display: flex; flex-direction: column; gap: 20px; margin: 0 auto; align-items: center; padding-top: 35px; padding-bottom: 35px;">

                <x-primary-button style="width: 270px; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('playgame') }}'">Play a Game</x-primary-button>
                <x-primary-button style="width: 270px; text-align: center;" onclick="window.location.href='{{ route('rules') }}'">View Rules</x-primary-button>
                <x-primary-button style="width: 270px; text-align: center;" onclick="window.location.href='{{ route('rules') }}'">View List of Topics</x-primary-button>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
