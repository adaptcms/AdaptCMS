<?php

$params = '{"name_of_page":"Contact Us","captcha_for_guests":"1","submissions_sent_to":"charliepage88+contact@gmail.com","email_subject":"Contact Form Submission","success_message":"Your email has been sent. We will respond within 5-7 business days."}';

$config = json_decode($params, true);
Configure::write('ContactForm', $config );
?>