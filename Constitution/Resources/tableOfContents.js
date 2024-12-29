document.querySelector("#tocbutton").addEventListener("click", toggleTOC);

function toggleTOC(e){
   let toc = document.querySelector("#toc");
   //check if active or not
   if(toc.classList.contains("active")){
      toc.className = ("unactive");
   } else {
      toc.className = ("active");
   }
   
}