document.addEventListener('DOMContentLoaded', function() {
    // Select all star rating containers
    const ratingContainers = document.querySelectorAll('.star-rating');

    ratingContainers.forEach(container => {
        // Get the rating from the data-rating attribute
        const rating = parseInt(container.getAttribute('data-rating'));

        // Clear any existing content
        container.innerHTML = '';

        // Create 5 stars
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('span');
            star.classList.add('star');
            star.innerHTML = '★';

            // Add filled class to stars up to the rating
            if (i <= rating) {
                star.classList.add('filled');
            }

            container.appendChild(star);
        }
    });
});

function scrollToSection(sectionId) {
    // Get the section with the given ID
    var section = document.getElementById(sectionId);

    // Get the height of the header
    var headerHeight = document.getElementById('header').offsetHeight;

    // Scroll to the section, offset by the header's height
    window.scrollTo({
        top: section.offsetTop - headerHeight,  // Adjust scroll position
        behavior: 'smooth'
    });
}

let mybutton = document.getElementById("scrollToTopBtn");

// Show the button when the user scrolls down 20px from the top of the document
window.onscroll = function() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block"; // Show the button
    } else {
        mybutton.style.display = "none"; // Hide the button
    }
};

// When the user clicks the button, scroll to the top of the document
function scrollToTop() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE, and Opera
}