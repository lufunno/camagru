
<form class="form_div col-lg-5 col-md-5 col-sm-11" method='post' action='update_info.php'>
	<p class='form_title'>
		My account information
	</p>
	<ul class="form">
		<li>
			<label> Name: </label>
			<input id="firstname" class="name" type="text" placeholder="First name" name="firstname" value=<?php echo $_SESSION['logged_on_user']->firstname; ?>>
			<input id="lastname" class="name" type="text" placeholder="Last name" name="lastname" value=<?php echo $_SESSION['logged_on_user']->lastname; ?>>
		</li>
		<li>
			<label> Username: </label>
			<input id="username" type="text" name="username" value=<?php echo $_SESSION['logged_on_user']->username; ?>>
		</li>
		<li>
			<label> Email address: </label>
			<input type="email" name="email" value=<?php echo $_SESSION['logged_on_user']->email; ?>>
		</li>
		<li>
			<input type='submit' name='submit' value='Save changes'>
		</li>
	</ul>
</form>

<form class="form_div col-lg-5 col-md-5 col-sm-11" method='post' action='update_info.php'>
	<p class='form_title'>
		My password
	</p>
	<ul class="form">
		<li>
			<label> Old Password: </label>
			<input id="old_password" type="password" name="old_password" required>
		</li>
		<li>
			<label> New Password: </label>
			<input id="password" type="password" name="new_password">
		</li>
		<li>
			<input type='submit' name='submit' value='Change password'>
		</li>
</form>