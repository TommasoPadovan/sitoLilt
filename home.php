<?php
require_once('lib/generalLayout.php');
require_once('lib/sqlLib.php');
$conn=connect();


//general layout of one page
$generalLayout = new GeneralLayout("home.php");

//setting the title
$generalLayout->yieldElem('title', "Lilt Home");




$welcome='';
if ( isset($_POST['logout']) ) {
	unset($_SESSION['id']);
	unset($_SESSION['permessi']);
}
if ( isset( $_SESSION['id'] ) ) {
	$welcome = welcomeMsg($_SESSION['id'], $conn);
} else {
	if ( isset($_POST['login']) && isset($_POST['Email']) && isset($_POST['Password']) ) {
		$allUsers=array();
		$users=queryThis("SELECT * FROM users", $conn);
		for ($i=0; $i<mysql_num_rows($users) ; $i++) {
			$row=mysql_fetch_assoc($users);
			if ($row['email'] == $_POST['Email']) {
				if ($row['psw'] == md5($_POST['Password'])) {
					$_SESSION['id'] = $row['id'];
					$_SESSION['permessi'] = $row['permessi'];
					$welcome = welcomeMsg($_SESSION['id'], $conn);
				}
			}
		}
	}
}


$content = <<<HTML
<h1>Home</h1>
<hr />
<div>
	$welcome
</div>
<hr />
<h2>Login</h2>
<form action='#' method="POST">
	<div class="form-group">
		<label for="Email">Email</label>
		<input type="text" class="form-control" id="Email" placeholder="Email" name="Email">
	</div>
	<div class="form-group">
		<label for="Password">Password</label>
		<input type="password" class="form-control" id="Password" placeholder="Password" name="Password">
	</div>
	<button type="submit" class="btn btn-default" name="login" value="login">Submit</button>
	<button type="submit" class="btn btn-default" name="logout" value="logout">Logout</button>
</form>
HTML;
$generalLayout->yieldElem('content', $content);

$generalLayout->pprint();










function welcomeMsg($id, $conn) {
	$user=queryThis("SELECT * FROM users WHERE id=$id" , $conn);
	$row = mysql_fetch_assoc($user);
	return "Ciao {$row['lastname']}";
}
?>