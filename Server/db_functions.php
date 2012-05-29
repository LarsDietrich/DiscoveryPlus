<?php
	function db_connect($host, $dbname, $user, $pass){
		try{
			$bdd = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $pass);
		}
		catch (Exception $e){
			$bdd = -1;
		}
		
		return $bdd;
	}
	
	function new_user($bdd, $username, $passwd, $fname, $lname, $age, $gender, $interests){
	
		$req = $bdd->prepare('INSERT INTO userData(username, pass_sha256, fname, lname, age, gender, interests) VALUES(:usr, :pass, :f, :l, :age, :gender, :intr)');
		$req->execute(array(
			'usr' => $username,
			'pass' => $passwd,
			'f' => $fname,
			'l' => $lname,
			'age' => $age,
			'gender' => $gender,
			'intr' => $interests));
			
		return 1;
	}
