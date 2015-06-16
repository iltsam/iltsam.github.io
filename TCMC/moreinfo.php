<!DOCTYPE html>
<html lang="en"
      
    <head> 
        <title> Townsville  </title>
        <meta charset="utf-8"/>
        <script src="http://use.edgefonts.net/crimson-text:n4,i4,n7,i7,n6,i6:all;tangerine:n4,n7:all.js"></script>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    
    </head>

    <body>
<?php
include("dbconnect.php");
?>

<?php 
$sql = "SELECT * FROM artist_details WHERE artist_id = ". $_GET["id"]
?>
    

             
 <?php 
include("header.php")
?>
<?php
include("nav.php")
?>
            
        
 <div id="content">

<div id ="artist"> 

<?php 
foreach($dbh->query($sql) as $row){
?>
<p>		
<?php	
echo "<img src='$row[images]' > <p>\n</p> <h1>$row[name]</h1> <p>\n</p> <p>$row[genre]</p> <p>\n</p> $row[more_info]";
?>
<?php }?>
</p>
<br>
<br>
<p><a href="/musos.php"> Go Back </a></p>

</div>     
     
 </div>
 
 <?php 
include("footer.php")
?>

    </body>

