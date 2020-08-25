<?php

require_once("User.class.php");

Class Database {

	public $dbh = NULL;

/*************** CONFIG - CONNEXION ***************************/

	private function initiate() {
	include("config/database.php");

		try {
		$this->dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		} catch (Exception $e) {
			die("Unable to connect: " . $e->getMessage() . $DB_DSN);
		}
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->dbh->beginTransaction();
	}

	private function endTransaction() {
		if ($this->dbh)
			$this->dbh->commit();
		$this->dbh = NULL;
	}

	public function build_table() {
		include('../config/database.php');
		try {
			$this->dbh = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		} catch (Exception $e) {
			die("Unable to connect: " . $e->getMessage());
		}
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->dbh->query("CREATE DATABASE IF NOT EXISTS camagru");
		$this->dbh->query("use camagru");
		$stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS Users (id INT PRIMARY KEY AUTO_INCREMENT, activate INT, username VARCHAR(255) NOT NULL, firstname VARCHAR(255), lastname VARCHAR(255), password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL , nb_photos INT DEFAULT 0, admin BOOLEAN)");
		$stmt->execute();
		$stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS Photos (user VARCHAR(10), user_id INT, name VARCHAR(255), comments VARCHAR(255), likes VARCHAR(1000))"); // default value null
		$stmt->execute();
		$stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS Comments (user_id VARCHAR(255), photo_id VARCHAR(255), comment VARCHAR(255), date DATE)"); // default value null
		$stmt->execute();
		$this->dbh = NULL;
	}

/******************* EMAILS ***********************************/

	private function email_available($email) {

		$stmt = $this->dbh->prepare("SELECT * FROM Users WHERE email=:email", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$users = $stmt->execute(array(':email' => $email));
		$nb_users = $stmt->rowCount();
		if ($nb_users > 0)
			return FALSE;
		return TRUE;
	}

	private function get_email($user) {
		$stmt = $this->dbh->prepare("SELECT email, firstname, activate FROM Users WHERE username=:username", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$account = $stmt->execute(array(':username' => $user->username));
		$account = $stmt->fetch(PDO::FETCH_ASSOC);
		// if ($account['activate'] == 1)
			// return NULL;
		return $account;
	}

	public function send_email($user, $message_txt, $subject) {
		if (!$user->email)
		{
			$account = $this->get_email($user);
			if ($subject == 'Activate your Camagru account' && $account['activate'] == 1)
					return false;
			$user->set_email($account['email']);
		}
		$newline = "\r\n";
		$name = ($account['firstname']) ? $account['firstname'] : $user->username;
		$message = $newline . $name . "," . $newline . $newline . $message_txt . $newline;
		$header = "From: Camagru team <noreply@camagru.com>" . $newline;
		$header .= "Content-Type: text/plain; charset=\"ISO-8859-1\"" . $newline;
		mail($user->email, $subject, $message, $header);
		return true;
	}

	public function send_activation_email($user) {

		if ($this->dbh == NULL)
			$this->initiate();
		$rand = rand();
		$message_txt = "activation link: http://localhost:8080/Camagru/index.php?activate="  . $rand . "&user=" . $user->username;
		$subject = "Activate your Camagru account";
		if ($this->send_email($user, $message_txt, $subject) == false)
			$this->endTransaction();
		else
		{
			$stmt = $this->dbh->prepare("UPDATE Users SET activate=:rand WHERE username=:username");
			$stmt->execute(array(':rand' => $rand, ':username' => $user->username));
			$this->endTransaction();
		}
	}


/************************** USER *****************************************/

public function auth_user($username, $password)
	{
		$this->initiate();
		$password = hash("whirlpool", $password);
		$stmt = $this->dbh->prepare("SELECT * FROM Users WHERE username=:username AND password=:password", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$users = $stmt->execute(array(':username' => $username, ':password' => $password));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user)
			return new User($user);
		else
		{
			$this->endTransaction();
			return NULL;
		}
	}

	// }

	private function username_available($username) {

		$stmt = $this->dbh->prepare("SELECT * FROM Users WHERE username=:username", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$users = $stmt->execute(array(':username' => $username));
		$nb_users = $stmt->rowCount();
		if ($nb_users > 0)
			return FALSE;
		return TRUE;
	}

	public function add_user($new_user) {

		$this->initiate();
		if ($this->email_available($new_user->email))
		{
			if ($this->username_available($new_user->username))
			{
				$new_user->set_password(hash("whirlpool", $new_user->password));
				$stmt = $this->dbh->prepare("INSERT INTO Users (username, password, email, activate) VALUES (:username, :password, :email, 0)");
				$stmt->bindParam(':username', $new_user->username);
				$stmt->bindParam(':password', $new_user->password);
				$stmt->bindParam(':email', $new_user->email);
				$stmt->execute();
				$this->send_activation_email($new_user);
			}
			else
			{
				$this->endTransaction();
				return -1;
			}
		}
		else
		{
			$this->endTransaction();
			return -2;
		}
	}

	public function activate_account() {
		$this->initiate();
		$stmt = $this->dbh->prepare("SELECT * FROM Users WHERE username=:username AND activate=:activate");
		$stmt->execute(array(':username' => $_GET['user'], ':activate' => $_GET['activate']));
		if ($stmt->rowCount() > 0)
		{
			$stmt = $this->dbh->prepare("UPDATE Users SET activate=1 WHERE username=:username AND activate=:activate");
			$stmt->execute(array(':username' => $_GET['user'], ':activate' => $_GET['activate']));
			header("Location: index.php?activate=1");
		}
		else
			header("Location: index.php?err=8");
		$this->endTransaction();
	}

	public function set_password($username, $new_password) {
		$this->initiate();
		print $username;
		print $new_password;
		$stmt = $this->dbh->prepare("UPDATE Users SET password=:password WHERE username=:username");
		$stmt->execute(array('password' => hash('whirlpool', $new_password), 'username' => $username));
		$this->endTransaction();
	}

	public function reinit_password($email) {
		$this->initiate();
		$stmt = $this->dbh->prepare("SELECT username FROM Users WHERE email=:email");
		$stmt->execute(array('email' => $email));
		$username = $stmt->fetch(PDO::FETCH_ASSOC)['username'];
		if($username) {
			$user = new User(array('username' => $username, 'email' => $email));
			$rand = rand();
			$this->set_password($username, $rand);
			$link = "http://localhost:8080/Camagru/index.php?help=ch_pw&user=" . $user->username . "&pw=" . $rand;
			$message = "Follow this link to reinitiate your password: " . $link;
			$this->send_email($user, $message, "Reinitiate your Camagru password");
		}
		else
			;//error
		$this->endTransaction();
	}

	private function set_username($user_id, $new_username) {
 		$stmt = $this->dbh->prepare("UPDATE Users SET username=:new_username WHERE id=:id");
		$stmt->execute(array(':new_username' => $new_username, ':id' => $user_id));
 	}

 	private function set_firstname($user_id, $new_firstname) {
 		$stmt = $this->dbh->prepare("UPDATE Users SET firstname=:new_firstname WHERE id=:id");
		$stmt->execute(array(':new_firstname' => $new_firstname, ':id' => $user_id));
 	}

 	private function set_lastname($user_id, $new_lastname) {
 		$stmt = $this->dbh->prepare("UPDATE Users SET lastname=:new_lastname WHERE id=:id");
		$stmt->execute(array(':new_lastname' => $new_lastname, ':id' => $user_id));
 	}

 	private function set_email($user_id, $new_email) {
 		$stmt = $this->dbh->prepare("UPDATE Users SET email=:new_email WHERE id=:id");
		$stmt->execute(array(':new_email' => $new_email, ':id' => $user_id));
		//$this->send_verification_email($_SESSION['logged_on_user']);
 	}

 	public function update_info($user_id, $info) {
 		$this->initiate();
 		foreach ($info as $key => $value) {
 			if ($key != 'submit')
 			{
 				// check if username already used
 				$function = 'set_' . $key;
 				if ($this->$function($user_id, $value) == -1)
 					; // error, return
 				$_SESSION['logged_on_user']->$function($value);
 			}
 		}
 		$this->endTransaction();
 		return 1;
 	}

 	/********************* PHOTOS *******************************/

 	public function add_photo($name, $id) {
 		$this->initiate();
 		$stmt = $this->dbh->prepare("INSERT INTO Photos (user, user_id, name) VALUES (:user, :id, :name)");
 		$stmt->execute(array(':id' => $_SESSION['logged_on_user']->id, ':user' => $id, ':name' => $name));
 		$stmt = $this->dbh->prepare("UPDATE Users SET nb_photos=nb_photos+1 WHERE id = :id");
 		$stmt->execute(array(':id' => $_SESSION['logged_on_user']->id));
 		$_SESSION['logged_on_user']->photos[] = array('name' => $name, 'id' => $id);
 		$_SESSION['logged_on_user']->add_photo();
 		$this->endTransaction();
 	}

 	public function delete_photo($name) {
 		$this->initiate();
 		$stmt = $this->dbh->prepare("DELETE FROM Photos WHERE name=:name AND user_id=:user_id");
 		$stmt->execute(array(':name' => $name, 'user_id' => $_SESSION['logged_on_user']->id));
 		$this->endTransaction();
 	}

 	public function count_photos() {
 		$this->initiate();
 		$stmt = $this->dbh->query("SELECT COUNT(*) FROM Photos");
 		$nb_photos = $stmt->fetch(PDO::FETCH_NUM);
 		$this->endTransaction();
 		return $nb_photos[0];
 	}

 	public function get_photos($page, $limit) {
 		$start = ($page - 1) * $limit;
 		$this->initiate();
 		$stmt = $this->dbh->prepare("SELECT * FROM Photos ORDER BY name LIMIT :start,:max");
 		$stmt->bindValue('max', $limit, PDO::PARAM_INT);
 		$stmt->bindValue('start', $start, PDO::PARAM_INT);
 		$stmt->execute();
 		$i = 0;
 		$photos = array();
 		while(($photo = $stmt->fetch(PDO::FETCH_ASSOC)))
		{
			$photos[$i] = $photo;
			$i++;
		}
 		$this->endTransaction();
 		return $photos;
 	}

 	public function get_user_photos($page, $limit) {
 		$start = ($page - 1) * $limit;
 		$this->initiate();
 		if ($limit > 0)
 		{
 			$stmt = $this->dbh->prepare("SELECT * FROM Photos WHERE user_id=:id ORDER BY name LIMIT :start, :max", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$stmt->bindValue('max', $limit, PDO::PARAM_INT);
 			$stmt->bindValue('start', $start, PDO::PARAM_INT);
 			$stmt->bindValue('id', $_SESSION['logged_on_user']->id);
			$stmt->execute();
		}
		else
		{
			$limit = 18;
 			$stmt = $this->dbh->prepare("SELECT * FROM Photos WHERE user_id=:id ORDER BY RAND() LIMIT :max", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$stmt->bindValue('max', $limit, PDO::PARAM_INT);
			$stmt->bindValue('id', $_SESSION['logged_on_user']->id);
			$stmt->execute();
		}
		$i = 0;
		$photos = array();
		while ($photo = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			 $photos[$i] = $photo;
			 $i++;
		}
		$this->endTransaction();
		return $photos;
	}


	/******* LIKES & COMMENTS *********/

	public function does_user_like($user, $photo){
		$this->initiate();
		$stmt = $this->dbh->prepare("SELECT likes FROM Photos WHERE name=:name", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$stmt->execute(array(':name' => $photo));
		$users = explode(':', $stmt->fetch(PDO::FETCH_ASSOC)['likes']);
		foreach ($users as $id)
		{
			if ($id == $user)
			{
				$this->endTransaction();
				return true;
			}
		}
		// print_r($users);
		$this->endTransaction();
		return false;
	}

	public function like_photo($photo, $user) {
		$this->initiate();
		$stmt = $this->dbh->prepare("UPDATE Photos SET likes=concat(coalesce(likes, :empty),:user)  WHERE name=:photo");
		$stmt->execute(array(':empty' => '', ':user' => $user, ':photo' => $photo));
		$stmt = $this->dbh->prepare('SELECT user FROM Photos WHERE name = :photo');
		$stmt->execute(array('photo' => $photo)); // here and in comment => function get_photo_user
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user['user'] != $_SESSION['logged_on_user']->username)
			$this->send_email(new User(array('username' => $user['user'])), $_SESSION['logged_on_user']->username . " liked your photo", "New like");
		$this->endTransaction();
	}

	public function unlike_photo($photo, $user){
		$this->initiate();
		$stmt = $this->dbh->prepare("UPDATE Photos SET likes=REPLACE(likes, :user, '') WHERE name=:photo");
		$stmt->execute(array(':user' => $user, ':photo' => $photo));
		$this->endTransaction();
	}

	public function get_nb_likes($photo) {
		$this->initiate();
		$stmt = $this->dbh->prepare("SELECT likes FROM Photos WHERE name=:name");
		$stmt->execute(array('name' => $photo));
		$likes = explode(':', $stmt->fetch(PDO::FETCH_ASSOC)['likes']);
		$nb_likes = count($likes);
		$this->endTransaction();
		return $nb_likes - 1;
	}

	public function get_comments($photo){
		$this->initiate();
		$i = 0;
		$comments = null;
		$stmt = $this->dbh->prepare("SELECT * FROM Comments WHERE photo_id=:name");
		$stmt->execute(array('name' => $photo));
		while ($comment = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$comments[$i] = $comment;
			$i++;
		}
		$this->endTransaction();
		return $comments;
	}

	public function add_comment($photo, $comment){
		$this->initiate();
		$stmt = $this->dbh->prepare("INSERT INTO Comments (photo_id, user_id, comment, date) VALUES (:photo, :user, :comment, NOW())");
		$stmt->execute(array('photo' => $photo, 'user' => $_SESSION['logged_on_user']->username, 'comment' => $comment));
		$stmt = $this->dbh->prepare('SELECT user FROM Photos WHERE name = :photo');
		$stmt->execute(array('photo' => $photo));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user['user'] != $_SESSION['logged_on_user']->username)
			$this->send_email(new User(array('username' => $user['user'])), $_SESSION['logged_on_user']->username . " commented your photo", "New comment");
		$this->endTransaction();
	}
}


// function delete_user($user_id) {
// 	$dbh = connect_database();
// 	$dbh->beginTransaction();
// 	$stmt = $dbh->prepare("DELETE FROM Users WHERE ID =:id");
// 	$stmt->execute(array(':id' => $user_id));
// 	$dbh->commit();
// 	close($dbh);
// 	echo "account successfully deleted";
// 	// delete pictures
// }

?>