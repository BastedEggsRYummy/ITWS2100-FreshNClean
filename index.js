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
