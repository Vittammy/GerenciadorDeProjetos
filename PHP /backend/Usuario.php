<?php

    require_once 'conexao.php';

    class Usuario {

        private $id;
        private $email;
        private $senha;

        // >>>>> Construtor
        public function __construct( string $email, string $senha) {
            $this -> email = $email;
            $this -> senha = $senha;
        }

        // >>>>> Getters
        public function getId() {
            return $this -> id;
        }
        public function getEmail() {
            return $this -> email;
        }
        public function getSenha() {
            return $this -> senha;
        }

        // >>>>> Setters
        public function setId(int $id) {
            $this -> id = $id;
        }
        public function setEmail(string $email) {
            $this -> email = $email;
        }
        public function setSenha(string $senha) {
            $this -> senha = $senha;
        }


        // >>>>> CRUD
        public function salvar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "INSERT INTO usuarios( email, senha ) VALUES ( ?, ? )";
                $salvar = $pdo -> prepare($sql);

                $salvar -> bindParam(1, $this -> email);
                $salvar -> bindParam(2, $this -> senha);

                return $salvar -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao salvar usuário: " . $erro->getMessage();
                return false;
            }
        }

        public function atualizar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "UPDATE usuarios SET email = ?, senha = ? WHERE id = ?";
                $update = $pdo -> prepare($sql);

                $update -> bindParam(1, $this -> email);
                $update -> bindParam(2, $this -> senha);
                $update -> bindParam(3, $this -> id);

                return $update -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao atualizar usuário: " . $erro->getMessage();
                return false;
            }
        }

        public function deletar() {
            try {
                $pdo = conexao::getConexao();

                $sql = "DELETE FROM usuarios WHERE id = ?";
                $delete = $pdo -> prepare($sql);

                $delete -> bindParam(1, $this -> id);

                return $delete -> execute();
            }

            catch (PDOException $erro) {
                echo "Erro ao deletar usuário: " . $erro->getMessage();
                return false;
            }
        }

        public static function buscarTodos() {
            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT * FROM usuarios";
                $buscar = $pdo -> prepare($sql);
                $buscar -> execute();

                $usuarios = [];

                while ($dados = $buscar -> fetch(PDO::FETCH_ASSOC)) {

                    $usuario = new Usuario($dados['email'], $dados['senha']);
                    $usuario -> setId($dados['id']);

                    $usuarios[] = $usuario;
                }
                return $usuarios;
            }

            catch (PDOException $erro) {
                echo "Erro ao buscar usuários: " . $erro->getMessage();
                return [];
            }
        }

        public static function buscarPorId(int $id) {
            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT FROM usuarios WHERE id = ?";
                $buscar = $pdo -> prepare($sql);

                $buscar -> bindParam(1,$id);
                $buscar -> execute();

                $usuarios = [];

                while ($dados = $buscar -> fetch(PDO::FETCH_ASSOC)) {

                    $usuario = new Usuario($dados['email'], $dados['senha']);
                    $usuario -> setId($dados['id']);

                    $usuarios[] = $usuario;
                }
                return $usuarios;
            }

            catch (PDOException $erro) {
                echo "Erro ao buscar usuário: " . $erro->getMessage();
                return [];
            }
        }

        public static function autenticar(string $email, string $senha) {
            try {
                $pdo = conexao::getConexao();

                $sql = "SELECT id FROM usuarios WHERE email = ? AND senha = ?";
                $autenticar = $pdo -> prepare($sql);

                $autenticar -> bindParam(1, $email);
                $autenticar -> bindParam(2, $senha);

                $autenticar -> execute();

                if ($autenticar -> rowCount() > 0) {
                    $dados = $autenticar -> fetch(PDO::FETCH_ASSOC);
                    return $dados['id'];
                } else {
                    return null;
                }
            }

            catch (PDOException $erro) {
                echo "Erro de autenticação: " . $erro->getMessage();
                return [];
            }
        }
    }

?>