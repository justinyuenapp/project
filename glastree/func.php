<?php

function connectDB(){
	$servername = "localhost";
	$username = "glastree";
	$password = "321321";
	$dbname = "glastree";
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		 die("Connection failed: " . $conn->connect_error);
	}
	$conn->set_charset("utf8");
	return $conn;
}



function users_query($user_id){
	$conn = connectDB();
	
	if($user_id == 0){
		$stmt = $conn->prepare("SELECT user_id,name,email,code,regdate FROM users");
	}
	else{
		$stmt = $conn->prepare("SELECT user_id,name,email,code,regdate FROM users WHERE user_id = ?");
		$stmt->bind_param("s", $user_id);
	}

	$stmt->execute();
	$stmt->bind_result($user_id,$name,$email,$code,$regdate);
	for($i=0;$stmt->fetch();$i++)
	{
		$array[$i] = array();
		$array[$i]["user_id"] = $user_id;
		$array[$i]["name"] = $name;
		$array[$i]["email"] = $email;
		$array[$i]["code"] = $code;
		$array[$i]["regdate"] = $regdate;
	}
	echo json_encode($array);
}

function users_getCode($name,$password){
	$conn = connectDB();

	$stmt = $conn->prepare("SELECT code FROM users WHERE name = ? AND password = ?");
	$stmt->bind_param("ss", $name,$password);

	$stmt->execute();
	$stmt->bind_result($code);
	while ($stmt->fetch()) {
		$code_str = $code;
		$stmt->close();
		return $code_str;
	}
	return 0;
}

function users_getDetailByCode($code){
	$conn = connectDB();

	$stmt = $conn->prepare("SELECT user_id, name, email, code, regdate FROM users WHERE code = ?");
	$stmt->bind_param("s", $code);

	$stmt->execute();
	$stmt->bind_result($user_id,$name,$email,$code,$regdate);
	while ($stmt->fetch()) {
		$array = array(
				"user_id" => $user_id,
				"name" => $name,
				"email" => $email,
				"code" => $code,
				"regdate" => $regdate
		);
		return $array;
	}
}

function books_query($book_id){
	$conn = connectDB();
	
	$stmt = $conn->prepare("SELECT book_id,name,season,pubdate,price,path FROM books WHERE book_id = ?");
	$stmt->bind_param("s", $book_id);
	
	
	if($book_id == 0){
		$stmt = $conn->prepare("SELECT book_id,name,season,pubdate,price,path FROM books");
	}
	
	$stmt->execute();
	$stmt->bind_result($book_id,$name,$season,$pubdate,$price,$path);
	for($i=0;$stmt->fetch();$i++)
	{
		$array[$i] = array();
		$array[$i]["book_id"] = $book_id;
		$array[$i]["name"] = $name;
		$array[$i]["season"] = $season;
		$array[$i]["pubdate"] = $pubdate;
		$array[$i]["price"] = $price;
		$array[$i]["path"] = $path;
	}
	return json_encode($array);
}

function userbook_query($user_id){
	$conn = connectDB();
	
	if($user_id == 0){
		$stmt = $conn->prepare("SELECT user_id,book_id,buydate FROM userbook");
	}
	else{
		$stmt = $conn->prepare("SELECT user_id,book_id,buydate FROM userbook WHERE user_id = ?");
		$stmt->bind_param("s", $user_id);
	}
	

	$stmt->execute();
	$stmt->bind_result($user_id,$book_id,$buydate);
	for($i=0;$stmt->fetch();$i++)
	{
		$array[$i] = array();
		$array[$i]["user_id"] = $user_id;
		$array[$i]["book_id"] = $book_id;
		$array[$i]["buydate"] = $buydate;
	}
	echo json_encode($array);
}

function addUser($name,$password,$email,$code,$regdate){
	$conn = connectDB();
	
	$stmt = $conn->prepare("INSERT INTO Works VALUES(?,?,?,?,?)");
	$stmt->bind_param("sssss",$name,$password,$email,$code,$regdate);

	$stmt->execute();
}

function addBook($name,$season,$pubdate,$price){
	$conn = connectDB();
	
	$stmt = $conn->prepare("INSERT INTO books VALUES(?,?,?,?,?)");
	$stmt->bind_param("sssss",$name,$season,$pubdate,$price,$name+"_"+$season+".pdf");
	
	$stmt->execute();
}

function buyBook($user_id,$book_id){
	$conn = connectDB();
	
	//Buy Book
	$stmt = $conn->prepare("INSERT INTO userbook VALUES(?,?,NOW())");
	$stmt->bind_param("ss",$user_id,$book_id);

	$stmt->execute();
	
	//Buy relative Work
	$stmt = $conn->prepare("SELECT work_id FROM BookWork WHERE book_id = ?");
	$stmt->bind_param("s", $book_id);
	$stmt->execute();
	
	$stmt->bind_result($work_id);
	while ($stmt->fetch()) {
		$stmt = $conn->prepare("INSERT INTO UserWork VALUES(?,?,NOW())");
		$stmt->bind_param("ss",$user_id,$work_id);
		$stmt->execute();
	}	
}

?>  