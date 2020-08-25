<?php

require_once('Database.class.php');

Class User {

	public $id;
	public $username = NULL;
	public $email = NULL;
	public $firstname = NULL;
	public $lastname = NULL;
	public $password;
	public $active = 0;
	public $nb_photos = 0;
	private $admin = FALSE;
	public $photos;

	public function __construct($user) {
		foreach ($user as $key => $value)
			$this->$key = $value;
		// if (!$this->username)
		// 	$this->username = $this->email;
		$db = new Database();
		// $photos = $db->get_user_photos($this->id);
	}

//	public function _set($key, $value) {

//}

	public function create_user_folder(){
		$folder_name = 'img/user/' . $this->id;
		mkdir($folder_name);
	}

	public function set_username($username){

		$this->username = $username;
	}

	public function set_email($email){

		$this->email = $email;
	}

	public function set_firstname($firstname){

		$this->firstname = $firstname;
	}

	public function set_lastname($lastname){

		$this->lastname = $lastname;
	}

	public function set_password($password) {
		$this->password = $password;
	}

	public function set_active($value)
	{
		$this->active = $value;
	}

	public function set_admin($value)
	{
		$this->admin = $value;
	}

	public function set_nb_photos($value)
	{
		$this->nb_photos = $value;
	}

	public function add_photo() {
		$this->nb_photos++;
	}

	public function delete_photo() {
		$this->nb_photos--;
	}

	// public function get_username(){

	// 	return $this->username;
	// }

	// public function get_email(){

	// 	return $this->email;
	// }

	// public function get_firstname(){

	// 	return $this->firstname;
	// }

	// public function get_lastname(){

	// 	return $this->lastname;
	// }

	// public function get_password() {
	// 	return $this->password;
	// }

// function set_activate($code){}
}
?>