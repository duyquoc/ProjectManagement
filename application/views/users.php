<?php



$users = $request->body;
$parsed_json = json_decode($users,TRUE);
foreach ($parsed_json as $key => $value) {
	echo 'ID => '.$value['id'].'<br>';
	echo 'USERNAME => '.$value['username'].'<br>';
	echo 'PASSWORD => '.$value['password'].'<br>';
	echo 'EMAIL => '.$value['email'].'<br>';
	echo '---------------------<br>';
}
//echo $users;

?>