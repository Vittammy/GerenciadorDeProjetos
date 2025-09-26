<?php

    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        require_once '../backend/Usuario.php';

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuario_id = Usuario::autenticar($email, $senha);

        if ($usuario_id) {

            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $usuario_id;
            header('Location: dashboard.php');
            exit();

        } else {
            $erro_login = "Email ou senha inválidos.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="login">

        <h1>Login</h1>

        <?php if (isset($erro_login)): ?>
            <p style="color: red;"><?php echo $erro_login; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            E-mail: <input type="email" name="email">
            Senha: <input type="password" name="senha">

            <button>Entrar</button>
        </form>

    </div>

    
</body>
</html>