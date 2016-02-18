<?php
	$id = htmlspecialchars($_POST['id']);
	$password = htmlspecialchars($_POST['password']);
	echo "$id is the id of the user and $password is the password";
	if(pam_auth($id,$password,&$error)){
		echo "Success";
	}
	else{
		echo "Fail: $error"
	
	
?>
