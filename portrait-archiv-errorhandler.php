<?php 
 
	if (isset($error) && isset($message)) {
		pawps_displayError($error, $message);
	} else if (isset($error)) {
		pawps_displayError($error);
	} else if (isset($message)) {
		pawps_displayError(null, $message);
	} 
	
?>