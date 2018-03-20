<?php
	session_start();
	if(isset($_GET['email'])) {
		$username = "";
		$password = "";
		$server = "";
		$db = "";

		$conn = mysqli_connect($server, $username, $password, $db);

		if(!$conn) {
			echo "<script>console.log('Connection Failed ".mysqli_connect_error()."')</script>";
			die();
		}

		$name = $_GET['name'];
		$email = $_GET['email'];
		$sql = "SELECT email FROM users WHERE email='$email'";
		$result = mysqli_query($conn, $sql);

		if(mysqli_num_rows($result)==0) {
			$sql = "INSERT INTO users VALUES(NULL, '$name', '$email')";
			if(mysqli_query($conn, $sql)) {
				echo "<script>console.log('user added to Database Succesfully')</script>";
			}
			else {
				$error = preg_replace('/[^A-Za-z0-9\- ]/', '', mysqli_error($conn));
				echo "<script>console.log('Database error ".$error."')</script>";
				// echo "<script>window.alert('A User with this Email Address already Exists')</script>";
				die();
			}
		}

		$sql = "SELECT userId FROM users WHERE email='$email'";
		$result = mysqli_query($conn, $sql);
		$userId = -1;
		if(mysqli_num_rows($result)>0) {
			$row = mysqli_fetch_assoc($result);
		} else {
			$error = preg_replace('/[^A-Za-z0-9\- ]/', '', mysqli_error($conn));
			echo "<script>console.log('Database error ".$error."')</script>";
			die();
		}

		$_SESSION['userId'] = $userId;
		$_SESSION['name']  = $name;
		$_SESSION['email'] = $email;

		mysqli_close($conn);
		header('Location: ');

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome</title>
	<style>
    * {
	     padding: 0px;
	     margin: 0px;
      }

    html {
	     height: 100%;
    }

    body {
	    display: flex;
	    flex-flow: column nowrap;
	    height: 100%;
    }
    div#header {
	    background-color: #2f4f4f;
	    max-width: 100%;
	    font-family: verdana;
	    color: #348e9e;
    }

    div#login {
	    float: right;
	    padding: 30px;
    }

    input[type=password].login, input[type=text].login {
    	padding: 5px;
    	border-radius: 2px;
    	margin: 3px;
    	border: none;
    	height: 15px;
    	width: 150px;
    	font-size: 13px;
    }

    input[type=submit].login {
    	width: 60px;
    	font-weight: bold;
    	font-size: 13px;
    	padding: 2px;
    	max-height: 25px;
    	border-radius: 2px;
    }

    input[type=submit]:hover {
    	background-color: #348e9e; /*#45a049*/
    }
  </style>
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<meta name="google-signin-client_id" content="893838997651-m8j18himp436r9tscqu1q3vhlbjj8db4.apps.googleusercontent.com">
	<meta name="google-signin-hosted_domain" content="nitc.ac.in" />
</head>
<body>
  <div id="header">
    <div id="login">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="text" placeholder="Administrator" class="login" name="username" required="" >
        <input type="password" placeholder="Password" class="login" name="password" required="" >
        <input type="submit" value="Login" class="login">
        <input type="text" style="display: none;" name="source" value="topbar_login" >
      </form>
    </div>
  </div>
	<div id="reg">
		<center>
			<h4 style="font-family: verdana;margin-bottom:10px;font-weight:100">Guides login with your NITC email ID</h4>
		<div class="g-signin2" data-onsuccess="onSignIn"></div>
		<center>
	</div>

	<script>
		function onSignIn(googleUser) {
			var profile = googleUser.getBasicProfile();
			var id = profile.getId();
			var name = profile.getName();
			var email = profile.getEmail();
			console.log('ID: ' + id); // Do not send to your backend! Use an ID token instead.
			console.log('Name: ' + name);
			console.log('Image URL: ' + profile.getImageUrl());
			console.log('Email: ' + email); // This is null if the 'email' scope is not present.
			window.location.href="index.php?name="+name+"&email="+email;
		}
	</script>
<?php
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['source'] == 'topbar_login') {

      $email = $_POST['username'];
      $pass = $_POST['password'];
      if($username == 'admin' && $pass == 'admin') {
        $_SESSION['username'] = $username;
        header('Location: ');
      }
      else {
        echo "<script>window.alert('Incorrect Username or Password')</script>";
        die();
      }
    }
  }
?>
</body>
</html>
