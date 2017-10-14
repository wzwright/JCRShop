<?php
session_start();
if(!isset($_SESSION['user']))
{
	header("Location:./");
	echo "no user";
	return;
}
require_once('./db.php');
$working=$_SESSION['user'];
$customer=$_GET['cust'];
$rawItems=urldecode($_GET['items']);
$items=explode('|', $rawItems);
$total=0;
foreach($items as $itemPair){
	$pair=explode('~', $itemPair);
	$query="SELECT `price` FROM `items` WHERE `item` = \"".$pair[0]."\"";
	$result=mysqli_query($con,$query);
	$total+=floatval(mysqli_fetch_array($result)['price'])*floatval($pair[1]);
}

$query="INSERT INTO `orders` (`customer`, `price`, `order`) VALUES (\"$customer\", $total, \"$rawItems\");";
$result=mysqli_query($con,$query);
if($result)
	echo "success";
else
	echo mysqli_error($con);
?>