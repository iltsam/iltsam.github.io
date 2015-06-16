<?php include("dbconnect.php");
// Authentication example for CP2010 - Lindsay Ward, IT@JCU

// every page we want to use sessions with should start with this function call
session_start();
// Report all PHP errors
error_reporting(E_ALL);

/* This code controls access to a page by checking to see if the username exists in the session.
 Since this variable is set by the login script, it means the user is logged in.
 if the username session variable is empty, it checks if it came from a form and then logs in
 by setting the session variable username.
 If the user is not logged in and the username is not valid, it redirects the browser to the login page.


*/

// this is the simple check if we're NOT logged in - if we ARE, do nothing (there's no "else")
if (!isset($_SESSION['email'])){
    // check if we came from a form (with username) - this could be more robust (check for our specific login form)
    if (isset($_POST['email']))
    {
        // SQL query to compare login details to database to see if an
        $sql = "SELECT * FROM user_accounts WHERE email = :em AND password = :pw";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':em', $_POST['email']);
        $stmt->bindParam(':pw', $_POST['password']);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $results = $results[0];

        // Check that the query returned an account
        if ($results)
        {
            // Yes, valid credentials - set message and set session variable for logged in
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['account_id'] = $results['account_id'];
            $_SESSION['account_type'] = $results['account_type'];
            $_SESSION['msg'] = "Welcome " . $results['firstname'];

            // Generate a new session ID for a new successful login
            session_regenerate_id();

            // Redirect to user control panel
            header("Location: user_control_panel.php?editing_mode=bulletin");
        }
        else
        {
            // Incorrect details - Sets the message
            $_SESSION['msg'] = "Invalid username and/or password!";
            // redirect them to the login page, protecting our secure page
            header("Location: login.php");
            exit();
        }
    }
    else // they didn't come from a form - tell them to log in, redirecting to login page
    {
        $_SESSION['msg'] = "You must log in first";
        header("Location: login.php");
        exit();
    }
} else {
    // SQL query to compare session data to our database - To prevent existing sessions.
    $sql = "SELECT * FROM user_accounts WHERE account_id = :account_id AND email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':account_id', $_SESSION['account_id']);
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $results = $stmt->fetchAll();
    // Check that the query returned something
    if($results) {
        // Confirmed logged in session is from our website
        // Do nothing
    } else {
        // Found incorrect session data - Redirect them to log in again
        $_SESSION['msg'] = "Please log in again";
        header("Location: login.php");
        exit();
    }
}
?>