<?php

    session_start();

    // Destroi váriaveis de sessão
    session_unset();

    // Destroi A sessão
    session_destroy();

    header('Location: login.php');
    exit();

?>