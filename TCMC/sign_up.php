<?php
session_start();
include('dbconnect.php');
?>
<!doctype html>
<head>
    <title> Townsville  </title>
    <meta charset="utf-8"/>
    <script src="http://use.edgefonts.net/crimson-text:n4,i4,n7,i7,n6,i6:all;tangerine:n4,n7:all.js"></script>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body>

<?php 
include("header.php")
?>
<?php
include("nav.php")
?>


<div id="content">
    <?php
    if (isset($_SESSION['email'])) {
        echo "Already logged in!" . $_SESSION['email'];
    } else if (isset($_POST['email'])) {
        if (strlen($_POST['password']) > 2 and strlen($_POST['password']) < 10){
            // Need to remove the binded params change values too '$_POST[value]'
            $sql = "INSERT INTO user_accounts (email, password, firstname, lastname, post_address, contact_number)
                VALUES ('$_POST[email]', '$_POST[password]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[post_address]', '$_POST[contact_number]')";
            $dbh->query($sql);
            $_SESSION['msg'] = 'Sign up complete! Please log in now!';
            header("Location: login.php");
        } else {
            $_SESSION['msg'] = "Password was not between 2 and 10 characters";
            header("Location: sign_up.php");
        }

    } else {
        ?>
        <div id="sign_up_form">
            <h3>Please fill out the sign up form.</h3>
            <?php echo $_SESSION['msg']; ?>
            <form id="sign_up" name="sign_up" method="post" action="sign_up.php">
                <table id="signup_table">
                    <tr><td><label for="email">Email Address</label></td><td><input type="text" id="email" name="email"></td><td>Your email will be used to log in.</td></tr>
                    <tr><td><label for="password">Password</label></td><td><input type="password" id="password" name="password"></td><td>Password needs to be between 2 - 10 Characters.</td></tr>
                    <tr><td><label for="firstname">First Name</label></td><td><input type="text" id="firstname" name="firstname"></td></tr>
                    <tr><td><label for="lastname">Last Name</label></td><td><input type="text" id="lastname" name="lastname"></td></tr>
                    <tr><td><label for="post_address">Postal Address</label></td><td><input type="text" id="post_address" name="post_address"></td></tr>
                    <tr><td><label for="contact_number">Contact Number</label></td><td><input type="text" id="contact_number" name="contact_number"></td></tr>
                    <tr><td></td><td><input type="submit" name="submit" value="submit"></td></tr>
                </table>
            </form>
        </div>
    <?php } ?>
</div>

<?php 
include("footer.php")
?>


</body>
