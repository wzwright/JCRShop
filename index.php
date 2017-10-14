<?php
session_start();
if(isset($_SESSION['user'])){
	if($_SESSION['user']=='shopmaster'){
		require_once('./admin.php');
	}
	else{
		require_once('./shop.php');
	}
	echo '<a href="./logout.php" style="position:fixed; top:15px; right:20px;">log out</a>';
}
else{
?>
<form action="login.php" method="post">
	<input name="password" placeholder="Who are you?">
	<input type="submit" value="Get selling!">
</form>	
<?php
}
?>