<!-- resources/views/admin/redirect.blade.php -->
<x-app-layout>
    <form id="redirectForm" action="{{ route('questions.filter') }}" method="POST">
        @csrf
        <input type="hidden" name="topic_id" value="{{ $topic_id }}">
        <input type="hidden" name="language" value="{{ $language }}">
    </form>

    <script>
        // Automatically submit the form when the page loads
        document.getElementById('redirectForm').submit();
    </script>
</x-app-layout>