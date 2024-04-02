document.addEventListener("DOMContentLoaded", function() {
  var form = document.querySelector('.php-email-form');
  form.addEventListener('submit', function(e) {
    e.preventDefault();

    var name = form.querySelector('#name').value;
    var email = form.querySelector('#email').value;
    var subject = form.querySelector('#subject').value;
    var message = form.querySelector('textarea[name=message]').value;

    // Simple validation at client's end
    // Check if fields are empty (More validation can be added)
    if (!name || !email || !subject || !message) {
      displayError("Please fill all the fields.");
      return;
    }

    // AJAX request to server
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'forms/contact.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200 && xhr.responseText === "OK") {
        displaySuccess("Your message has been sent. Thank you!");
      } else {
        // Handle other errors
        if (xhr.status !== 200) {
          displayError("Failed to send email. Error: Server error (" + xhr.status + ")");
        } else {
          displayError("Failed to send email. Error: Unexpected response (" + xhr.responseText + ")");
        }
      }
    };
    xhr.send(encodeURI('name=' + name + '&email=' + email + '&subject=' + subject + '&message=' + message));
  });

  function displayError(message) {
    var errorMessage = form.querySelector('.error-message');
    errorMessage.textContent = message; // Use textContent to prevent XSS
    errorMessage.style.display = 'block';
  }

  function displaySuccess(message) {
    var sentMessage = form.querySelector('.sent-message');
    sentMessage.textContent = message; // Use textContent to prevent XSS
    sentMessage.style.display = 'block';
  }
});
