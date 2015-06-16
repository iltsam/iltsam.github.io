<?php
include("dbconnect.php");
?>

<?php
// Selects everything from bulletins - recently added first.
$sql = "SELECT * FROM bulletins ORDER BY id DESC"
?>

<?php 
foreach($dbh->query($sql) as $row){
?>	
<div id="post">	
<?php	
echo "<img src='$row[image]' id=\"postimg\"> <p>\n</p> <h1>$row[name]</h1> <p>\n</p> <p>$row[description]<br/>Expires: $row[date]</p>   <p>\n</p>";
?>
</div>
<?php }?>