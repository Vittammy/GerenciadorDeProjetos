<?php

    require_once '../backend/Alocacao.php';
    require_once '../backend/Funcionario.php';
    require_once '../backend/Projeto.php';
    session_start();

    if (!isset($_SESSION['usuario_logado'])) {
        header('Location: login.php');
        exit();
    }

    $listaAlocacoes = Alocacao::buscarTodos();
    $listaFuncionarios = Funcionario::buscarTodos();
    $listaProjetos = Projeto::buscarTodos();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

    <div class="dashboard">
        
        <h1>Bem-vindo à Dashboard!</h1>

        <p>Selecione a página que deseja visitar:</p>

        <div class="caminhos">
            <button class="caminho"><a href="funcionarios.php">Funcionários</a></button>
            <button class="caminho"><a href="projetos.php">Projetos</a></button>
            <button class="caminho"><a href="alocacao.php">Alocações</a></button>
        </div>
        
        
        <div class="relatorios">
            <h2>Relatório</h2>

            <?php if (count($listaProjetos) > 0): ?>

                <table>
                    <thead>
                        <tr>
                            <th>Nome do Projeto</th>
                            <th>Funcionários Alocados</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($listaProjetos as $projeto): ?>
                            <tr>
                                <?php
                                    $funcionariosAlocadosNoProjeto = array_filter($listaAlocacoes, function($alocacao) use ($projeto) {
                                        return $alocacao->getIdProjeto() == $projeto->getId();
                                    });
                                    $countAlocacoes = count($funcionariosAlocadosNoProjeto);
                                ?>

                                <td><?php echo htmlspecialchars($projeto->getNomeProjeto()); ?></td>
                                <td><?php echo $countAlocacoes; ?></td>
                                <td><button class="btn_alocacao"><a href="alocacao.php">Ir para Alocações</a></button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
                
            <?php else: ?>
                <p>Nenhuma alocação cadastrada.</p>
            <?php endif; ?>
        </div>


        <button class="btn_sair"><a href="logout.php">Logout</a></button>
        
    </div>

</body>
</html>