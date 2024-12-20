// sidebar.js

document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggle = document.querySelector('.dropdown .dropbtn');
    const dropdownContent = document.querySelector('.dropdown .dropdown-content');

    dropdownToggle.addEventListener('click', function (e) {
        e.preventDefault();
        dropdownContent.classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
        if (!dropdownToggle.contains(e.target) && !dropdownContent.contains(e.target)) {
            dropdownContent.classList.remove('show');
        }
    });
});
