<?php

require_once '../backend/Alocacao.php';
require_once '../backend/Funcionario.php';
require_once '../backend/Projeto.php';
session_start();


$alocacoes = [];
$nome_pesquisa = '';
$mensagem = '';
$exibindo_pesquisa = false;

if (isset($_GET['nome_pesquisa']) && !empty($_GET['nome_pesquisa'])) {

    $nome_pesquisa = $_GET['nome_pesquisa'] ?? '';

    $alocacoes = Alocacao::pesquisar($nome_pesquisa);

    $exibindo_pesquisa = true;

    if (empty($alocacoes)) {
        $mensagem = "Nenhum projeto encontrado com o nome '" . htmlspecialchars($nome_pesquisa) . ".'";
    }
}


if (isset($_POST['cadastrar_alocacao'])) {

    $id_funcionario = $_POST['id_funcionario'] ?? 0;
    $id_projeto = $_POST['id_projeto'] ?? 0;

    $novaAlocacao = new Alocacao($id_funcionario, $id_projeto);

    if ($novaAlocacao->salvar()) {
        header('Location: alocacao.php?status=success');
        exit();

    } else {
        $erro = "Falha ao cadastrar a alocação.";
    }
}


if (isset($_GET['excluir_id'])) {

    $id = $_GET['excluir_id'];
    $alocacaoDeletada = new Alocacao(0, 0);
    $alocacaoDeletada->setId($id);

    if ($alocacaoDeletada->deletar()) {
        header('Location: alocacao.php?status=deleted');
        exit();

    } else {
        $erro = "Falha ao excluir a alocação.";
    }
}

if (!$exibindo_pesquisa) {
    $alocacoes = Alocacao::buscarTodos();
}

$listaFuncionarios = Funcionario::buscarTodos();
$listaProjetos = Projeto::buscarTodos();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alocação de Projetos</title>
    <link rel="stylesheet" href="css/tabelas.css">
</head>

<body>

    <div class="geral">

        <div class="topo">
            <button class="btn_inicio"><a href="dashboard.php">Voltar para o início</a></button>

            <form class="form_pesquisar" action="alocacao.php" method="GET">
                <input type="search" class="pesquisar" name="nome_pesquisa" 
                        placeholder="Digite um funcionário ou projeto alocado..." >

                <button class="btn_pesquisar" type="submit">Pesquisar</button>
            </form>
        </div>

        <div class="divisao">

            <div class="cadastro">
                <h1>Cadastre uma Alocação</h1>

                <form class="formulario" action="" method="POST">

                    <input type="hidden" name="cadastrar_alocacao" value="1">

                    Funcionário: 
                    <select name="id_funcionario">
                        <?php foreach ($listaFuncionarios as $funcionario): ?>
                            <option value="<?php echo $funcionario->getId(); ?>">
                                <?php echo htmlspecialchars($funcionario->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    Projeto:
                    <select name="id_projeto">
                        <?php foreach ($listaProjetos as $projeto): ?>
                            <option value="<?php echo $projeto->getId(); ?>">
                                <?php echo htmlspecialchars($projeto->getNomeProjeto()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button class="btn_salvar" type="submit">Alocar</button>

                </form>

            </div>

            <div class="tabela">

                <h2>Tabela de Alocações</h2>

                <?php if (count($alocacoes) > 0): ?>

                    <table>
                        <thead>
                            <tr>
                                <th>ID Alocação</th>
                                <th>Nome do Funcionário</th>
                                <th>Nome do Projeto</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($alocacoes as $aloc): ?>
                                <tr>
                                    <td><?php echo $aloc->getId(); ?></td>
                                    <td><?php echo htmlspecialchars($aloc->getFuncionario()->getNome()); ?></td>
                                    <td><?php echo htmlspecialchars($aloc->getProjeto()->getNomeProjeto()); ?></td>
                                    <td>
                                        <button class="btn_deletar">
                                            <a href="alocacao.php?excluir_id=<?php echo $aloc->getId(); ?>"
                                                onclick="return confirm('Tem certeza que deseja excluir esta alocação?');"
                                                >Excluir</a>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>

                <?php else: ?>
                    <p>Nenhuma alocação cadastrada.</p>
                <?php endif; ?>

            </div>

        </div>


        <br>
        <hr><br>

        <!-- Mensagem de Sucesso -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert success">Alocação cadastrada com sucesso!</div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert success">Alocação excluída com sucesso!</div>
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