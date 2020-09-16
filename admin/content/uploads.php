<?php

session_start();

	if (!isset($_SESSION['contName'])) {
		header("Location: home.php");
		exit();
	}

?>


<!doctype html>
<html lang="en">




<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #4CAF50;
  color: white;
}

.topnav-right {
  float: right;
}
</style>

<body>
<div class="topnav">

  <a href="admin.php">Comments</a>
  <a href="../User list/admin.php">Comments</a>
  <a class="active" href="#home">Upload </a>
  <div class="topnav-right">

<a href="logout.php" >Log Out</a>
	
	

	
  </div>
</div>

<div style="padding-left:16px">
  
</div>
</body>














<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
        .comment {
            margin-bottom: 20px;
        }

        .user {
            font-weight: bold;
            color: black;
        }

        .time, .reply {
            color: gray;
        }

        .userComment {
            color: #000;
        }

        .replies .comment {
            margin-top: 20px;

        }

        .replies {
            margin-left: 20px;
        }

        #registerModal input, #logInModal input {
            margin-top: 10px;
        }
		#registerModal input, #adminModal input {
            margin-top: 10px;
        }
    </style>
</head>
<body>




<style>
/* Style the body */
body {
  font-family: Arial;

}

/* Header/Logo Title */
.header {
	

  
  background: #1abc9c;

  color: white;
  font-size: 20px;


     text-align: center;


 
  top: 20%;

  z-index: 2;

  padding: 20px;
  text-align: center;






}

/* Page Content */
.content {padding:20px;}
</style>



<div class="header">

  <h1>COURSE SOLUTION</h1>
  <p>A HAND TO YOUR SUCCESS</p>
</div>


    <div class="row" style="margin-top: 10px;margin-bottom: 20px;">
        <div class="col-md-12" align="center">
       
        </div>





<div>
<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 150px;
   
  
  text-align: center;
  font-family: arial;
 
  position: absolute;
  left: 10px;
  top: 80px;

}

.title {
  color: grey;
  font-size: 14px;
}

uname{
  text-decoration: none;
  font-size: 18px;
  color: black;
}

button:hover, a:hover {
  opacity: 0.7;
}
</style>





<div class="card">
 

<img src="112.PNG" alt="tahsin" style="width:100%">
<?php


echo  "Contant Manager". "<br>"; 

echo  $_SESSION['contName']. "<br>"; 





?>


</div>



</div>



































<head>
    <title>File Upload</title>
</head>
<body>

        <div class="col-md-12" align="center">
       <form method="post" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title">
    <label>File Upload</label>
    <input type="File" name="file">
    <input type="submit" name="submit">
 
 <?php 

$localhost = "localhost"; #localhost
$dbusername = "root"; #username of phpmyadmin
$dbpassword = "";  #password of phpmyadmin
$dbname = "ytcommentsystem";  #database name
 
#connection string
$conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
 
if (isset($_POST["submit"]))
 {
     #retrieve file title
        $title = $_POST["title"];
     
    #file name with a random number so that similar dont get replaced
     $pname = rand(1000,10000)."-".$_FILES["file"]["name"];
 
    #temporary file name to store file
    $tname = $_FILES["file"]["tmp_name"];
   
     #upload directory path
$uploads_dir = 'images';
    #TO move the uploaded file to specific location
    move_uploaded_file($tname, $uploads_dir.'/'.$pname);
 
    #sql query to insert into database
    $sql = "INSERT into fileup(title,image) VALUES('$title','$pname')";
 
    if(mysqli_query($conn,$sql)){
 
    echo "<br>File Sucessfully uploaded";
    }
    else{
        echo "<br>Error";
    }
}
 
 
?>
</form>
        </div>


</body>
</html>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
