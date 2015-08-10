<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
include "func.php";
session_start();
if(!empty($_SESSION["glastree_user_code"])) {
?>


<?php 
$array = users_getDetailByCode($_SESSION["glastree_user_code"])

?>

<h2>Welcome back <?php echo $array["name"]?></h2>

<p>User ID: <?php echo $array["user_id"]?></p>
<p>User Email: <?php echo $array["email"]?></p>
<p>User Code: <?php echo $array["code"]?></p>
<p>Reg Date: <?php echo $array["regdate"]?></p>

<?php
$array = json_decode(books_query(0));

foreach ($array as $row) {
?>
<tr>
<td><?php echo $row["book_id"]; ?></td>
<td><?php echo $row["name"]; ?></td>
<td><?php echo $row["season"]; ?></td>
<td><?php echo $row["pubdate"]; ?></td>
<td><?php echo $row["price"]; ?></td>
<td><?php echo $row["path"]; ?></td>
</tr>
<?php 
}
?>


<?php
}else if (!empty($_POST['name']) && !empty($_POST['password'])) {
	$name = $_POST['name'];
	$password = $_POST['password'];
	$code = users_getCode($name,$password);
	$_SESSION["glastree_user_code"] = $code;
	//header('Location: index.php');
}else{
?>
<form action="index.php" method="post">
	<h1>Glastree</h1>
	<br/>
	Name:
	<br/>
	<input name="name" type="text" />
	<br/>
	Password:
	<br/>
	<input name="password" type="password" />
	<br/>
	<input name="submit" type="submit" />
</form>
<?php 
}
?>

