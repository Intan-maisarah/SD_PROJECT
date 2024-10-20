document.addEventListener('DOMContentLoaded', function () {
    const feedbackForm = document.getElementById('feedbackForm');
    
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function (event) {
            event.preventDefault(); 

            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'send_feedback.php', true);
            
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Thank you for your feedback!');
                    feedbackForm.reset();
                } else {
                    alert('Failed to send feedback. Please try again.');
                }
            };
            
            xhr.send(formData);
        });
    } else {
        console.error('Feedback form not found.');
    }
});
