<?php

    require_once 'conexao.php';
    require_once 'Funcionario.php';
    require_once 'Projeto.php';

    class Alocacao {

        private $id;
        private $id_funcionario;
        private $id_projeto;

        // Carrega os objetos completos
        private $funcionario;
        private $projeto;


        public function __construct(int $id_funcionario, int $id_projeto) {
            $this->id_funcionario = $id_funcionario;
            $this->id_projeto = $id_projeto;
        }

        // >>>>> Getters
        public function getId() {
            return $this->id;
        }
        public function getIdFuncionario() {
            return $this->id_funcionario;
        }
        public function getIdProjeto() {
            return $this->id_projeto;
        }
        public function getFuncionario() {
            return $this->funcionario;
        }
        public function getProjeto() {
            return $this->projeto;
        }


        // >>>>> Setters
        public function setId(int $id) {
            $this->id = $id;
        }
        public function setFuncionario(Funcionario $funcionario) {
            $this->funcionario = $funcionario;
        }
        public function setProjeto(Projeto $projeto) {
            $this->projeto = $projeto;
        }


        // >>>>> CRUD
        public function salvar() {

            try {

                $pdo = conexao::getConexao();

                $sql = "INSERT INTO alocacao(id_funcionario, id_projeto) VALUES (?, ?)";
                $salvar = $pdo->prepare($sql);

                $salvar->bindParam(1, $this->id_funcionario);
                $salvar->bindParam(2, $this->id_projeto);

                return $salvar->execute();

            } catch (PDOException $erro) {
                echo "Erro ao salvar alocação: " . $erro->getMessage();
                return false;
            }

        }

        public function deletar() {

            try {

                $pdo = conexao::getConexao();

                $sql = "DELETE FROM alocacao WHERE id = ?";
                $delete = $pdo->prepare($sql);
                $delete->bindParam(1, $this->id);

                return $delete->execute();

            } catch (PDOException $erro) {
                echo "Erro ao deletar alocação: " . $erro->getMessage();
                return false;
            }

        }

        public static function buscarTodos() {
            
            try {

                $pdo = conexao::getConexao();

                // Join para buscar dados de funcionários e projetos
                $sql = "SELECT alocacao.id, alocacao.id_funcionario, alocacao.id_projeto,
                        f.nome AS nome_funcionario, f.cargo, f.salario,
                        p.nome_projeto, p.descricao, p.status_projeto
                        FROM alocacao
                        INNER JOIN funcionarios f ON alocacao.id_funcionario = f.id
                        INNER JOIN projetos p ON alocacao.id_projeto = p.id";

                $buscar = $pdo->prepare($sql);
                $buscar->execute();

                $alocacoes = [];

                while ($dados = $buscar->fetch(PDO::FETCH_ASSOC)) {

                    $alocacao = new Alocacao($dados['id_funcionario'], $dados['id_projeto']);
                    $alocacao->setId($dados['id']);

                    $funcionario = new Funcionario($dados['nome_funcionario'], $dados['cargo'], $dados['salario']);
                    $funcionario->setId($dados['id_funcionario']);

                    $projeto = new Projeto($dados['nome_projeto'], $dados['descricao'], $dados['status_projeto']);
                    $projeto->setId($dados['id_projeto']);


                    $alocacao->setFuncionario($funcionario);
                    $alocacao->setProjeto($projeto);

                    $alocacoes[] = $alocacao;
                }
                return $alocacoes;

            } catch (PDOException $erro) {
                echo "Erro ao buscar alocações: " . $erro->getMessage();
                return [];
            }
            
        }


        public static function pesquisar($nome_pesquisa) {

            $alocacoes = [];

            if (empty($nome_pesquisa)) {
                return $alocacoes;
            }

            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT alocacao.id, alocacao.id_funcionario, alocacao.id_projeto,
                        f.nome AS nome_funcionario, f.cargo, f.salario,
                        p.nome_projeto, p.descricao, p.status_projeto
                        FROM alocacao
                        INNER JOIN funcionarios f ON alocacao.id_funcionario = f.id
                        INNER JOIN projetos p ON alocacao.id_projeto = p.id
                        WHERE LOWER(f.nome) LIKE LOWER(?) OR LOWER(p.nome_projeto) LIKE LOWER(?)";

                $busca = $pdo -> prepare($sql);

                $busca_like = '%' . $nome_pesquisa . '%';
                $busca->bindParam(1, $busca_like);
                $busca->bindParam(2, $busca_like);
                
                $busca -> execute();
                $result = $busca -> fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $row) {

                    $alocacao = new Alocacao($row['id_funcionario'], $row['id_projeto']);
                    $alocacao -> setId($row['id']);

                    $funcionario = new Funcionario($row['nome_funcionario'], $row['cargo'], $row['salario']);
                    $funcionario -> setId($row['id_funcionario']);

                    $projeto = new Projeto($row['nome_projeto'], $row['descricao'], $row['status_projeto']);
                    $projeto -> setId($row['id_projeto']);

                    $alocacao -> setFuncionario($funcionario);
                    $alocacao -> setProjeto($projeto);

                    $alocacoes[] = $alocacao;
                }

                } catch (PDOException $erro) {
                    echo "Erro na consulta: " . $erro->getMessage();
                }

            return $alocacoes;
        }
    }
?>