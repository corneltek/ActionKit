

CurrentUser
===========
in config.yml

	current_user:
		model: ...
		class: ...

make a login:

	$user = new \User\Model\User;
	$user->load( 12 );

	$cUser = new \Phifty\CurrentUser( $user );
	$cUser->isLogged();  // is logged in ?

	$cUser->logout();    // log out:w

make sure we are logged in:

	$cUser = new \Phifty\CurrentUser;
	$cUser->isLogged();


