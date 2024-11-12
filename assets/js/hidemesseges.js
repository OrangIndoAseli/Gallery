// hideMessage.js
window.addEventListener('load', function() {
    // Select the message element
    const messageDiv = document.querySelector('.message');
    
    // Check if the message element exists
    if (messageDiv) {
        // Set a timeout to hide the message after 3 seconds (3000 ms)
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }
});
