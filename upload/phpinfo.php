<?php

// Show all information, defaults to INFO_ALL
phpinfo();
$temp = sys_get_temp_dir();		
		echo '<pre>';			
			print_r($temp);
			echo '</pre>';
			die;
?>