<?php

session_start();

	if (!isset($_SESSION['adminName'])) {
		header("Location: home.php");
		exit();
	}

?>




<!DOCTYPE html>
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
<head>
   
	
	
	
	
	
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <style>
        #thead>tr>th{ color: white; }
    </style>
</head>

<body>



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



echo  "Admin". "<br>"; 

echo  $_SESSION['adminName']. "<br>"; 






?>


</div>



</div>



<div class="topnav">
  <a class="active" href="#home">Pending Users </a>
  <a href="../User list/admin.php">Users List</a>
  <a href="#contact">Student Notes</a>
  <div class="topnav-right">
<a href="logout.php" >Log Out</a>
	
	
	

	
  </div>
</div>



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


<div style="padding-left:16px">
  
</div>
</div>



<div style="margin-top: 20px;padding-bottom: 20px;">
    <center>
      

    </center>
</div>
<div class="container">
    <table class="table">
        <thead id="thead" style="background-color: #26a2af">
        <tr>
            <th>Sr.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>University</th>
			<th>Created On</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        include "config.php";
        include_once "deleteSql.php";
        $common = new Common();
        $records = $common->getAllRecords($connection);
        if ($records->num_rows>0){
            $sr = 1;
            while ($record = $records->fetch_object()) {
                $recordId = $record->id;
                $name = $record->name;
                $email = $record->email;
                $department = $record->prog;
				$university = $record->uni;
				$created = $record->createdOn;
				?>
                <tr>
                    <th><?php echo $sr;?></th>
                    <th><?php echo $name;?></th>
                    <th><?php echo $email;?></th>
                    <th><?php echo $department;?></th>
					<th><?php echo $university;?></th>
					<th><?php echo $created;?></th>
					<td><a href="approve-script.php?recordId=<?php echo $recordId?>" class="btn btn-success">Approve</a> 
					<a href="delete-script.php?recordId=<?php echo $recordId?>" class="btn btn-danger btn">Delete</a></td>
               
					
                </tr>
                <?php
                $sr++;
            }
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>