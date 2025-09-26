<?php

    require_once 'conexao.php';

    class Projeto {

        private $id;
        private $nome_projeto;
        private $descricao;
        private $status_projeto;

        // >>>>> Construtor
        public function __construct( string $nome_projeto, string $descricao, string $status_projeto) {
            $this -> nome_projeto = $nome_projeto;
            $this -> descricao = $descricao;
            $this -> status_projeto = $status_projeto;
        }

        // >>>>> Getters
        public function getId() {
            return $this -> id;
        }
        public function getNomeProjeto() {
            return $this -> nome_projeto;
        }
        public function getDescricao() {
            return $this -> descricao;
        }
        public function getStatusProjeto() {
            return $this -> status_projeto;
        }

        // >>>>> Setters
        public function setId(int $id) {
            $this -> id = $id;
        }
        public function setNomeProjeto(string $nome_projeto) {
            $this -> nome_projeto = $nome_projeto;
        }
        public function setDescricao(string $descricao) {
            $this -> descricao = $descricao;
        }
        public function setStatusProjeto(string $status_projeto) {
            $this -> status_projeto = $status_projeto;
        }


        // >>>>> CRUD
        public function salvar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "INSERT INTO projetos( nome_projeto, descricao, status_projeto ) VALUES ( ?, ?, ? )";
                $salvar = $pdo -> prepare($sql);

                $salvar -> bindParam(1, $this -> nome_projeto);
                $salvar -> bindParam(2, $this -> descricao);
                $salvar -> bindParam(3, $this -> status_projeto);

                return $salvar -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao salvar projeto: " . $erro->getMessage();
                return false;
            }
        }

        public function atualizar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "UPDATE projetos SET nome_projeto = ?, descricao = ?, status_projeto = ? WHERE id = ?";
                $update = $pdo -> prepare($sql);

                $update -> bindParam(1, $this -> nome_projeto);
                $update -> bindParam(2, $this -> descricao);
                $update -> bindParam(3, $this -> status_projeto);
                $update -> bindParam(4, $this -> id);

                return $update -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao atualizar projeto: " . $erro->getMessage();
                return false;
            }
        }

        public function deletar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "DELETE FROM projetos WHERE id = ?";
                $delete = $pdo -> prepare($sql);

                $delete -> bindParam(1, $this -> id);

                return $delete -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao deletar projeto: " . $erro->getMessage();
                return false;
            }
        }

        public static function buscarTodos() {
            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT * FROM projetos";
                $buscar = $pdo -> prepare($sql);
                $buscar -> execute();

                $projetos = [];

                while ($dados = $buscar -> fetch(PDO::FETCH_ASSOC)) {

                    $projeto = new Projeto($dados['nome_projeto'], $dados['descricao'], $dados['status_projeto']);
                    $projeto -> setId($dados['id']);

                    $projetos[] = $projeto;
                }
                return $projetos;
            }

            catch (PDOException $erro) {
                echo "Erro ao buscar projetos: " . $erro->getMessage();
                return [];
            }
        }

        public static function buscarPorId(int $id) {
            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT * FROM projetos WHERE id = ?";
                $buscar = $pdo -> prepare($sql);

                $buscar -> bindParam(1,$id);
                $buscar -> execute();

                if ($dados = $buscar -> fetch(PDO::FETCH_ASSOC)) {

                    $projeto = new Projeto($dados['nome_projeto'], $dados['descricao'], $dados['status_projeto']);
                    $projeto -> setId($dados['id']);

                    return $projeto;
                    
                } else {
                    return null;
                }
            }

            catch (PDOException $erro) {
                echo "Erro ao buscar projeto: " . $erro->getMessage();
                return null;
            }
        }

        public static function pesquisar($nome_pesquisa) {

            $projetos = [];

            if (empty($nome_pesquisa)) {
                return $projetos;
            }

            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT * FROM projetos WHERE LOWER(nome_projeto) LIKE LOWER(?)";
                $busca = $pdo -> prepare($sql);

                $busca_like = '%' . $nome_pesquisa . '%';
                $busca->bindParam(1, $busca_like);
                
                $busca -> execute();
                $result = $busca -> fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $row) {

                    $p = new Projeto($row['nome_projeto'], $row['descricao'], $row['status_projeto']);
                    $p -> setId($row['id']);
                    $projetos[] = $p;
                }

                } catch (PDOException $erro) {
                    echo "Erro na consulta: " . $erro->getMessage();
                }

            return $projetos;
        }


    }

?>