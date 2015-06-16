<?php session_start();
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
         echo "<a href='logout.php'>Logout!</a>";
     } else {
        if(isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
        }
        $_SESSION['editing_mode'] = "bulletin";
     ?>
     <div id="login_form">
         <h3>Login</h3>
        <form id="login" name="login" method="post" action="authenticate.php">
            <table name="loginTable">
                <tr>
                    <td><label for="username">Email Address:</label></td>
                    <td><input type="text" name="email" id="email"></td>
                </tr>
                <tr>
                    <td><label for="password">Password:</label></td>
                    <td><input type="password" name="password" id="password"></td>
                </tr>
                <tr><td></td><td><input type="submit" name="submit" value="Login"></td></tr>
            </table>
        </form>
     </div>
     <?php } ?>
</div>

<?php 
include("footer.php")
?>

    </body>
