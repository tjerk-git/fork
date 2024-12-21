import './bootstrap';

// Initialize Tailwind dark mode based on system preference
if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.classList.add('dark')
} else {
    document.documentElement.classList.remove('dark')
}
