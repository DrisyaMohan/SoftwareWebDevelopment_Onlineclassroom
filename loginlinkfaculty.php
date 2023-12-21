<?php
session_start();

include("database.php");

define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_DURATION', 60); 


function logEvent($message) {
    $logFile = 'log.txt';
    $timestamp = date("Y-m-d H:i:s");
    $logMessage = "[{$timestamp}] {$message}\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $x = isset($_POST["fid"]) ? intval($_POST["fid"]) : 0; 
    $y = hash('sha256', isset($_POST["pass"]) ? $_POST["pass"] : "");


    $sql = "SELECT * FROM facutlytable WHERE FID=? AND Pass=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('ss', $x, $y);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result-> num_rows > 0) {
        
        $_SESSION["login_attempts"] = 0;

       
        if ($row = $result->fetch_assoc()) {
            $_SESSION["fidx"] = $row["FID"];
            $_SESSION["fname"] = $row["FName"];

            
            logEvent("Successful login - Faculty ID: {$_SESSION['fidx']}, Name: {$_SESSION['fname']}");

        }
       
        
       setcookie("user_id", $row["Eid"], time() + (86400 * 30), "/"); 

        // Redirecting to welcome faculty page
        header('Location: welcomefaculty.php');
    } else {
       
        $_SESSION["login_attempts"] = isset($_SESSION["login_attempts"]) ? ($_SESSION["login_attempts"] + 1) : 1;

        
        if ($_SESSION["login_attempts"] >= MAX_LOGIN_ATTEMPTS) {
            
            $_SESSION["account_locked"] = time() + LOCKOUT_DURATION;
            
            
            logEvent("Account locked - Faculty ID: $x");

            echo "<h3><span style='color:red;'>Account is locked due to too many failed login attempts. Please try again later.</span></h3>";
            
        
        } else {

            // Log failed login attempt
            logEvent("Failed login attempt - Faculty ID: $x");

        // Error message if SQL query fails
        echo $y;
        echo "<h3><span style='color:red;'>Invalid Faculty ID & Password. Page will redirect to Login Page after 3 seconds</span></h3>";
       // header("refresh:3;url=facultylogin.php");
        }
    }
    }
}
    catch (Exception $e) {

        // Log the error
    logEvent("An error occurred: " . $e->getMessage());

        echo "<h3><span style='color:red;'>An error occurred: " . $e->getMessage() . "</span></h3>";
    } finally {
        // Close the statement
        if (isset($stmt)) {
            $stmt->close();
        }
    }
    // Close the connection
    $connect->close();

// Check if the account is locked
if (isset($_SESSION["account_locked"]) && $_SESSION["account_locked"] > time()) {
    
   // Log account lockout
    logEvent("Account is still locked due to too many failed login attempts.");
     
    echo "<h3><span style='color:red;'>Account is locked due to too many failed login attempts. Please try again later.</span></h3>";
} else {
    // Display the login form
    // ...
}
?>
?>