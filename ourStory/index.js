document.getElementById('form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const response = await fetch('send_email.php', {
        method: 'POST',
        body: formData
    });
    const notifContainer = document.getElementById('notificationContainer');
    if (response.ok) {
        document.getElementById('form').reset();
        notifContainer.style.backgroundColor = '#B8F2B3';
    }
    else {
        notifContainer.style.backgroundColor = '#FF9E9E';
    }
    const responseText = await response.text();
    const notif = document.getElementById('notification');
    notif.innerHTML = responseText;
    
    // display notification
    if (notifContainer.classList.contains('disappear')){
        // remove class
        notifContainer.classList.remove('disappear');
        // restart animation if notification is still displayed
        notifContainer.offsetHeight;  // triggers reflow and does the line above before continuing
    }
    notifContainer.style.display = 'block';
    setTimeout(()=>{
        notifContainer.classList.add('disappear');
    }, 5000);
})