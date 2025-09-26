<?php

    require_once '../backend/Projeto.php';
    session_start();

    $opcoes_status = ['Em Andamento', 'Concluído', 'Pendente', 'Cancelado'];

    if (!isset($_GET['id'])) {
        header('Location: projetos.php');
        exit();
    }

    $id = $_GET['id'];
    $projeto = Projeto::buscarPorId($id);

    if (!$projeto) {
        header('Location: projetos.php?erro=nao_encontrado');
        exit();
    }

    if (isset($_POST['atualizar_projeto'])) {

        $nome = $_POST['nome_projeto'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $status = $_POST['status_projeto'] ?? 0;

        $projeto -> setNomeProjeto($nome);
        $projeto -> setDescricao($descricao);
        $projeto -> setStatusProjeto($status);

        if ($projeto -> atualizar()) {
            header('Location: projetos.php?status=updated');
            exit();
        } else {
            $erro = "Falha ao atualizar o projeto.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Projeto</title>
    <link rel="stylesheet" href="css/tabelas.css">
</head>
<body>

    <div class="geral">

        <button class="btn_inicio"><a href="dashboard.php">Voltar para o início</a></button>

    <div class="edicao">
        <h1>Editar Projeto</h1>

        <form class="formulario" action="editar_projeto.php?id=<?php echo $projeto->getId(); ?>" method="POST">
            <input type="hidden" name="atualizar_projeto" value="1">

            Nome: <input type="text" name="nome_projeto" 
                    value="<?php echo htmlspecialchars($projeto->getNomeProjeto()); ?>">

            Descrição: <input type="text" name="descricao" 
                    value="<?php echo htmlspecialchars($projeto->getDescricao()); ?>">

            Status: 
            <select name="status_projeto">
                <?php foreach ($opcoes_status as $status): ?>
                    <option value="<?php echo $status; ?>" 
                            <?php echo ($projeto->getStatusProjeto() == $status) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($status); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="btn_salvar">Atualizar</button>
        </form>

    </div>


    <br><hr><br>
    <div class="btn_inicio" style="width: 10%;"><a href="projetos.php">Voltar para a lista</a></div>

    
    <?php if (isset($erro)): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>

    </div>
    
</body>
</html>