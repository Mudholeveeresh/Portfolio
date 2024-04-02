<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $name = strip_tags(trim($_POST['name']));
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $subject = strip_tags(trim($_POST['subject']));
  $message = trim($_POST['message']);

  // Validate form data
  $errors = [];
  if (empty($name)) {
    $errors[] = "Name is required.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  if (empty($subject)) {
    $errors[] = "Subject is required.";
  }

  if (empty($message)) {
    $errors[] = "Message is required.";
  }

  $response = []; // Initialize response array

  // Send email if no validation errors
  if (empty($errors)) {

    // Optional: SMTP Configuration
    // Replace with your actual SMTP details if needed
    // $to = 'your_email@example.com';
    // $headers = "From: $name <$email>" . "\r\n";
    // $message_body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";

    // Uncomment and configure the above block if using SMTP

    // Using PHP mail function (may have limitations)
    $to = 'muttuveeresh484@gmail.com'; // Replace with your actual recipient email
    $headers = "From: $name <$email>" . "\r\n";
    $message_body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";

    if (mail($to, $subject, $message_body, $headers)) {
      $response['success'] = true;
      $response['message'] = "Your message has been sent. Thank you!";
    } else {
      // Get specific error message (optional)
      $error_message = error_get_last()['message'];
      $response['success'] = false;
      $response['message'] = "Failed to send email. Error: $error_message";
      error_log("Email sending error: $error_message", 3, '/path/to/error.log'); // Log error for debugging
    }
  } else {
    $response['success'] = false;
    $response['message'] = implode("\n", $errors); // Combine validation errors
  }

  // Respond with JSON data
  echo json_encode($response);
} else {
  http_response_code(405); // Method Not Allowed
  echo "Invalid request method.";
}
?>
