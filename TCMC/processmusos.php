<?php
include("dbconnect.php");
?>

<form method="post">
 <label>Filter Genres: </label>
<select name="genreselect">
  <option value="Rock">Rock</option>
  <option value="Pop">Pop</option>
  <option value="Jazz">Jazz</option>
  <option value="Acapella">Acapella</option>
</select>
<input name="submit" type="submit" value="submit" >
</form>

<?php
$sql = "SELECT * FROM artist_details"
?>
 
<div id="musoscontainer">
<ul>  

<?php 
foreach($dbh->query($sql) as $row){
?>	
<li>
<?php	
echo "<img src=\"$row[images]\" height='200' width='300'> <p>\n</p> <h1>$row[name]</h1><p>$row[genre]</p> $row[description] <p>\n</p> <a href=\"/moreinfo.php?id=$row[artist_id]\"> More info</a>";
?>
</li>
<?php }?>