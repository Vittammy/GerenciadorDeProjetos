<?php

    require_once 'conexao.php';

    class Funcionario {

        private $id;
        private $nome;
        private $cargo;
        private $salario;

        // >>>>> Construtor
        public function __construct( string $nome, string $cargo, float $salario) {
            $this -> nome = $nome;
            $this -> cargo = $cargo;
            $this -> salario = $salario;
        }

        // >>>>> Getters
        public function getId() {
            return $this -> id;
        }
        public function getNome() {
            return $this -> nome;
        }
        public function getCargo() {
            return $this -> cargo;
        }
        public function getSalario() {
            return $this -> salario;
        }

        // >>>>> Setters
        public function setId(int $id) {
            $this -> id = $id;
        }
        public function setNome(string $nome) {
            $this -> nome = $nome;
        }
        public function setCargo(string $cargo) {
            $this -> cargo = $cargo;
        }
        public function setSalario(float $salario) {
            $this -> salario = $salario;
        }


        // >>>>> CRUD
        public function salvar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "INSERT INTO funcionarios( nome, cargo, salario ) VALUES ( ?, ?, ? )";
                $salvar = $pdo -> prepare($sql);

                $salvar -> bindParam(1, $this -> nome);
                $salvar -> bindParam(2, $this -> cargo);
                $salvar -> bindParam(3, $this -> salario);

                return $salvar -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao salvar funcionário: " . $erro->getMessage();
                return false;
            }
        }

        public function atualizar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "UPDATE funcionarios SET nome = ?, cargo = ?, salario = ? WHERE id = ?";
                $update = $pdo -> prepare($sql);

                $update -> bindParam(1, $this -> nome);
                $update -> bindParam(2, $this -> cargo);
                $update -> bindParam(3, $this -> salario);
                $update -> bindParam(4, $this -> id);

                return $update -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao atualizar funcionário: " . $erro->getMessage();
                return false;
            }
        }

        public function deletar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "DELETE FROM funcionarios WHERE id = ?";
                $delete = $pdo -> prepare($sql);

                $delete -> bindParam(1, $this -> id);

                return $delete -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao deletar funcionário: " . $erro->getMessage();
                return false;
            }
        }

        public static function buscarTodos() {
            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT * FROM funcionarios";
                $buscar = $pdo -> prepare($sql);
                $buscar -> execute();

                $funcionarios = [];

                while ($dados = $buscar -> fetch(PDO::FETCH_ASSOC)) {

                    $funcionario = new Funcionario($dados['nome'], $dados['cargo'], $dados['salario']);
                    $funcionario -> setId($dados['id']);

                    $funcionarios[] = $funcionario;
                }
                return $funcionarios;
            }

            catch (PDOException $erro) {
                echo "Erro ao buscar funcionários: " . $erro->getMessage();
                return [];
            }
        }

        public static function buscarPorId(int $id) {
            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT * FROM funcionarios WHERE id = ?";
                $buscar = $pdo -> prepare($sql);

                $buscar -> bindParam(1,$id);
                $buscar -> execute();

                if ($dados = $buscar -> fetch(PDO::FETCH_ASSOC)) {

                    $funcionario = new Funcionario($dados['nome'], $dados['cargo'], $dados['salario']);
                    $funcionario -> setId($dados['id']);

                    return $funcionario;
                    
                } else {
                    return null;
                }
            }

            catch (PDOException $erro) {
                echo "Erro ao buscar funcionário: " . $erro->getMessage();
                return [];
            }
        }

        public static function pesquisar($nome_pesquisa) {

            $funcionarios = [];

            if (empty($nome_pesquisa)) {
                return $funcionarios;
            }

            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT * FROM funcionarios WHERE LOWER(nome) LIKE LOWER(?)";
                $busca = $pdo -> prepare($sql);

                $busca_like = '%' . $nome_pesquisa . '%';
                $busca->bindParam(1, $busca_like);
                
                $busca -> execute();
                $result = $busca -> fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $row) {

                    $f = new Funcionario($row['nome'], $row['cargo'], $row['salario']);
                    $f -> setId($row['id']);
                    $funcionarios[] = $f;
                }

                } catch (PDOException $erro) {
                    echo "Erro na consulta: " . $erro->getMessage();
                }

            return $funcionarios;
        }
    }

?>