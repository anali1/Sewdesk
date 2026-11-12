
<?php

// =============================================
// SEWDEX CONTACT FORM - PHP EMAIL FORM
// =============================================

// Receiving email address
$receiving_email_address = 'analitech200@gmail.com';

// Load PHP Email Form library
$php_email_form = '../assets/vendor/php-email-form/php-email-form.php';

if (file_exists($php_email_form)) {
    include($php_email_form);
} else {
    http_response_code(500);
    die('Unable to load the PHP Email Form Library.');
}


// =============================================
// VALIDATE REQUEST
// =============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Invalid request method.');
}


// Retrieve and sanitize submitted information
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');


// Validate required fields
if (
    $name === '' ||
    $email === '' ||
    $subject === '' ||
    $message === ''
) {
    http_response_code(400);
    die('Please complete all required fields.');
}


// Validate email address
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die('Please provide a valid email address.');
}


// Prevent excessively long input
if (
    strlen($name) > 150 ||
    strlen($email) > 254 ||
    strlen($subject) > 200 ||
    strlen($message) > 10000
) {
    http_response_code(400);
    die('One or more fields exceed the allowed length.');
}


// =============================================
// INITIALIZE EMAIL FORM
// =============================================

$contact = new PHP_Email_Form;

$contact->ajax = true;

$contact->to = $receiving_email_address;

// Visitor details
$contact->from_name = $name;
$contact->from_email = $email;

// Email subject
$contact->subject = 'SewDex Website Enquiry: ' . $subject;


// =============================================
// FORMAT EMAIL CONTENT
// =============================================

$contact->add_message('SEWDEX WEBSITE ENQUIRY', 'New Enquiry');

$contact->add_message($name, 'Customer Name');

$contact->add_message($email, 'Customer Email');

$contact->add_message($subject, 'Enquiry Subject');

$contact->add_message($message, 'Message', 10);


// =============================================
// SEND EMAIL
// =============================================

echo $contact->send();

?>
