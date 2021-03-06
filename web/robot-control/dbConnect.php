<?php

function get_db() {
	$db = NULL;
	try {
		// default Heroku Postgres configuration URL
		$databaseUrl = getenv('DATABASE_URL');

		// Local database URL
		if (!isset($databaseUrl) || empty($databaseUrl)) {
			require("databaseUrl.php");
		}

		// Get the various parts of the DB Connection from the URL
		$dbopts = parse_url($databaseUrl);
		$dbHost = $dbopts["host"];
		$dbPort = $dbopts["port"];
		$dbUser = $dbopts["user"];
		$dbPassword = $dbopts["pass"];
		$dbName = ltrim($dbopts["path"],'/');
		// Create the PDO connection
		$db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
		// this line makes PDO give us an exception when there are problems, and can be very helpful in debugging!
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (PDOException $ex) {
		// If this were in production, you would not want to echo
		// the details of the exception.
		echo "Error connecting to DB. Details: $ex";
		die();
	}
	return $db;
}

?>