<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style=" text-align: center">
            {{ __('Database Management') }}
        </h2>
    </x-slot>
    
    <div style="padding-top: 25px; padding-left: 25px;">
        <!--Main Menu Button-->
        <x-primary-button style="width: fit; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('dashboard') }}'">Return to Main Menu</x-primary-button>
    </div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg div-ele">

                <x-primary-button style="width: 270px; text-align: center; margin-right: 0;" onclick="window.location.href='{{ route('userstable') }}'">Users Table</x-primary-button>
                <x-primary-button style="width: 270px; text-align: center;" onclick="window.location.href='{{ route('question') }}'">Questions Table</x-primary-button>
            </div>
        </div>
    </div>
</div>

<style> 
.div-ele {
    width: 400px;
    display: flex; 
    flex-direction: column; 
    gap: 20px; 
    margin: 0 auto; align-items: center; padding-top: 35px; padding-bottom: 35px;
}

@media (max-width: 395px) {
            .div-ele {
                width: 350px;
            }
    }
</style>
</x-app-layout>
