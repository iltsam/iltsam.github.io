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
include("header.php")
?>
<?php
include("nav.php")
?>            
            
        
 <div id="content">
     <div id="allevents">   
         <ul>
             <?php include("processEvents.php") ?>
         </ul>
         
     </div>
     </div>

<footer>
<p> © Townsville Community Music Centre 2015  </p> 
<a href="https://www.facebook.com/pages/Townsville-Community-Music-Centre/159636880763534"> <img src="find us on facebook.jpg" width="205" height="62" alt=""/> </a>
</footer>


</body>
</html>
