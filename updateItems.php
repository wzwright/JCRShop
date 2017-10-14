<?php
session_start();
if(!isset($_SESSION['user']))
{
	header("Location:./");
	return;
}
require_once('./db.php');

function close($a, $b){
	abs($a-$b)<0.005;
}

$items=[];
$found=[];
$query="SELECT `item`, `price` FROM `items`";
$result=mysqli_query($con, $query);
while($row=mysqli_fetch_array($result)){
	if(strlen(trim($row['item']))<1)
		continue;	
	$items[$row['item']]=$row['price'];
	$found[$row['item']]=false;
}
foreach(file($_FILES["uploadFile"]["tmp_name"]) as $linePair){
	$line=explode(',', $linePair);
	if(strlen(trim($line[1]))<1)
		continue;
	if(!is_numeric($line[1][0]))
		$line[1]=substr($line[1], 1);
	if(array_key_exists($line[0], $items)){
		if(!close(floatval($items[$line[0]]), floatval($line[1]))){
			$query="UPDATE `items` SET `price`=".$line[1]."WHERE `item`='".mysqli_real_escape_string($con, $line[0])."'";
			if(!mysqli_query($con, $query))
				echo mysqli_error($con)." | $query <br>";
			
		}
		$found[$line[0]]=true;
	}
	else{
		$query="INSERT INTO `items` (`item`, `price`) VALUES ('".mysqli_real_escape_string($con, $line[0])."',$line[1])";
		if(!mysqli_query($con, $query))
			echo mysqli_error($con)." | $query <br>";
	}
}

foreach($found as $item => $res){
	if(!$res){
		$query="DELETE FROM `items` WHERE `item`='$item'";
		if(!mysqli_query($con, $query))
			echo mysqli_error($con)." | $query <br>";
	}
}
echo "done";
?>