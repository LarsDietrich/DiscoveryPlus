<?php
header("Content-type: application/json");

function create_error($emsg = "there has been an error"){
	echo "<div style=\"font-family:Calibri;font-size:2em;\"><h1 style=\"margin:0\">DiscoveryPlus</h1><h2 style=\"margin:0\">Sorry, ".$emsg.".</h2><p><a href=\"http://codethree.lu/\">Code Three</a></p></div>";
	exit();
}

//MYSQL START
function mye($q){mysql_real_escape_string($q);}
function db_connect(){
	mysql_connect($p["mysql"]["host"], $p["mysql"]["user"], $p["mysql"]["pass"]) or create_error("there is a problem with our database");
	mysql_select_db($p["mysql"]["dtbs"]) or create_error("there is a problem connecting to the database");
	//This function is way simpler than yours. Keep your's though, it might be a good idea if we switch to a NoSQL database, which I really want.	
}
function add_user($uname, $pass, $age, $fname, $lname, $gender, $interests){
	mysql_query("INSERT INTO example (username, pass_sha256, fname, lname, age, gender, interests) VALUES('".mye($uname)."', '".mye($pass)."', '".mye(fname)."', '".mye($lname)."', '".mye($age)."', '".mye($gender)."', '".mye($interests)."') ") or create_error("there has been a problem creating a new user");  
}
function login_user($uname, $pass){
	mysql_query("SELECT *  FROM 'userData' WHERE 'username' = '".mye($uname)."' && pass_sha256 = '".hash('sha256', $mye(pass))."'");
}
//MYSQL END

function sessionKey($length = 12){
	$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$randomString = "";
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];	
	}
	return $randomString;
}

if(file_exists("properties.json")){
	$p = json_decode(file_get_contents("properties.json"), true);
}else{
	create_error("couldn't find validation file");
}

$open = !$p['private'];

/*REMEMBER FIRST TIME INFO SEND */
if(array_key_exists('i', $_GET) && $open){echo json_encode($p['info']);}

/* SIGNUP */
if($open && array_key_exists('s', $_GET)){
	$su = json_decode($_GET['s'], true);
	//Data should be sent like this:                                    (null if optional)
		// {"uname":"username","pass":"p455w0rd","age":21,"fname":"Bob","lname":null,"gender":"m","interests":{"eating":9,"drinking":10}}
	add_user($su['uname'], hash('sha256', $su['pass']), $su['age'], $su['fname'], $su['lname'], $su['gender'], json_encode($su['interests']));
}

/* LOGIN */
if($open && array_key_exists('l', $_GET)){
	login_user($_GET['l']['uname'], $_GET['l']['pass']);
}

?>