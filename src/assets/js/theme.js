$(document).on('change', '#theme-toggle', function() {

    const theme = $(this).is(':checked') ? 'dark' : 'light';

    document.documentElement.setAttribute('data-bs-theme', theme);

    if (theme === 'dark') {
        $('#theme-toggle-label').text('🌙');
    } else {
        $('#theme-toggle-label').text('☀️');
    }

    localStorage.setItem('theme', theme);

});

document.addEventListener('DOMContentLoaded', () => {

    const savedTheme = localStorage.getItem('theme') || 'light';

    document.documentElement.setAttribute('data-bs-theme', savedTheme);

    if (savedTheme === 'dark') {
        $('#theme-toggle-label').text('🌙');
    } else {
        $('#theme-toggle-label').text('☀️');
    }
    
    // Setze den Toggle-Status basierend auf dem gespeicherten Theme
    // savedTheme === 'dark' → ergibt true oder false
    // .prop('checked', true/false) → setzt den Zustand des Switches
    $('#theme-toggle').prop('checked', savedTheme === 'dark');

});