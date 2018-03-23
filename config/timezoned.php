<?php

return [
	/* 
	 * Callback that will hold user's timezone used to calculated timestamps.
	 * Generally this should be logged in user's timezone.
	 */
	'user_timezone' => function() { 
    $user = \Auth::user();

    return !empty($user->settings) && !empty($user->settings->timezone) ? $user->settings->timezone : null;
  }
];