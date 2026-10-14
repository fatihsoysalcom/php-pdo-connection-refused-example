<?php

// This example simulates a database connection attempt that will likely fail
// with a "Connection Refused" error, similar to SQLSTATE[HY000] [2002]
// discussed in the article. This typically happens when the database server
// is not running or is inaccessible at the specified host and port.

// --- Database Connection Parameters (deliberately incorrect to force an error) ---
// Using a non-standard port (e.g., 9999) is highly likely to cause a "Connection Refused" error
// if no database server is listening on that port.
$dbHost = '127.0.0.1';
$dbPort = '9999'; // A port unlikely to have a database server running
$dbName = 'non_existent_db';
$dbUser = 'wrong_user';
$dbPass = 'wrong_password';

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

echo "Attempting to connect to database...\n";
echo "DSN: {$dsn}\n";
echo "User: {$dbUser}\n\n";

try {
    // Attempt to establish a PDO connection
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    echo "Database connection successful!\n";
    // In a real application, you would now perform database operations.
    // For this example, we expect failure, so this line should not be reached.

} catch (PDOException $e) {
    // Catch PDOException if the connection fails
    echo "Database connection failed!\n";
    echo "Error Code: " . $e->getCode() . "\n";
    echo "Error Message: " . $e->getMessage() . "\n";

    // The SQLSTATE code is often part of the message or can be accessed via getErrorInformation()
    // For connection refused, it's typically 'SQLSTATE[HY000] [2002] Connection refused'
    // Laravel often presents this message directly.
    if (strpos($e->getMessage(), 'SQLSTATE[HY000] [2002]') !== false) {
        echo "\nThis error (SQLSTATE[HY000] [2002] Connection Refused) indicates that the application\n";
        echo "could not reach the database server at the specified host and port.\n";
        echo "Possible causes:\n";
        echo "  - Database server is not running.\n";
        echo "  - Incorrect DB_HOST or DB_PORT in configuration (e.g., .env file in Laravel).\n";
        echo "  - Firewall blocking the connection.\n";
    } else {
        echo "\nThis is a different type of PDO error. Check credentials or database existence.\n";
    }

} catch (Exception $e) {
    // Catch any other unexpected exceptions
    echo "An unexpected error occurred: " . $e->getMessage() . "\n";
}

echo "\nScript finished.\n";

?>