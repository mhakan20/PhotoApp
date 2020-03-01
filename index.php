<?php
  // Conexiune la BD
 
 require_once "connect.php";

  
  $msg = "";

 
  if (isset($_POST['upload'])) {
  	
  	$image = $_FILES['image']['name'];
  	
  	$image_text = mysqli_real_escape_string($link, $_POST['image_text']);

  	
  	$target = "images/".basename($image);
    //pastrarea numelui fisierului intr-o baza de date pentru a putea fi accesata ulterior
  	$sql = "INSERT INTO images (image, image_text) VALUES ('$image', '$image_text')";
  	
  	mysqli_query($link, $sql);
    //upload in server a fisierului
  	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
  		$msg = "Upload realizat";
  	}else{
  		$msg = "Upload ratat";
  	}
  }
  //salvare nume tuturor pozelor pentru afisare
  $result = mysqli_query($link, "SELECT * FROM images");
?>
<!DOCTYPE html>
<html>
<head>
<title>Image Upload</title>
<style type="text/css">
   #content{
   	width: 50%;
   	margin: 20px auto;
   	border: 1px solid #cbcbcb;
   }
   form{
   	width: 50%;
   	margin: 20px auto;
   }
   form div{
   	margin-top: 5px;
   }
   #img_div{
   	width: 80%;
   	padding: 5px;
   	margin: 15px auto;
   	border: 1px solid #cbcbcb;
   }
   #img_div:after{
   	content: "";
   	display: block;
   	clear: both;
   }
   img{
   	float: left;
   	margin: 5px;
   	width: 300px;
   	height: 140px;
   }
</style>
</head>
<body>
  <center>
  <p><span style="color: #000000;"><span style="background-color: #00ffff;"><strong>Simple Facebook</strong></span></span></p>
    <p><span style="color: #000000; background-color: #00ffff;"><strong>AWJ Project</strong></span></p>
  </center>
<div id="content">
  <?php
  //afisare poze pe rand
    while ($row = mysqli_fetch_array($result)) {
      echo "<div id='img_div'>";
      	echo "<img src='images/".$row['image']."' >";
      	echo "<p><strong>".$row['image_text']."</strong></p>";
      echo "</div>";
    }
  ?>
  <form method="POST" action="index.php" enctype="multipart/form-data">
  	<input type="hidden" name="size" value="1000000">
  	<div>
  	  <input type="file" name="image">
  	</div>
  	<div>
      <textarea 
      	id="text" 
      	cols="40" 
      	rows="4" 
      	name="image_text" 
      	placeholder="Descriere poza"></textarea>
  	</div>
  	<div>
  		<button type="submit" name="upload">POST</button>
  	</div>
    <div>
      <a href="logout.php" class="btn btn-danger">Signout cont</a>
    </div>
  </form>
</div>
</body>
</html>