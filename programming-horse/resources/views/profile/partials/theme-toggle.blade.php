<section>
    
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Display Settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Toggle between light and dark mode.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex items-center">
            <x-input-label for="light-toggle" :value="__('Enable Dark Mode')" />

            <!-- Toggle switch structure -->
            <label class="switch ml-3">
                <input type="checkbox" id="dark_mode" name="dark_mode" x-model="darkMode" onclick="toggleDarkMode()">
                <span class="slider round"></span>
            </label>
        </div>

        <div x-text="'Current mode: ' + (darkMode ? 'Dark' : 'Light')"></div>

        @if (session('status') === 'settings-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400"
            >{{ __('Settings saved.') }}</p>
        @endif
    </form>
</section>

<style>
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 25px;
}

.light-mode {
  background-color: #ffffff;
  color: #333333;
}
.dark-mode {
  background-color: #333333;
  color: #ffffff;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 17px;
  width: 17px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
}

/* Style for when the checkbox is checked */
input:checked + .slider {
  background-color: #ff0000;
}

input:checked + .slider:before {
  transform: translateX(34px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

@media (prefers-color-scheme: dark) {
  .dark\:text-gray-100 {
      color: #f3f4f6;
  }
  .dark\:text-gray-400 {
      color: #9ca3af;
  }
}
</style>
<script>
     function toggleDarkMode() {
            const body = document.getElementById('page-body');
            body.classList.toggle('dark-mode');
            const div = document.getElementsByTagName("div");
            div.classList.toggle('dark-mode');
      }
</script>
