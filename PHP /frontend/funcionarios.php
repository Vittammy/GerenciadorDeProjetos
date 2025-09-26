<?php

require_once '../backend/Funcionario.php';
session_start();

$funcionarios = [];
$nome_pesquisa = '';
$mensagem = '';
$exibindo_pesquisa = false;

if (isset($_GET['nome_pesquisa']) && !empty($_GET['nome_pesquisa'])) {

    $nome_pesquisa = $_GET['nome_pesquisa'] ?? '';

    $funcionarios = Funcionario::pesquisar($nome_pesquisa);

    $exibindo_pesquisa = true;

    if (empty($funcionarios)) {
        $mensagem = "Nenhum funcionário encontrado com o nome '" . htmlspecialchars($nome_pesquisa) . ".'";
    }
}


if (isset($_POST['cadastrar_funcionario'])) {

    $nome = $_POST['nome'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $salario = $_POST['salario'] ?? 0;

    $novoFuncionario = new Funcionario($nome, $cargo, $salario);

    if ($novoFuncionario->salvar()) {
        header('Location: funcionarios.php?status=success');
        exit();
    } else {
        $erro = "Falha ao cadastrar o funcionário.";
    }
}

if (isset($_GET['excluir_id'])) {

    $id = $_GET['excluir_id'];

    $funcionarioDeletado = new Funcionario('', '', 0.00);
    $funcionarioDeletado->setId($id);

    if ($funcionarioDeletado->deletar()) {
        header('Location: funcionarios.php?status=deleted');
        exit();
    } else {
        $erro = "Falha ao excluir o funcionário.";
    }
}

if (!$exibindo_pesquisa) {
    $funcionarios = Funcionario::buscarTodos();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <link rel="stylesheet" href="css/tabelas.css">
</head>
<body>

<div class="geral">

<div class="topo">
    <button class="btn_inicio"><a href="dashboard.php">Voltar para o início</a></button>

    <form class="form_pesquisar" action="funcionarios.php" method="GET">
        <input type="search" class="pesquisar" name="nome_pesquisa" 
                placeholder="Pesquise um funcionário..." >

        <button class="btn_pesquisar" type="submit">Pesquisar</button>
    </form>
</div>
    
    
    
    <div class="divisao">

        <div class="cadastro">
            <h1>Cadastre um Funcionário</h1>
            
                <form class="formulario" action="" method="POST">
                    <input type="hidden" name="cadastrar_funcionario" value="1">

                    Nome: <input type="text" name="nome">
                    Cargo: <input type="text" name="cargo">
                    Salário(R$): <input type="number" step="0.01" name="salario">

                    <button class="btn_salvar" type="submit">Cadastrar</button>
                </form>

        </div>
        

        <div class="tabela">
            <h1>Tabela de funcionarios</h1>

            <?php if (count($funcionarios) > 0): ?>

                <table>

                    <thead><tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Salário</th>
                            <th>Ações</th>
                    </tr></thead>

                    <tbody>
                        <?php foreach ($funcionarios as $fun): ?>
                            <tr>
                                <td><?php echo $fun->getId(); ?></td>
                                <td><?php echo $fun->getNome(); ?></td>
                                <td><?php echo $fun->getCargo(); ?></td>
                                <td>R$ <?php echo number_format($fun->getSalario(), 2, ',', '.'); ?></td>
                                <td>
                                    <div class="acoes">
                                        <button class="btn_editar">
                                            <a href="editar_funcionario.php?id=<?php echo $fun->getId(); ?>"
                                            >Editar</a>
                                        </button> 
                                        
                                        <button class="btn_deletar">
                                            <a href="funcionarios.php?excluir_id=<?php echo $fun->getId(); ?>"
                                                onclick="return confirm('Tem certeza que deseja excluir este funcionário?');"
                                                >Excluir</a>
                                        </button>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            <?php else: ?>
                <p>Nenhum funcionário cadastrado.</p>
            <?php endif; ?> 
        </div>

    </div>

    
    <br><hr><br>

    <!-- Mensagem de Sucesso -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert success">Funcionário cadastrado com sucesso!</div>
    <?php endif; ?>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="alert success">Funcionário excluído com sucesso!</div>
    <?php endif; ?>

    <?php if (isset($erro)): ?>
        <div class="alert error"><?php echo $erro; ?></div>
    <?php endif; ?>

    <!-- Mensagem de pesquisa -->
    <?php if (!empty($mensagem)): ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>

</div>



</body>

</html>