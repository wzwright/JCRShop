# JCRShop
Simple Point of Sale Software made for Magdalen College JCR

To use this, add the following files:
1. users.txt , the list of users, containing the name of one authorized user (seller) on each line
2. jcr.txt , the list of customers (JCR members in the original implementation) containing the name of one customer on each line
3. db.php , holds database credentials. The entire contents of this should be
<?php
$con=mysqli_connect("domain","username","password", "database name");
?>

Also needs minor database setup (schema should be pretty obvious, didn't bother to include a sql file in this repo)