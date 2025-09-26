<?php

require_once '../backend/Projeto.php';
session_start();

$opcoes_status = ['Em Andamento', 'Concluído', 'Pendente', 'Cancelado'];

$projetos = [];
$nome_pesquisa = '';
$mensagem = '';
$exibindo_pesquisa = false;

if (isset($_GET['nome_pesquisa']) && !empty($_GET['nome_pesquisa'])) {

    $nome_pesquisa = $_GET['nome_pesquisa'] ?? '';

    $projetos = Projeto::pesquisar($nome_pesquisa);

    $exibindo_pesquisa = true;

    if (empty($projetos)) {
        $mensagem = "Nenhum projeto encontrado com o nome '" . htmlspecialchars($nome_pesquisa) . ".'";
    }
}

if (isset($_POST['cadastrar_projeto'])) {

    $nome = $_POST['nome_projeto'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $status = $_POST['status_projeto'] ?? 0;

    $novoProjeto = new Projeto($nome, $descricao, $status);

    if ($novoProjeto->salvar()) {
        header('Location: projetos.php?status=success');
        exit();
    } else {
        $erro = "Falha ao cadastrar o projeto.";
    }
}

if (isset($_GET['excluir_id'])) {

    $id = $_GET['excluir_id'];

    $projetoDeletado = new Projeto('', '', '');
    $projetoDeletado->setId($id);

    if ($projetoDeletado->deletar()) {
        header('Location: projetos.php?status=deleted');
        exit();
    } else {
        $erro = "Falha ao excluir o projeto.";
    }
}

if (!$exibindo_pesquisa) {
    $projetos = Projeto::buscarTodos();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos</title>
    <link rel="stylesheet" href="css/tabelas.css">
</head>
<body>

<div class="geral">
    
<div class="topo">
    <button class="btn_inicio"><a href="dashboard.php">Voltar para o início</a></button>

    <form class="form_pesquisar" action="projetos.php" method="GET">
        <input type="search" class="pesquisar" name="nome_pesquisa" 
                placeholder="Pesquise um projeto..." >

        <button class="btn_pesquisar" type="submit">Pesquisar</button>
    </form>
</div>
    
    <div class="divisao">

        <div class="cadastro">
            <h1>Cadastre um Projeto</h1>

            <form class="formulario" action="" method="POST">
                <input type="hidden" name="cadastrar_projeto" value="1">

                Nome: <input type="text" name="nome_projeto">
                Descrição: <input type="text" name="descricao">
                
                Status: 
                <select name="status_projeto">
                    <?php foreach ($opcoes_status as $status): ?>
                        <option value="<?php echo $status; ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button class="btn_salvar" type="submit">Cadastrar</button>
            </form>

        </div>

        <div class="tabela">

            <h2>Tabela de Projetos</h2>

            <?php if (count($projetos) > 0): ?>

                <table>

                    <thead><tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                    </tr></thead>

                    <tbody>
                        <?php foreach ($projetos as $pro): ?>

                            <tr>
                                <td><?php echo $pro->getId(); ?></td>
                                <td><?php echo $pro->getNomeProjeto(); ?></td>
                                <td><?php echo $pro->getDescricao(); ?></td>
                                <td><?php echo $pro->getStatusProjeto(); ?></td>
                                <td>
                                    <div class="acoes">
                                      <button class="btn_editar">
                                        <a href="editar_projeto.php?id=<?php echo $pro->getId(); ?>"
                                            >Editar</a>
                                        </button>

                                        <button class="btn_deletar">
                                            <a href="projetos.php?excluir_id=<?php echo $pro->getId(); ?>"
                                                onclick="return confirm('Tem certeza que deseja excluir este projeto?');"
                                                >Excluir</a>
                                        </button>  
                                    </div>
                                    
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>

                </table>

            <?php else: ?>
                <p>Nenhum projeto cadastrado.</p>
            <?php endif; ?>

        </div>

    </div>


    <br><hr><br>

    <!-- Mensagem de Sucesso -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert success">Projeto cadastrado com sucesso!</div>
    <?php endif; ?>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="alert success">Projeto excluído com sucesso!</div>
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