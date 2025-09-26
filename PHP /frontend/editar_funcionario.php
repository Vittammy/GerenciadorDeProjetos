<?php

require_once '../backend/Funcionario.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: funcionarios.php');
    exit();
}

$id = $_GET['id'];
$funcionario = Funcionario::buscarPorId($id);

if (!$funcionario) {
    header('Location: funcionarios.php?erro=nao_encontrado');
    exit();
}

if (isset($_POST['atualizar_funcionario'])) {

    $nome = $_POST['nome'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $salario = $_POST['salario'] ?? 0;

    $funcionario->setNome($nome);
    $funcionario->setCargo($cargo);
    $funcionario->setSalario($salario);

    if ($funcionario->atualizar()) {
        header('Location: funcionarios.php?status=updated');
        exit();
    } else {
        $erro = "Falha ao atualizar o funcionário.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="css/tabelas.css">
</head>

<body>

    <div class="geral">

        <button class="btn_inicio"><a href="dashboard.php">Voltar para o início</a></button>

    <div class="edicao">
        <h1>Editar Funcionário</h1>

        <form class="formulario" action="editar_funcionario.php?id=<?php echo $funcionario->getId(); ?>" method="POST">
            <input type="hidden" name="atualizar_funcionario" value="1">

            Nome: <input type="text" name="nome" value="<?php echo htmlspecialchars($funcionario->getNome()); ?>">

            Cargo: <input type="text" name="cargo" value="<?php echo htmlspecialchars($funcionario->getCargo()); ?>">

            Salário(R$): <input type="number" step="0.01" name="salario"
                value="<?php echo htmlspecialchars($funcionario->getSalario()); ?>">

            <button class="btn_salvar">Atualizar</button>
        </form>

    </div>


    <br><hr><br>
    <div class="btn_inicio" style="width: 10%;"><a href="funcionarios.php">Voltar para a lista</a></div>

    
    <?php if (isset($erro)): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>

    </div>
    
</body>

</html>