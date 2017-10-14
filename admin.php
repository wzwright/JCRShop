<?php
if(!isset($_SESSION['user'])||strcmp($_SESSION['user'], 'shopmaster')!=0)
{
	header("Location:./");
	return;
}
$customers=[];
$customerText=file("./jcr.txt");
foreach($customerText as $customer)
	$customers[]=trim($customer);
$users=file("./users.txt");
?>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="../../bootstrap.min.css" rel="stylesheet">
<script>
var xmlhttp = new XMLHttpRequest();
var xmlhttp2 = new XMLHttpRequest();
window.onload = function(e){
	$("#search").autocomplete({
		source: <?php echo json_encode($customers); ?>,
		minLength: 2,
		select: function(e, ui){
			var req = "./purchases.php?c="+ui.item.value;
			req+="&d="+$("#dt").val();
			xmlhttp.open("GET", req);
			xmlhttp.send();
		}
	});
	$("#users").val(<?php echo "'".implode('\r\n',array_map("trim", $users))."'";?>);
}
xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		$("#res").html(xmlhttp.responseText);
	}
}

function update(){
	xmlhttp2.open("GET", "./updateUsers.php?u="+encodeURI($("#users").val()));
	xmlhttp2.send();
}
xmlhttp2.onreadystatechange=function() {
	if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
		if(xmlhttp2.responseText=="success"){
			$("#updateUsers").text("Success!");
			setTimeout(function(){$("#updateUsers").text("update")}, 2000);
		}
		else
			alert("failure: " +xmlhttp2.responseText);
	}
}
</script>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<h3>Search Transactions</h3>
			Purchases made after: (American format)<br><input id="dt" type="date" value="2017-04-01">
			<input style="margin-top:3px;" id="search" placeholder="customer">		
		</div>
		<div class="col-md-6">
			<h3>Results</h3>
			<div id="res"></div>
		</div>
		<div class="col-md-3">
			<h3>Edit Employees</h3>
			<textarea id="users" rows="10" cols="20"></textarea>
			<p><button id="updateUsers" onclick="update()" style="margin-top:10px;">update</button></p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<h3>Purchase Summary</h3>
			<form action="./summary.php" method="post">
				<label style="width:35px;">From</label> <input name="dtBegin" type="date" value=<?php echo '"'.date("Y-m-d").'"';?>><br>
				<label style="width:35px;">To</label> <input name="dtEnd" type="date" value=<?php echo '"'.date("Y-m-d").'"';?>> (the day after the last purchase)
				<input style="margin-left:38px;" type="submit" value="prepare summary">
			</form>
		</div>
		<div class="col-md-5">
			<h3>Update Items</h3>
			Replaces the list of items and prices<br>Submit a csv file with two columns, the first of items and the second of prices<br>
			Note that this will change existing prices and delete items that are no longer in the list<br>
			Also, foreign characters (e.g. ê) may cause an item not to appear<br>
			<form action="updateItems.php" enctype="multipart/form-data" method="post" style="margin-top:5px;">
				<input type="file" name="uploadFile">
				<input style="margin-top:3px;" type="submit" value="Update Items">
			</form>
		</div>
	</div>
</div>