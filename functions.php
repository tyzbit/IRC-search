<?php

// format today's date like 18062014 (for June 18, 2014)
function formatToday() {
	date_default_timezone_set('America/New_York');
	return date('dmY', time());
}

// format a date in 18-06-2014 notation to Jun18 notation
function reverseFormat($input) {

	return date('F d', strtotime($input));
}

// decide how to handle the users request and call the search
function weedSearch($userinput, $user = NULL, $time = NULL) {
// allow $prefix to be passed to sub-functions
global $prefix, $prefix;

// filter user inputted search string
$searchstring = filter_var($userinput, FILTER_SANITIZE_STRING);

// if this is an all time search, find all files that have the prefix
if ($time) {
	$files = glob($prefix . "*");
}
// otherwise, manually set the files array to just today
else {
	$files[0] = $prefix . formatToday();
}

// usernames in the log are <username>, so search for that
if ($user) {
	$searchstring = "\<.*" . $searchstring . ".*\>";
}

// search the files for matches
$results = searchFile($files, $searchstring, $time);

// process and parse the results
processResults($results, $time);
}

// search for a string in a variable number of files
function searchFile($files, $searchstring, $time = NULL) {

	// $results will hold all of our results
	$results = array();
	// Iterate through files given and look for the search string
	foreach ($files as $file) {
		// (re)initiate $matches
		$matches = array();
		// get a handle to the file
		$handle = @fopen($file, "r");
		// we opened the file successfully!
		if ($handle) {
				// while there is still file to be read
				while (!feof($handle))	{
					// put the next line in $buffer
					$buffer = stream_get_line($handle, 1000, "\n");
					// search $buffer for $search string, if it's found push it onto $matches
					if(preg_match("@" . $searchstring . "@i", $buffer)) {
						// put this match for this file onto the $matches array
						array_push($matches, $buffer);
					}
				}
				fclose($handle);
				// if there were matches, add a new array element to $results with the $matches array
				if ($matches) {
					$results[$file]=$matches;
					// clear out the $matches array
					unset($matches);
				}
		}
		// there was a problem opening the file
		else {
			return "<b>ERROR WHEN OPENING FILE " . $file . "</b>";
		}
	}
	// return what we found
	return $results;
}

// format and display the results of searchFile for the internet
function processResults($results, $time = NULL) {
	// begin the results div
	echo "<div id='results'>";
		foreach ($results as $path => $match) {
			// generate those date headers
			if ($time) {
				// cut off all but the last 8 characters of the file path, this is the DMY
				$date = substr($path, -8);
				// add some dashes
				$date = preg_replace("/^1?(\d{2})(\d{2})(\d{4})$/", "$1-$2-$3", $date);
				// format the date as Jun18
				$date = reverseFormat($date);
				echo "<div id='day'>" . $date;
			}
			else {
				echo "<div id='day'>Today's Results";
			}
		//close the header <div>
			echo "</div>";
			// spit out the logs
			foreach ($match as $log) {
					echo "<div id='log'>";
					// elsewise, print the result
					echo htmlspecialchars($log) . "<br />";
					echo "</div>";
					// closing divs for dates
				if (!$results) {
					echo "no results";
				}
			}
		}
	// close the results <div>
	echo "</div>";
}

?>
