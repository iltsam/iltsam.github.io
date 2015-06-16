<?php
include("dbconnect.php");
session_start();
require("authenticate.php");
require("wideimage/WideImage.php");

// Creates a filter for all POSTs
if (isset($_SESSION['coming_from'])){

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    if ($_SESSION['coming_from'] == "bulletins") {
        if (isset($_POST['delete_button'])) {
            $sql = "DELETE FROM bulletins WHERE id = '$_POST[id]' ";
            $dbh->query($sql);
        } else if (isset($_POST['update_button'])) {
            $sql = "UPDATE bulletins SET description = '$_POST[description]', name = '$_POST[name]' WHERE id = '$_POST[id]'";
            $dbh->query($sql);
        } else if (isset($_POST['create_bulletin'])) {
            $sql = "INSERT INTO bulletins (name, description, account_id, date) VALUES ('$_POST[name]', '$_POST[description]', '$_SESSION[account_id]', '$_POST[expiry_date]')";
            // Lindsay Wards image upload code.
            // check to see if the image is valid
            // check MIME type (GIF or JPEG) and maximum upload size
            if ((($_FILES["imagefile"]["type"] == "image/gif")
                    || ($_FILES["imagefile"]["type"] == "image/jpeg")
                    || ($_FILES["imagefile"]["type"] == "image/pjpeg")
                    || ($_FILES["imagefile"]["type"] == "image/png"))
                && ($_FILES["imagefile"]["size"] < 2000000))
            {
                // check for any error code in the data
                if ($_FILES["imagefile"]["error"] > 0)
                {
                    echo "Error Code: " . $_FILES["imagefile"]["error"] . "<br />";
                }
                else
                {
                    // create a new unique filename using current time and existing filename
                    $newName = time() . preg_replace('/\s+/', '', $_FILES["imagefile"]["name"]);
                    $newFullName = "res/images/{$newName}";
                    echo $newFullName;
                    // move the temporary file to the destination directory (images) and give it its new name
                    move_uploaded_file($_FILES["imagefile"]["tmp_name"], $newFullName);
                    // set the permission on the file
                    chmod($newFullName, 0644);
                    // NOW, create a separate thumbnail from original image
                    // demo of the {} syntax as well...
                    $image = WideImage::load($newFullName);
                    // resize maintains aspect ratio, so the new image will fit within the rectangle defined by the parameters
                    // you might like to use a constant for this size
                    $thumbnailImage = $image->resize(300, 300);
                    $thumbFullName = "res/images/thumb{$newName}";
                    $thumbnailImage->saveToFile($thumbFullName);
                    $sql = "INSERT INTO bulletins (name, description, account_id, date, image, thumbnail_image) VALUES ('$_POST[name]', '$_POST[description]', '$_SESSION[account_id]', '$_POST[expiry_date]', '$newFullName', '$thumbFullName')";
                }
            }
            $dbh->query($sql);
        }
        header("Location: user_control_panel.php?editing_mode=bulletin");
    } else if ($_SESSION['coming_from'] == "artist") {
        if (isset($_POST['delete_button'])) {
            $sql = "DELETE FROM artist_details WHERE artist_id = '$_POST[artist_id]' ";
            $dbh->query($sql);
        } else if (isset($_POST['update_button'])) {
            $sql = "UPDATE artist_details SET description = '$_POST[description]', name = '$_POST[name]', genre = '$_POST[genre]', more_info = '$_POST[more_info]' WHERE artist_id = '$_POST[artist_id]'";
            $dbh->query($sql);
        } else if (isset($_POST['create_button'])) {
            $sql = "INSERT INTO artist_details (name, description, genre, more_info) VALUES ('$_POST[name]', '$_POST[description]', '$_POST[genre]', '$_POST[more_info]')";

            // Lindsay Wards image upload code.
            // check to see if the image is valid
            // check MIME type (GIF or JPEG) and maximum upload size
            if ((($_FILES["imagefile"]["type"] == "image/gif")
                    || ($_FILES["imagefile"]["type"] == "image/jpeg")
                    || ($_FILES["imagefile"]["type"] == "image/pjpeg")
                    || ($_FILES["imagefile"]["type"] == "image/png"))
                && ($_FILES["imagefile"]["size"] < 2000000))
            {
                // check for any error code in the data
                if ($_FILES["imagefile"]["error"] > 0)
                {
                    echo "Error Code: " . $_FILES["imagefile"]["error"] . "<br />";
                } else {
                    // create a new unique filename using current time and existing filename
                    $newName = time() . preg_replace('/\s+/', '', $_FILES["imagefile"]["name"]);
                    $newFullName = "res/images/{$newName}";
                    echo $newFullName;
                    // move the temporary file to the destination directory (images) and give it its new name
                    move_uploaded_file($_FILES["imagefile"]["tmp_name"], $newFullName);
                    // set the permission on the file
                    chmod($newFullName, 0644);
                    // NOW, create a separate thumbnail from original image
                    // demo of the {} syntax as well...
                    $image = WideImage::load($newFullName);
                    // resize maintains aspect ratio, so the new image will fit within the rectangle defined by the parameters
                    // you might like to use a constant for this size
                    $thumbnailImage = $image->resize(300, 300);
                    $thumbFullName = "res/images/thumb{$newName}";
                    $thumbnailImage->saveToFile($thumbFullName);
                    $sql = "INSERT INTO artist_details (name, description, genre, images, thumbnail_image, more_info) VALUES ('$_POST[name]', '$_POST[description]', '$_POST[genre]', '$newFullName', '$thumbFullName', '$_POST[more_info]')";
                }
            }
            $dbh->query($sql);
        }
        // Redirect User back to UCP
        header("Location: user_control_panel.php?editing_mode=artist");
    } else if ($_SESSION['coming_from'] == "events") {
        if (isset($_POST['delete_button'])) {
            $sql = "DELETE FROM events WHERE event_id = '$_POST[event_id]' ";
            $dbh->query($sql);
        } else if (isset($_POST['update_button'])) {
            $sql = "UPDATE events SET event_description = '$_POST[event_description]', event_name = '$_POST[event_name]', expiry_date = '$_POST[expiry_date]' WHERE event_id = '$_POST[event_id]'";
            $dbh->query($sql);
        } else if (isset($_POST['create_event'])) {
            $sql = "INSERT INTO events (event_name, event_description, expiry_date, creator_id) VALUES ('$_POST[event_name]', '$_POST[event_description]', '$_POST[expiry_date]', '$_POST[creator_id]')";

            // Lindsay Wards image upload code.
            // check to see if the image is valid
            // check MIME type (GIF or JPEG) and maximum upload size
            if ((($_FILES["imagefile"]["type"] == "image/gif")
                    || ($_FILES["imagefile"]["type"] == "image/jpeg")
                    || ($_FILES["imagefile"]["type"] == "image/pjpeg")
                    || ($_FILES["imagefile"]["type"] == "image/png"))
                && ($_FILES["imagefile"]["size"] < 2000000))
            {
                // check for any error code in the data
                if ($_FILES["imagefile"]["error"] > 0)
                {
                    echo "Error Code: " . $_FILES["imagefile"]["error"] . "<br />";
                }
                else
                {
                        // create a new unique filename using current time and existing filename
                        $newName = time() . preg_replace('/\s+/', '', $_FILES["imagefile"]["name"]);
                        $newFullName = "res/images/{$newName}";
                        echo $newFullName;
                        // move the temporary file to the destination directory (images) and give it its new name
                        move_uploaded_file($_FILES["imagefile"]["tmp_name"], $newFullName);
                        // set the permission on the file
                        chmod($newFullName, 0644);
                        // NOW, create a separate thumbnail from original image
                        // demo of the {} syntax as well...
                        $image = WideImage::load($newFullName);
                        // resize maintains aspect ratio, so the new image will fit within the rectangle defined by the parameters
                        // you might like to use a constant for this size
                        $thumbnailImage = $image->resize(300, 300);
                        $thumbFullName = "res/images/thumb{$newName}";
                        $thumbnailImage->saveToFile($thumbFullName);
                        $sql = "INSERT INTO events (event_name, event_description, expiry_date, creator_id, image, thumbnail_image) VALUES ('$_POST[event_name]', '$_POST[event_description]', '$_POST[expiry_date]', '$_POST[creator_id]', '$newFullName', '$thumbFullName')";
                }
            }
            $dbh->query($sql);
        }
        header("Location: user_control_panel.php?editing_mode=events");
    } else if ($_SESSION['coming_from'] == "user_accounts") {
        if (isset($_POST['delete_button'])) {
            $sql = "DELETE FROM user_accounts WHERE account_id = '$_POST[account_id]' ";
            $dbh->query($sql);
        } else if (isset($_POST['update_button'])) {
            $sql = "UPDATE user_accounts SET firstname = '$_POST[firstname]', lastname = '$_POST[lastname]', post_address = '$_POST[post_address]', account_type = '$_POST[account_type]' WHERE account_id = '$_POST[account_id]'";
            $dbh->query($sql);
        }
        header("Location: user_control_panel.php?editing_mode=user_account");
    }

} else {

}

?>