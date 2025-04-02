<?php
session_start();
session_unset();
session_destroy(); // Destroi todas as sessões
header("Location: index.html"); // Redireciona para a página de login
exit;
?>
