function confirmLogout(event) {
    if (!confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
        event.preventDefault();
    }
}