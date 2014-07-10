<?php
// global definitions
$prefix = "/home/eggdrop/twitcher/logs/theoriginalweed.log.";

// includes
require_once 'header.html';
require_once 'functions.php';


// Check if we were given any input, otherwise initialize variables to null
$search = (isset($_GET['search']) ? $_GET['search'] : null);
$user = (isset($_GET['user']) ? $_GET['user'] : null);
$time = (isset($_GET['time']) ? $_GET['time'] : null);

if ($search) {
	if ($user=="yes") {
		$user = TRUE;
	}
	if ($time=="yes") {
		$time = TRUE;
	}
weedSearch($search,$user,$time);
require_once 'timer_footer.php';

}

require_once 'footer.html';

?>