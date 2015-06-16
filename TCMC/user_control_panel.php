<?php
include("dbconnect.php");
session_start();
require("authenticate.php");
require("wideimage/WideImage.php");

$defaultImagePath = "/images/musos/"
?>

<!DOCTYPE html>
<html lang="en"/>

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
<div id="user_cp_nav">
    <a href="user_control_panel.php?editing_mode=bulletin">Bulletin Editing / Creation</a>
    <?php
    if($_SESSION['account_type'] == "admin" || $_SESSION['account_type'] == "paid_user") {
        echo "<a href='user_control_panel.php?editing_mode=artist'>Artist Editing / Creation</a>";
    }
    ?>
    <a href="user_control_panel.php?editing_mode=events">Events Editing / Creation</a>
    <?php

    if ($_SESSION['account_type'] == 'admin') {
        echo "<a href=\"user_control_panel.php?editing_mode=user_account\">User Account Editing</a>";
    }
    if ($_SESSION['account_type'] == 'user') {
        echo "<a href=\"user_control_panel.php?editing_mode=user_upgrade\">Upgrade Now!</a>";
    }

    ?>
</div>

<div id="content">
    <?php
    // Sets Date to now then adds 30 days for the expiry date..
    $todaysDate = new DateTime("now");
    $expiryDate = $todaysDate->add(new DateInterval("P30D"));
    // Converts DateTime to string for adding into DB
    $expireString = $expiryDate->format('Y-m-d'); // Used to add into DB for bulletin
    // Converts string back to DateTime
    $date2 = new DateTime($expireString);

    // Check for expired bulletins
    $sql = "SELECT * FROM bulletins";
    foreach ($dbh->query($sql) as $row) {
        $checkExpired = new DateTime($row['date']);
        $compareDate = new DateTime("now");
        if ($checkExpired < $compareDate) {
            $sql = "DELETE FROM bulletins WHERE id = '$row[id]'";
            $dbh->exec($sql);
        }
    }
    // Check for expired Events
    $sql = "SELECT * FROM events";
    foreach ($dbh->query($sql) as $row) {
        $checkExpired = new DateTime($row['expiry_date']);
        $compareDate = new DateTime("now");
        if ($checkExpired < $compareDate) {
            $sql = "DELETE FROM events WHERE event_id = '$row[event_id]'";
            $dbh->exec($sql);
        }
    }

    // If else Statement for showing each editor
    if ($_GET['editing_mode'] == "bulletin"){
        $_SESSION['coming_from'] = "bulletins";


        if ($_SESSION['account_type'] == "admin") {
            $sql = "SELECT * FROM bulletins";
        } else {
            // Displays the bulletins that belong to you.
            $sql = "SELECT * FROM bulletins WHERE account_id = '$_SESSION[account_id]'";
        }

        $results = $dbh->query($sql);

        ?>
        <h3>Bulletin Editing</h3>
        <p>Bulletins expire after 30 days since creation</p>
        <table id="bulletin_details">
            <tr>
                <th>Name</th>
                <th>Details</th>
            </tr>
            <?php
            foreach ($results as $row){
                ?>
            <form id="bulletin_details" name="bulletin_details" method="post" action="dbprocess.php">
                <?php
                echo "<tr><td><input type='text' id='bulletin_name' name='name' value='$row[name]'></td>
                        <td><textarea id='bulletin_description' name='description'>$row[description]</textarea></td>
                        <td><input type='text' id='expiry_date' name='expiry_date' readonly value='$row[date]'></td>
                        <td><input type='submit' name='update_button' value='Update'></td>
                        <td><input id='delete_button' type='submit' name='delete_button' value='Delete'></td>
                        <td><input type='hidden' name='id' value='$row[id]'></td></tr>";
                ?>
            </form>
            <?php
            }
            ?>
            <form id="create_bulletin" name="create_bulletin" method="post" action="dbprocess.php" enctype="multipart/form-data">
                <table>
                <tr><th><h3>Create New Bulletin</h3></th></tr>
                <tr><th>Name</th><th>Description</th><th>Expiry Date</th></tr>
                <tr><td><input type='text' name='name'></td>
                    <td><textarea name='description'></textarea></td>
                    <td><input type='text' id='expiry_date' name='expiry_date' readonly value=<?php echo $expireString; ?>></td>
                    <td><input type='file' name='imagefile' id='imagefile'/></td>
                    <td><input type='submit' name='create_bulletin' value='Create'></td></tr>
                </table>
            </form>
        </table>

<?php } else if ($_GET['editing_mode'] == "artist"){
        $_SESSION['coming_from'] = "artist";
        if($_SESSION['account_type'] == "paid_user" || $_SESSION['account_type'] == "admin") {
            $sql = "SELECT * FROM artist_details";
        } else {
            header("Location: user_control_panel?editing_mode=bulletin");
        }

        ?>
        <h3>Artist Editing</h3>
            <table id="artist_details">
                <tr><th>Name</th><th>Genre</th><th>Details</th><th>Artist Picture</th></tr>
                <?php
                foreach ($dbh->query($sql) as $row){
                    ?>
                    <form id="deleteForm" name="deleteForm" method="post" action="dbprocess.php" enctype="multipart/form-data">
                        <?php
                        echo "<tr><td><input type='text' name='name' value='$row[name]' /></td>
                                <td><input type='text' name='genre' value='$row[genre]' /></td>
                                <td><textarea name='description' id='description'>$row[description]</textarea></td>
                                <td><textarea name='more_info' id='more_info'>$row[more_info]</textarea></td>";
                        echo "<input type='hidden' name='artist_id' value='$row[artist_id]' />";
                        ?>
                        <td><input type="submit" name="update_button" value="Update" />
                            <input id='delete_button' type="submit" name="delete_button" value="Delete" class="deleteButton" /></td></tr>
                    </form>
                <?php
                }
                ?>
            </table>
            <br/>
            <br/>
            <h3>Artist Creation</h3>
            <form id="insert" method="post" action="dbprocess.php" enctype="multipart/form-data">
                <fieldset>
                    <table>
                    <tr><td><label for="name">Name: </label></td>
                        <td><input id="name" name="name" type="text"></td></tr>
                        <tr><td><label for="genre">Genre: </label></td>
                            <td><input id="genre" name="genre" type="text"></td></tr>
                        <tr><td><label for="description">Short Description: </label></td>
                            <td><textarea id="description" name="description" type="text"></textarea></td></tr>
                        <tr><td><label for="more_info">Detailed Description: </label></td>
                            <td><textarea id="more_info" name="more_info" type="text"></textarea></td></tr>
                        <tr><td><label for="imagefile">Upload Image</label></td><td><input type='file' name='imagefile' id='imagefile'/></td></tr>
                        <tr><td><input type="submit" name="create_button" value="Create"></td></tr>
                    </table>
                </fieldset>
            </form>
<?php } else if ($_GET['editing_mode'] == "events"){
    $_SESSION['coming_from'] = "events";

    // TODO - Display SELECT * with account_type == admin
    // Displays the bulletins that belong to you.
        if($_SESSION['account_type'] == "admin") {
            $sql = "SELECT * FROM events";
        } else {
            $sql = "SELECT * FROM events WHERE creator_id = '$_SESSION[account_id]'";
        }


    ?>
    <h3>Events Editing</h3>
    <table id="event_details">
        <tr>
            <th>Event Name</th>
            <th>Event Details</th>
            <th>Event Expiry Date</th>
        </tr>
        <?php
        foreach ($dbh->query($sql) as $row){
            ?>
            <form id="event_details" name="event_details" method="post" action="dbprocess.php">
                <?php
                echo "<tr><td><input type='text' id='event_name' name='event_name' value='$row[event_name]'></td>
                        <td><textarea id='event_description' name='event_description'>$row[event_description]</textarea></td>
                        <td><input type='text' name='expiry_date' value='$row[expiry_date]'></td>
                        <td><input type='submit' name='update_button' value='Update'></td>
                        <td><input id='delete_button' type='submit' name='delete_button' value='Delete'></td>
                        <td><input type='hidden' name='event_id' value='$row[event_id]'></td>
                        <td><input type='hidden' name='creator_id' value='$_SESSION[account_id]'></td></tr>";
                ?>
            </form>
        <?php
        }
        ?>
        <form id="create_event" name="create_event" method="post" action="dbprocess.php" enctype="multipart/form-data">
            <table>
                <tr><th><h3>Create New Event</h3></th></tr>
                <tr><th>Name</th><th>Description</th><th>Event Expiry Date<br/>(YEAR-MONTH-DAY)</th></tr>
                <tr><td><input type='text' name='event_name'></td>
                    <td><textarea name='event_description'></textarea></td>
                    <td><input type='text' name='expiry_date'></td>
                    <td><input type='file' name='imagefile' id='imagefile'/></td>
                    <td><input type='hidden' name='creator_id' value='<?php echo $_SESSION['account_id']; ?>'></td>
                    <td><input type='submit' name='create_event' value='Create'></td></tr>
            </table>
        </form>
    </table>



<?php } else if ($_GET['editing_mode'] == "user_account"){
        $_SESSION['coming_from'] = "user_accounts";
        if ($_SESSION['account_type'] == "admin") {
            $sql = "SELECT * FROM user_accounts";
        } else {
            $sql = "SELECT * FROM user_accounts WHERE user_id = '$_SESSION[account_id]'";
        }
        ?>
        <h3>User Account Editing</h3>
        <table id="user_account_table">
            <tr>
                <th>
                    First Name
                </th>
                <th>
                    Last Name
                </th>
                <th>
                   Post Address
                </th>
                <th>
                    Account Type
                </th>
            </tr>
            <?php
            foreach ($dbh->query($sql) as $row) {
            ?>
            <form name="user_accounts" method="post" action="dbprocess.php">
            <tr><td><input type="text" name="firstname" value="<?php echo $row['firstname']; ?>"></td>
                <td><input type="text" name="lastname" value="<?php echo $row['lastname']; ?>"></td>
                <td><input type="text" name="post_address" value="<?php echo $row['post_address']; ?>"></td>
                <td><select name="account_type">
                        <option value="paid_user" <?php if($row['account_type'] == 'paid_user'){echo("selected");}?>>Paid User</option>
                        <option value="admin" <?php if($row['account_type'] == 'admin'){echo("selected");}?>>Admin</option>
                        <option value="user" <?php if($row['account_type'] == 'user'){echo("selected");}?>>User</option>
                    </select></td>
                <td><input type="submit" name="update_button" value="Update"></td>
                <td><input type="submit" name="delete_button" value="Delete" id="delete_button"></td></tr>
                <input type="hidden" name="account_id" value="<?php echo $row['account_id']; ?>">

            </form>
            <?php } ?>
        </table>

<?php
    } else if ($_GET['editing_mode'] == "upgrade_user") {
        echo "<h3>Upgrade to a paying user now to gain access to adding and editing Artists</h3>";
        echo "<p>You can support the Music Centre by becoming a Member and derive some benefits for yourself at the same time. Your subscription helps to keep us operating and we provide substantial discounts whenever possible.</p>
                <p>For the Music Centre's own events, Members' ticket discounts can be as high as 50%!</p>
                <p>The Music Centre is also registered as a Deductible Gift Recipient. Any extra donations are tax-deductible!</p>";
        echo "<h3>Individual Membership Price $25 per year</h3>";
        // Paypal link taken from their website
        echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
                    <input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
                    <input type=\"hidden\" name=\"hosted_button_id\" value=\"GCRJ28AFLXURQ\">
                    <input type=\"image\" src=\"https://www.paypalobjects.com/en_AU/i/btn/btn_paynow_SM.gif\" name=\"submit\" alt=\"PayPal � The safer, easier way to pay online.\">
                    <img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_AU/i/scr/pixel.gif\" width=\"1\" height=\"1\">
                    </form>";
    } ?>



</div>
</body>
<?php 
include("footer.php")
?>
