<?php
session_start();

$loggedIn = false;

if (isset($_SESSION['loggedIn']) && isset($_SESSION['name'])) {
    $loggedIn = true;
}

$conn = new mysqli('localhost', 'root', '', 'ytCommentSystem');

function createCommentRow($data) {
    global $conn;

    $response = '
            <div class="comment">
                <div class="user">'.$data['name'].' <span class="time">'.$data['createdOn'].'</span></div>
                <div class="userComment">'.$data['comment'].'</div>
                <div class="reply"><a href="javascript:void(0)" data-commentID="'.$data['id'].'" onclick="reply(this)">REPLY</a></div>
                <div class="replies">';

    $sql = $conn->query("SELECT replies.id, name, comment, DATE_FORMAT(replies.createdOn, '%Y-%m-%d') AS createdOn FROM replies INNER JOIN users ON replies.userID = users.id WHERE replies.commentID = '".$data['id']."' ORDER BY replies.id DESC LIMIT 1");
    while($dataR = $sql->fetch_assoc()){

        $response .= createCommentRow($dataR);
	}
    $response .= '
                        </div>
            </div>
        ';

    return $response;
}

if (isset($_POST['getAllComments'])) {
    $start = $conn->real_escape_string($_POST['start']);

    $response = "";
    $sql = $conn->query("SELECT comments.id, name, comment, DATE_FORMAT(comments.createdOn, '%Y-%m-%d') AS createdOn FROM comments INNER JOIN users ON comments.userID = users.id ORDER BY comments.id DESC LIMIT $start, 20");
    while($data = $sql->fetch_assoc())
        $response .= createCommentRow($data);

    exit($response);
}

if (isset($_POST['addComment'])) {
    $comment = $conn->real_escape_string($_POST['comment']);
    $isReply = $conn->real_escape_string($_POST['isReply']);
    $commentID = $conn->real_escape_string($_POST['commentID']);

    if ($isReply != 'false') {
        $conn->query("INSERT INTO replies (comment, commentID, userID, createdOn) VALUES ('$comment', '$commentID', '".$_SESSION['userID']."', NOW())");
        $sql = $conn->query("SELECT replies.id, name, comment, DATE_FORMAT(replies.createdOn, '%Y-%m-%d') AS createdOn FROM replies INNER JOIN users ON replies.userID = users.id ORDER BY replies.id DESC LIMIT 1");
    } else {
        $conn->query("INSERT INTO comments (userID, comment, createdOn) VALUES ('".$_SESSION['userID']."','$comment',NOW())");
        $sql = $conn->query("SELECT comments.id, name, comment, DATE_FORMAT(comments.createdOn, '%Y-%m-%d') AS createdOn FROM comments INNER JOIN users ON comments.userID = users.id ORDER BY comments.id DESC LIMIT 1");
    }

    $data = $sql->fetch_assoc();
    exit(createCommentRow($data));
}





















if (isset($_POST['register'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
	$prog = $conn->real_escape_string($_POST['prog']);
	$uni = $conn->real_escape_string($_POST['uni']);



    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = $conn->query("SELECT id FROM pendinguser WHERE email='$email'");
        if ($sql->num_rows > 0)
            exit('failedUserExists');
        else {
            $ePassword = password_hash($password, PASSWORD_BCRYPT);
            $conn->query("INSERT INTO pendinguser (name,email,password,createdOn,prog,uni) VALUES ('$name', '$email', '$ePassword', NOW(), '$prog', '$uni')");

           
			
			
            exit('success');
        }
    } else
        exit('failedEmail');
}





if (isset($_POST['adminLogin'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = $conn->query("SELECT id, password, name FROM admin WHERE email='$email'");
        if ($sql->num_rows == 0)
            exit('failed');
        else {
            $data = $sql->fetch_assoc();
            $passwordHash = $data['password'];

            if (password_verify($password, $passwordHash)) {
                $_SESSION['adminLoggedIn'] = 1;
                $_SESSION['adminName'] = $data['name'];
                $_SESSION['adminEmail'] = $email;
				
			
                $_SESSION['userID'] = $data['id'];

                exit('success');
            } else
                exit('failed');
        }
    } else
        exit('failed');
}



if (isset($_POST['contLogin'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = $conn->query("SELECT id, password, name FROM contman WHERE email='$email'");
        if ($sql->num_rows == 0)
            exit('failed');
        else {
            $data = $sql->fetch_assoc();
            $passwordHash = $data['password'];

            if (password_verify($password, $passwordHash)) {
                $_SESSION['contLoggedIn'] = 1;
                $_SESSION['contName'] = $data['name'];
                $_SESSION['contEmail'] = $email;
				
			
                $_SESSION['userID'] = $data['id'];

                exit('success');
            } else
                exit('failed');
        }
    } else
        exit('failed');
}


	











if (isset($_POST['logIn'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = $conn->query("SELECT id, password, name, prog, uni FROM users WHERE email='$email'");
        if ($sql->num_rows == 0)
            exit('failed');
        else {
            $data = $sql->fetch_assoc();
            $passwordHash = $data['password'];

            if (password_verify($password, $passwordHash)) {
                $_SESSION['loggedIn'] = 1;
                $_SESSION['name'] = $data['name'];
                $_SESSION['email'] = $email;
				$_SESSION['prog'] = $data['prog'];
				$_SESSION['uni'] = $data['uni'];
			
                $_SESSION['userID'] = $data['id'];

                exit('success');
            } else
                exit('failed');
        }
    } else
        exit('failed');
}

$sqlNumComments = $conn->query("SELECT id FROM comments");
$numComments = $sqlNumComments->num_rows;
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
  <a class="active" href="#home">Discussions </a>


	


	
	
	
    <?php
            if (!$loggedIn)
                echo '
			  <div class="topnav-right">
			 
						<a href="#about"  data-toggle="modal" data-target="#adminModal">Admin</a>
						 <a href="#about"  data-toggle="modal" data-target="#contModal">Content Manager</a>
						<a href="#about"  data-toggle="modal" data-target="#registerModal">Register</a>
                        <a href="#about"  data-toggle="modal" data-target="#logInModal">Log In</a>
						</div>
                      
                ';
            else
                echo '
			  <a href="stunotes/admin.php">Student Notes</a>
    <a href="uploads.php">Upload Files</a>
	<div class="topnav-right">
						<a href="logout.php" >Log Out</a>
        </div>
                ';
				 
            ?>

	
  
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
		#registerModal input, #contModal input {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="modal" id="registerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registration Form</h5>
            </div>
            <div class="modal-body">
                <input type="text" id="userName" class="form-control" placeholder="Your Name">
                <input type="email" id="userEmail" class="form-control" placeholder="Your Email">
				<input type="text" id="prog"  class="form-control" placeholder="Your Program">
				<input type="text" id="uni"  class="form-control" placeholder="Your University">
                <input type="password" id="userPassword" class="form-control" placeholder="Password">
				
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="registerBtn">Register</button>
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
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



if (isset($_SESSION['loggedIn']) && isset($_SESSION['name'])) {
echo  $_SESSION['name']. "<br>"; 
echo  $_SESSION['prog']. "<br>";
echo  $_SESSION['uni']. "<br>";
}
else 
	
	echo  "<br>"."Guest Account"; 



?>


</div>



</div>



































<div class="modal" id="contModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Content Manager Login</h5>
            </div>
            <div class="modal-body">
                <input type="email" id="contEmail" class="form-control" placeholder="Your Email">
                <input type="password" id="contPassword" class="form-control" placeholder="Password">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="contBtn">Log In</button>
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<div class="modal" id="logInModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log In Form</h5>
            </div>
            <div class="modal-body">
                <input type="email" id="userLEmail" class="form-control" placeholder="Your Email">
                <input type="password" id="userLPassword" class="form-control" placeholder="Password">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="loginBtn">Log In</button>
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top:50px;">
    
	
	
	
    <div class="row" style="margin-top: 10px;margin-bottom: 20px;">
        <div class="col-md-12" align="center">
       
        </div>
		
		

		
		
		
		
		
		
    </div>
    <div class="row">
        <div class="col-md-12">
            <textarea class="form-control" id="mainComment" placeholder="Start Discussion..." cols="30" rows="2"></textarea><br>
            <button style="float:right" class="btn-primary btn" onclick="isReply = false;" id="addComment">Post</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2><b id="numComments"><?php echo $numComments ?> Comments</b></h2>
            <div class="userComments">

            </div>
        </div>
    </div>
</div>

<div class="row replyRow" style="display:none">
    <div class="col-md-12">
        <textarea class="form-control" id="replyComment" placeholder="Add Public Comment" cols="30" rows="2"></textarea><br>
        <button style="float:right" class="btn-primary btn" onclick="isReply = true;" id="addReply">Add Reply</button>
        <button style="float:right" class="btn-default btn" onclick="$('.replyRow').hide();">Close</button>
    </div>
</div>

<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript">
    var isReply = false, commentID = 0, max = <?php echo $numComments ?>;

    $(document).ready(function () {
        $("#addComment, #addReply").on('click', function () {
            var comment;

            if (!isReply)
                comment = $("#mainComment").val();
            else
                comment = $("#replyComment").val();

            if (comment.length > 5) {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        addComment: 1,
                        comment: comment,
                        isReply: isReply,
                        commentID: commentID
                    }, success: function (response) {
                        max++;
                        $("#numComments").text(max + " Posts");

                        if (!isReply) {
                            $(".userComments").prepend(response);
                            $("#mainComment").val("");
                        } else {
                            commentID = 0;
                            $("#replyComment").val("");
                            $(".replyRow").hide();
                            $('.replyRow').parent().next().append(response);
                        }
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });

        $("#registerBtn").on('click', function () {
            var name = $("#userName").val();
            var email = $("#userEmail").val();
            var password = $("#userPassword").val();
			var prog = $("#prog").val();
			var uni = $("#uni").val();
		

            if (name != "" && email != "" && password != "" && prog != "") {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        register: 1,
                        name: name,
                        email: email,
                        password: password,
                        prog: prog,
						uni: uni
                    }, success: function (response) {
                        if (response === 'failedEmail')
                            alert('Please insert valid email address!');
                        else if (response === 'failedUserExists')
                            alert('User with this email already exists!');
                        else
                            window.location = window.location;
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });
		$("#lginBtn").on('click', function () {
            var email = $("#uzerLEmail").val();
            var password = $("#uzerLPassword").val();

            if (email != "" && password != "") {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        adminLogin: 1,
                        email: email,
                        password: password
                    }, success: function (response) {
                        if (response === 'failed')
                            alert('Please admin check your login details!');
                        else
							location.href = "../admin/Pending user/admin.php" 
                            
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });
			$("#contBtn").on('click', function () {
            var email = $("#contEmail").val();
            var password = $("#contPassword").val();

            if (email != "" && password != "") {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        contLogin: 1,
                        email: email,
                        password: password
                    }, success: function (response) {
                        if (response === 'failed')
                            alert('Please admin check your login details!');
                        else
							location.href = "../admin/content/admin.php" 
                            
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });

        $("#loginBtn").on('click', function () {
            var email = $("#userLEmail").val();
            var password = $("#userLPassword").val();

            if (email != "" && password != "") {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        logIn: 1,
                        email: email,
                        password: password
                    }, success: function (response) {
                        if (response === 'failed')
                            alert('Please check your login details!');
                        else
                            window.location = window.location;
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });

        getAllComments(0, max);
    });

    function reply(caller) {
        commentID = $(caller).attr('data-commentID');
        $(".replyRow").insertAfter($(caller));
        $('.replyRow').show();
    }

    function getAllComments(start, max) {
        if (start > max) {
            return;
        }

        $.ajax({
            url: 'index.php',
            method: 'POST',
            dataType: 'text',
            data: {
                getAllComments: 1,
                start: start
            }, success: function (response) {
                $(".userComments").append(response);
                getAllComments((start+20), max);
            }
        });
    }
</script>
</body>
</html>