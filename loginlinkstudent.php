<?php
session_start();
?>

<?php

// Log function
function logEvent($message) {
    $logFile = 'log.txt';
    $timestamp = date("Y-m-d H:i:s");
    $logMessage = "[{$timestamp}] {$message}\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
// Function to sanitize and validate input
function sanitizeInput($input) {
    // Trim leading and trailing whitespaces
    $input = trim($input);
    // Use htmlspecialchars to prevent XSS attacks
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

$x = isset($_POST["sid"]) ? sanitizeInput($_POST["sid"]) : "";
$y = hash('sha256', isset($_POST["pass"]) ? $_POST["pass"] : "");


// Define constants for account lockout
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_DURATION', 60); // in seconds

include( "database.php" );
//searching login id and password entered in $x & $y
$sql = "SELECT * FROM studenttable WHERE Eid=? AND Pass=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('ss', $x, $y);
    $stmt->execute();
    $result = $stmt->get_result();

if ( $result-> num_rows > 0 )

//session create
{
    // Reset the login attempts on successful login
    $_SESSION["login_attempts"] = 0;

	if ( $row = $result->fetch_assoc() ) {
		$_SESSION[ "sidx" ] = $row[ "Eid" ];
		$_SESSION[ "fname" ] = $row[ "FName" ];
		$_SESSION[ "lname" ] = $row[ "LName" ];
		$_SESSION[ "seno" ] = $row[ "Eno" ];

        // Set a cookie for the user
        setcookie("user_id", $row["Eid"], time() + (86400 * 30), "/"); // Cookie expires in 30 days (86400 seconds per day)
        // Log successful login
        logEvent("Successful login - User: {$_SESSION['sidx']}, IP: {$_SERVER['REMOTE_ADDR']}");

	} //redirecting to welcome student page
	header( 'Location:welcomestudent.php' );

} else {
    // Increment login attempts
    $_SESSION["login_attempts"] = isset($_SESSION["login_attempts"]) ? ($_SESSION["login_attempts"] + 1) : 1;
    // Log failed login attempt
    logEvent("Failed login attempt - User: $x, IP: {$_SERVER['REMOTE_ADDR']}");

    // Check if the maximum login attempts are reached
    if ($_SESSION["login_attempts"] >= MAX_LOGIN_ATTEMPTS) {
        // Lock the account for a specific duration
        $_SESSION["account_locked"] = time() + LOCKOUT_DURATION;
       
        // Log account lockout
        logEvent("Account locked - User: $x, IP: {$_SERVER['REMOTE_ADDR']}");
       
        echo "<h3><span style='color:red;'>Account is locked due to too many failed login attempts. Please try again later.</span></h3>";
    } else {
	//error message if SQL query fails/
	echo $y;
	echo "<h3><span style='color:red; '>Invalid Student ID & Password. Page Will redirect to Login Page after 2 seconds </span></h3>";
// 	header( "refresh:3;url=studentlogin.php" );
}
//close the connection
$connect->close();
}
// Check if the account is locked
if (isset($_SESSION["account_locked"]) && $_SESSION["account_locked"] > time()) {
    echo "<h3><span style='color:red;'>Account is locked due to too many failed login attempts. Please try again later.</span></h3>";
} else {
    // Display the login form
    // ...
}
?>
?>
