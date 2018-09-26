<?php
session_start ();
function loginForm() {
	echo '
    <div id="loginform">
    <form action="index.php" method="post">
        <p>Please enter your first name,middle initail and last name to continue:</p>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" />
        <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
    </div>
    ';
}
if (isset ( $_POST ['enter'] )) {
	if ($_POST ['name'] != "") {
		$_SESSION ['name'] = stripslashes ( htmlspecialchars ( $_POST ['name'] ) );
		$fp = fopen ( "log.html", 'a' );
		fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
		fclose ( $fp );
	} else {
		echo '<span class="error">Please type in a name</span>';
	}
}
if (isset ( $_GET ['logout'] )) {
	$fp = fopen ( "log.html", 'a' );
	fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has left the chat session.</i><br></div>" );
	fclose ( $fp );
	
	session_destroy ();
	unlink("log.html");
	header ( "Location: index.php" ); 
}
?>

<!DOCTYPE html>
<head>
<style>
body {
	font: 12px arial;
	color: #FEF02F;
	text-align: center;
	padding: 35px;
	background: url("jags.png");
}

form,p,span {
	margin: 0;
	padding: 0;
}

input {
	font: 12px arial;
}

a {
	color: #FEF02F;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

#wrapper,#loginform {
	margin: 0 auto;
	padding-bottom: 25px;
	background: #800000;
	width: 504px;
	border: 1px solid #010000;
}

#loginform {
	padding-top: 18px;
}

#loginform p {
	margin: 5px;
}

#chatbox {
	text-align: left;
	margin: 0 auto;
	margin-bottom: 25px;
	padding: 10px;
	background: #010000;
	height: 270px;
	width: 430px;
	border: 1px solid #010000;
	overflow: auto;
}

#usermsg {
	width: 395px;
	border: 1px solid #ACD8F0;
}

#submit {
	width: 60px;
}

.error {
	color: #ff0000;
}

#menu {
	padding: 12.5px 25px 12.5px 25px;
}

.welcome {
	float: left;
}

.logout {
	float: right;
}

.msgln {
	margin: 0 0 2px 0;
}
</style>
<title>Guidance Chat</title>
</head>
<body>
	<?php
	if (! isset ( $_SESSION ['name'] )) {
		loginForm ();
	} else {
		?>
<div id="wrapper">
		<div id="menu">
			<p class="welcome">
				Welcome, <b><?php echo $_SESSION['name']; ?></b>
			</p>
			<p class="logout">
				<a id="exit" href="#">Exit Chat</a>
			</p>
			<div style="clear: both"></div>
		</div>
		<div id="chatbox"><?php
		if (file_exists ( "log.html" ) && filesize ( "log.html" ) > 0) {
			$handle = fopen ( "log.html", "r" );
			$contents = fread ( $handle, filesize ( "log.html" ) );
			fclose ( $handle );
			
			echo $contents;
		}
		?></div>

		<form name="message" action="">
			<input name="usermsg" type="text" id="usermsg" size="63" /> <input
				name="submitmsg" type="submit" id="submitmsg" value="Send" />
		</form>
	</div>
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
	<script type="text/javascript">
	
$(document).ready(function(){
	
	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to end the session?");
		if(exit==true){window.location = 'index.php?logout=true';}		
	});
});


$("#submitmsg").click(function(){
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		loadLog;
	return false;
});

function loadLog(){		
	var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; 
	$.ajax({
		url: "log.html",
		cache: false,
		success: function(html){		
			$("#chatbox").html(html); 
			
			
			var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; 
			if(newscrollHeight > oldscrollHeight){
				$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
			}				
	  	},
	});
}

setInterval (loadLog, 2500);
</script>
<?php
	}
	?>
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
	<script type="text/javascript">
</script>
</body>
</html>