<?php
session_start();
if(!isset($_SESSION['user']))
{
	header("Location:./");
	echo "no user";
	return;
}
require_once('./db.php');
$customer=$_GET['c'];
$date=$_GET['d'];
$query="SELECT `price`, `order` FROM `orders` WHERE `customer`='$customer' AND `time`> '$date'";
$result=mysqli_query($con, $query);
$total=0;
$res="<style>table{border-collapse: collapse;}
table, td{border:1px solid black;padding:5px;}
</style><table>";
while($row=mysqli_fetch_array($result)){
	$total+=floatval($row['price']);
	$ord=str_replace("|",", ", str_replace("~", ":" ,$row['order']));
	$res.="<tr><td>".number_format(floatval($row['price']), 2, '.', '')."</td><td>".$ord."</td></tr>";
}
echo "<h4>Total: ".number_format($total, 2, '.', '')."</h4>".$res."</table>"
?>