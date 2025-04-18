<?php
require_once '../config/db_connect.php';
require_once 'authFunctions.php';

logoutUser();
error_log("hotelfabio : disconnect user");
$encodedMessage = urlencode("SUCCES: Vous êtes maintenant déconnecté.");
header("Location: /hotelfabio/index.php?message=$encodedMessage");

?>