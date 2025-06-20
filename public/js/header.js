document.addEventListener('DOMContentLoaded', function () {
    const profileButton = document.getElementById('profileButton');
    const dropdown = document.getElementById('dropdown');

    profileButton.addEventListener('click', function () {
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', function (event) {
        if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    const logoutButton = document.getElementById('logoutButton');
    const logoutPopup = document.getElementById('logoutPopup');
    const popupOverlay = document.getElementById('popupOverlay');
    const confirmLogout = document.getElementById('confirmLogout');
    const cancelLogout = document.getElementById('cancelLogout');

    logoutButton.addEventListener('click', function () {
        logoutPopup.style.display = 'block';
        popupOverlay.style.display = 'block';
    });

    cancelLogout.addEventListener('click', function () {
        logoutPopup.style.display = 'none';
        popupOverlay.style.display = 'none';
    });

    confirmLogout.addEventListener('click', function () {
        document.getElementById('logoutForm').submit();
    });
});

