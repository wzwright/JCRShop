<?php
if(!isset($_SESSION['user']))
{
	header("Location:./");
	return;
}
require_once('./db.php');
$items=[];
$query="SELECT `item`, `price` FROM `items`";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result)){
	$items[]=$row;

$customers=[];
$customerText=file("./jcr.txt");
foreach($customerText as $customer)
	$customers[]=$customer;
}
?>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="../../bootstrap.min.css" rel="stylesheet">
<script>
var items=undefined;
var customers=undefined;
var autoItems=Array();
var priceDict={};
var cart={};
var inProgress=false;
window.onload = function(e){
	items=<?php echo json_encode($items);?>;
	customers=<?php echo json_encode($customers);?>;
	items.forEach(function(i){		
		i.price=parseFloat(i.price);
		autoItems.push({label:(i.item+" - "+i.price.toFixed(2)), value:i.item});		
		priceDict[i.item]=i.price;
	});
	$("#search").autocomplete({
		source: autoItems,
		select: function(e, ui){
			add(ui.item.value);
			e.preventDefault()
			$("#search").val('');
		}
	});
	$("#cust").autocomplete({
		source:customers,
		minLength:2
	});
}

function add(name){
	if(name in cart)
		cart[name]+=parseFloat($('#num').val());
	else
		cart[name]=parseFloat($('#num').val());
	refreshCart();
}

function remove(name){
	if(name in cart)
		delete cart[name];
	refreshCart();
}

function refreshCart(){
	var res = ""
	var total=0;
	for(var key in cart){
		if(!cart.hasOwnProperty(key))
			continue;
		var price=priceDict[key]*cart[key];
		total+=price;
		res+="<p>"+cart[key]+" "+key+": "+price.toFixed(2);
		res+=" <a href='#' onClick='remove(\""+key+"\")'>remove</a></p>";
	}
	res+="Total: "+total.toFixed(2);
	$("#cart").html(res);
}

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		if(xmlhttp.responseText=="success"){
			cart={};
			refreshCart();
			$("#sendButton").text("Success!");
			$("#sendButton").css("background-color","chartreuse");
			$("#cust").val("");
			setTimeout(function(){$("#sendButton").text("Submit");	$("#sendButton").css("background-color","#DDD");}, 2000);
			inProgress=false;
		}
		else{
			alert("failed to submit");
		}
	}
}
function sendOrder(){
	if(inProgress)
		return;
	inProgress=true;
	$("#sendButton").text("waiting...");
	$("#sendButton").css("background-color","yellow");
	var customer = $("#cust").val()
	var dest = "./shopBackend.php?cust="+customer+"&items=";
	var itemPairs=[]
	for(var key in cart){
		if(!cart.hasOwnProperty(key))
			continue;
		itemPairs.push(key+"~"+cart[key]);
	}
	dest+=(itemPairs.join("|")).replace('&', '{');
	xmlhttp.open("GET", dest);
	xmlhttp.send();
}
</script>
<style>
table{
	border-collapse: collapse;
}
table, td{
	border:1px solid black;
	padding:3px;
}
</style>

<div class="container">
	<h2><?php if($_SESSION['user']=='fajita'){echo 'Hope your day\'s been going nicely, Dave!';} else{echo 'JCR Shop Dashboard';}?></h2>
	<div class="col-md-4">
		<h3>Cart</h3><div id="cart"><p>Total: 0.00</p></div>
	</div>
	<div class="col-md-4">
		<h3>Add to Cart</h3>
		<p>qty <input id="num" type="number" min="1" max="99" value="1"></p>
		<p><input id="search" placeholder="search"></p>
	</div>
	<div class="col-md-4">
		<h3>Send Order</h3>
		<p><input id="cust" placeholder="customer"></p>
		<button id="sendButton" onclick="sendOrder()">Submit</button>
	</div>
</div>