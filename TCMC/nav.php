<?php
session_start();
?>
<div id= "topnavigation">
    <a href="index.php">Home</a>
    <a href="events.php">Events</a>
    <a href="bulletin.php">Bulletin Board</a>
    <a href="musos.php">Musos</a>
    <a href="about.php">About</a>
    <span id="accountnav">
    <?php if(isset($_SESSION['email'])) {
        echo "<a href=\"user_control_panel.php?editing_mode=bulletin\">User Control Panel</a>";
        echo "<a href=\"logout.php\">Logout</a>";
    } else {
        echo "<a href=\"login.php\">Login</a>";
    } ?>
    <?php if(!isset($_SESSION['email'])) {
        echo "<a href=\"sign_up.php\">Sign Up!</a>";
    } ?>
    </span>
</div>