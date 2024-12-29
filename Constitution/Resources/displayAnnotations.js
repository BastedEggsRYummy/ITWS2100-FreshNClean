function displayAnnotations(data){
  // Grab each article
  data.constitution.articles.forEach(article => {
    // Find place to paste article comment
    const articleDiv = document.getElementById('article' + article.article_number); 
    const articleAnnotation = document.createElement('p');
    articleAnnotation.classList.add('annotations'); 
    // Create and paste annotation
    articleAnnotation.innerText = article.annotation;
    articleDiv.appendChild(articleAnnotation);
  });
  // Grab each amendment
  data.constitution.amendments.forEach(amendment => {
    // Find place to paste amendment comment
    const amendmentDiv = document.getElementById('amendment' + amendment.amendment_number); 
    const amendmentAnnotation = document.createElement('p');
    amendmentAnnotation.classList.add('annotations'); 
    // Create and paste annotation
    amendmentAnnotation.innerText = amendment.annotation;
    amendmentDiv.appendChild(amendmentAnnotation);
  });
}



// Second xml request to parse the file data
const xhr2 = new XMLHttpRequest();
xhr2.open('GET', './Resources/Annotations.json', true);
xhr2.onreadystatechange = function () {
  if (xhr2.readyState === 4 && xhr2.status === 200) {
      const data = JSON.parse(xhr2.responseText);
      displayAnnotations(data);
  }
};
xhr2.send();


// *********  https://docs.google.com/document/d/1ZaY20Jc3ll6cFFc8jjZv8sHzspzFhxjFoPArkNaNwkk/edit  ************
// All credit for the annotations goes to group members who wrote on this document. Document history should show who worked on what alongside their README's