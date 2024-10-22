<!-- resources/views/components/dark-mode-toggle.blade.php -->
<div x-data="{ darkMode: @json(session('dark_mode', false)) }" class="flex items-center">
    <button
        @click="darkMode = !darkMode; $dispatch('dark-mode-changed', darkMode)"
        class="relative inline-flex h-6 w-11 items-center rounded-full"
        :class="{ 'bg-gray-200': !darkMode, 'bg-blue-600': darkMode }"
    >
        <span class="sr-only">Toggle dark mode</span>
        <span
            class="inline-block h-4 w-4 transform rounded-full bg-white transition"
            :class="{ 'translate-x-6': darkMode, 'translate-x-1': !darkMode }"
        ></span>
    </button>
    <span class="ml-3 text-sm font-medium" x-text="darkMode ? 'Dark' : 'Light'"></span>
</div>

<script>
    document.addEventListener('dark-mode-changed', (event) => {
        fetch('{{ route('toggle-dark-mode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ dark_mode: event.detail })
        }).then(response => response.json())
          .then(data => {
              if (data.dark_mode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          });
    });
</script>