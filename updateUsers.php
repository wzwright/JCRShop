<?php
session_start();
if(!isset($_SESSION['user']))
{
	header("Location:./");
	return;
}
try{
	$text=urldecode($_GET['u']);
	if(strpos($text, 'shopmaster')===false){
		echo 'Do not remove shopmaster';
		return;
	}
	file_put_contents("./users.txt", $text);
}
catch(Exception $e){
	echo $e->getMessage();
	return;
}

echo "success";
?>