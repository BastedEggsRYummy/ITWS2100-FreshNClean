document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.entry').forEach(entry => {
      entry.addEventListener('click', function() {
          const details = entry.getAttribute('data-details').split(', ');

          // Generate HTML content dynamically
          let additional = "";
          for(let i = 5; i < details.length; i++){
              additional += details[i];
              additional += ", ";
          }
          additional = additional.slice(0, -2);
          const popupInfoContent = `
              <p><strong># of Loads:</strong> ${details[0]}</p>
              <p><strong>Wash Cycle:</strong> ${details[1]}</p>
              <p><strong>Wash Temperature:</strong> ${details[2]}</p>
              <p><strong>Dry Time:</strong> ${details[3]}</p>
              <p><strong>Dry Temperature:</strong> ${details[4]}</p>
              <p><strong>Additional Instructions:</strong><br> ${additional}</p>
          `;

          // Insert the content into the popup container
          document.getElementById('popup-info').innerHTML = popupInfoContent;

          // Display the popup
          document.getElementById('popup').style.display = 'flex';
      });
  });

  // Close button functionality
  document.querySelector('.close').onclick = function() {
      document.getElementById('popup').style.display = 'none';
  };

  // Close the popup when clicking outside the content
  window.onclick = function(event) {
      if (event.target == document.getElementById('popup')) {
      document.getElementById('popup').style.display = 'none';
      }
  };
});