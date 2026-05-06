// Funktion zum Neuladen des User-Bereichs in der Navigation (z.B. nach Login oder Logout)
// besser über API damit src/ nicht öffnetlich zugänglich bleibt
function reloadUserArea() {
    $('#user-area').load('/api/serviceHandler.php?module=nav&action=reloadUserArea');
}