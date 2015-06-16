<?php
include("dbconnect.php");
?>

<?php 
$sql = "SELECT * FROM events"
?>

<?php 
foreach($dbh->query($sql) as $row){
?>	
<li>	
<?php	
echo "<img src='$row[image]' width='50' height='50'/> <p>\n</p> <h1>$row[event_name]</h1> <p>\n</p> <p>$row[event_description]</p> <p>\n</p> $row[event_date] <p>\n</p>";
?>
</li>
<?php }?>