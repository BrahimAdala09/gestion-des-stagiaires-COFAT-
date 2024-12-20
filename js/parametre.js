// js/sidebar.js

document.addEventListener("DOMContentLoaded", function() {
    // Get all dropdown elements
    var dropdowns = document.querySelectorAll(".dropdown");
    
    // Add event listener to each dropdown
    dropdowns.forEach(function(dropdown) {
        var dropbtn = dropdown.querySelector(".dropbtn");
        var dropdownContent = dropdown.querySelector(".dropdown-content");

        dropbtn.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Toggle dropdown visibility
            dropdown.classList.toggle("show");

            // Close other dropdowns
            dropdowns.forEach(function(otherDropdown) {
                if (otherDropdown !== dropdown) {
                    otherDropdown.classList.remove("show");
                }
            });
        });
    });

    // Close dropdowns when clicking outside
    window.addEventListener("click", function(e) {
        if (!e.target.matches('.dropbtn')) {
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.remove("show");
            });
        }
    });
});
