<?php
session_start();
$pass=strtolower($_POST['password']);
$users=file("users.txt");
foreach($users as $user){
	if(strlen($user)<1)
		continue;
	if(strcmp(strtolower(trim($user)), $pass)==0){		
		$_SESSION['user']=$pass;
		header("Location:./");
		return;
	}
}
echo "$pass is not a registered user";
?>

<form action="./login.php" method="post" style="margin-top:5px;">
	<input name="password" placeholder="Who are you?">
	<input type="submit" value="Get selling!">
</form>	