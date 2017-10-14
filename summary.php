<?php
session_start();
if(!isset($_SESSION['user']))
{
	header("Location:./");
	return;
}

require_once("./db.php");
header('Content-Disposition: filename="shopSummary.csv"');
header("Content-Type: application/csv");

$dBegin=$_POST['dtBegin'];
$dEnd=$_POST['dtEnd'];
$query="SELECT `customer`, SUM(`price`) FROM `orders` WHERE  `time`>= '$dBegin' AND `time` <= '$dEnd' GROUP BY `customer`";
$result=mysqli_query($con, $query);
while($row=mysqli_fetch_array($result)){
	echo '"'.$row['customer']."\",".number_format($row['SUM(`price`)'], 2, '.', '')."\r\n";
}
echo mysqli_error($con);
?>