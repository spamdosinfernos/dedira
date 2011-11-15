<?php
/*
 * {
    success: true,
    users: [
        {id: 1, name: 'Ed',    email: 'ed@sencha.com'},
        {id: 2, name: 'Tommy', email: 'tommy@sencha.com'}
    ]
}
 */

$var0 = array(
"success" => true,
"users" => array(
	array("id" => 1, "name" => "ed", "email" => "ed@sencha.com"),
	array("id" => 2, "name" => "Tommy", "email" => "tommy@sencha.com")
)
);

$json = json_encode($var0);

//$json = file_get_contents('users.json');

$var = json_decode($json);
?>