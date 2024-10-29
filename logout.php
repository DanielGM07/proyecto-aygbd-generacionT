<?php
unset($_COOKIE['usuario_logeado']);
setcookie('usuario_logeado', '', time() - 3600);
header('Location: index.php');
?>