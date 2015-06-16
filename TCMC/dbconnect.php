<?php

try {
    // Creates object orient DB connection.
    $dbh = new PDO("sqlite:db/tcmc.sqlite");
} catch (PDOException $e){
    echo $e->getMessage();
}
?>

