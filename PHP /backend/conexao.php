<?php

class Conexao {

    private static $host = "localhost";
    private static $db = "empresa_tech";
    private static $user = 'root';
    private static $pwd = '';
    private static $conect;

    public static function getConexao() {

        try {

            // Verifica se já existe conexão
            if (!isset(self::$conect)) {
                self::$conect = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db, self::$user, self::$pwd);
                // Driver | Onde o banco está hospedado | Porta (3306 padrão) | Nome do banco | Usuário | Senha
                self::$conect -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            }
            return self::$conect;
    
        } catch (PDOException $erro) {
            echo "Falha na conexão. Erro: " . $erro -> getMessage();
        }

    }
}

?>