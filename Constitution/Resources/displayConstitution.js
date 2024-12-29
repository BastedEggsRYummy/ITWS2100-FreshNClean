function displayArticles(data) {
  const articleDiv = document.getElementById('articleContent'); // This is the main container for the articles

  // Loop through the articles
  data.constitution.articles.forEach(article => {
      // Create a div for each article
      const articleContainer = document.createElement('div');
      articleContainer.classList.add('container'); 
      articleContainer.id = 'article' + article.article_number;
      
      // Assign article numbers
      const articleHeading = document.createElement('h1');
      const articleText = document.createElement('p');
      articleHeading.innerText = `Article ${article.article_number}: `;
      articleText.innerText = `${article.text}`;
      articleContainer.appendChild(articleHeading);
      articleContainer.appendChild(articleText);

      // Loop through the sections of each article
      article.sections.forEach(section => {
          // Section Header
          const sectionHeading = document.createElement('h2');
          sectionHeading.innerText = `Section ${section.section_number}:`;

          if(section.section_number == 0){
            return;
          }
          
          // Section Text
          const sectionText = document.createElement('p');
          sectionText.innerText = section.text;

          // Append section heading and text to article container
          articleContainer.appendChild(sectionHeading);
          articleContainer.appendChild(sectionText);
      });
      const sectionHeading = document.createElement('h2');
      sectionHeading.classList.add("showText");
      sectionHeading.innerText = `Click for Summary/Notes ▾`;
      articleContainer.appendChild(sectionHeading);
      // Append the whole article container to the main content div
      articleContainer.addEventListener('click', function() {
        this.classList.toggle('displayAnnotations'); 
        if (this.getElementsByClassName("showText")[0].innerHTML == `Click for Summary/Notes ▾`){
          this.getElementsByClassName("showText")[0].innerHTML = `Click for Summary/Notes ▴`;
        } else {
          this.getElementsByClassName("showText")[0].innerHTML = `Click for Summary/Notes ▾`;
        }
      });
      articleDiv.appendChild(articleContainer);
      
  });
}
function displayAmendments(data) {
  const articleDiv = document.getElementById('amendmentContent'); // This is the main container for the articles

  // Loop through the articles
  data.constitution.amendments.forEach(amendment => {
      // Create a div for each article
      const amendmentContainer = document.createElement('div');
      amendmentContainer.classList.add('container'); 
      amendmentContainer.id = 'amendment' + amendment.amendment_number;
      
      // Assign article numbers
      const amendmentHeading = document.createElement('h1');
      const amendmentText = document.createElement('p');
      amendmentHeading.innerText = `Amendment ${amendment.amendment_number}: `;
      amendmentText.innerText = `${amendment.text}`;
      amendmentContainer.appendChild(amendmentHeading);
      amendmentContainer.appendChild(amendmentText);

      // Loop through the sections of each article
      amendment.sections.forEach(section => {
          // Section Header
          if(section.section_number == 0){
            return;
          }
          const sectionHeading = document.createElement('h2');
          sectionHeading.innerText = `Section ${section.section_number}:`;
          
          // Section Text
          const sectionText = document.createElement('p');
          sectionText.innerText = section.text;

          // Append section heading and text to article container
          amendmentContainer.appendChild(sectionHeading);
          amendmentContainer.appendChild(sectionText);
      });
      const sectionHeading = document.createElement('h2');
      sectionHeading.classList.add("showText");
      sectionHeading.innerText = `Click for Summary/Notes ▾`;
      amendmentContainer.appendChild(sectionHeading);
      // Append the whole article container to the main content div
      amendmentContainer.addEventListener('click', function() {
        this.classList.toggle('displayAnnotations'); 
        if (this.getElementsByClassName("showText")[0].innerHTML == `Click for Summary/Notes ▾`){
          this.getElementsByClassName("showText")[0].innerHTML = `Click for Summary/Notes ▴`;
        } else {
          this.getElementsByClassName("showText")[0].innerHTML = `Click for Summary/Notes ▾`;
        }
      });
      articleDiv.appendChild(amendmentContainer);
  });
}
// AJAX request to get the JSON data
const xhr = new XMLHttpRequest();
xhr.open('GET', './Resources/Constitution.json', true);
xhr.onreadystatechange = function () {
  if (xhr.readyState === 4 && xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      displayArticles(data);
      displayAmendments(data);
  }
};
xhr.send();
