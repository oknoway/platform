<?php
function getTestEnv($key) {
	$cwd  = getcwd();
	$file = '__test_environment.json';
	$home = getenv('HOME');

	// First we look in $HOME and then in the current directory for
	// our test environment config file

	chdir($home);
	if (file_exists($file)) {
		//echo "Found $home/$file!\n";
		_getTestEnv($file,$key);
	}
	
	chdir(dirname(__FILE__));
	if (file_exists($file)) {
		//echo "Found " . dirname(__FILE__) . "$file!\n";
		_getTestEnv($file,$key);
	}

	return getenv($key);
}

function _getTestEnv($filename, $key) {
		$environment = json_decode(file_get_contents($filename),true);
		if (isset($environment[$key])) {
			return $environment[$key];
		} else {
			return false;
		}
}
?>
