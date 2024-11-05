document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('dark_mode');
    
    darkModeToggle.addEventListener('change', function () {
        document.documentElement.classList.toggle('dark', this.checked);
    });
});