<?php
require_once "./classes/Database.php";
/**
 * Class Professor
 * @name Professor
 * @access public
 * @author Germano da Silva Pinheiro 
 * @version 2.5.4
 */
class Professor {
    /**
     * @name codigo
     * @access private
     */
    private $codigo;
    /**
     * @name nome
     * @access private
     */
    private $nome;

    /**
     * Metodo setProfessor
     * @name setProfessor
     * @param String $codigo
     * @param String $nome
     * @return $this->codigo
     * @return $this->nome
    */
    public function setProfessor($codigo, $nome) {
        $this->codigo = $codigo;
        $this->nome = $nome;
    }

    /**
     * Metodo getCodigo
     * @name getCodigo
     * @return @this->codigo
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Metodo getNome
     * @name getNome
     * @return $this->nome
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Metodo salvar
     * @name salvar
     * @return Boolean
     */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO professor (nome) VALUES (:nome)");
                $stm->execute(array(":nome" => $this->getNome()));
            } else {
                $stm = $db->prepare("UPDATE professor SET nome=:nome WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":codigo" => $this->codigo));
            }
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }
    
    /**
     * metodo listar
     * @name listar
     * @return $professor
     */
    public static function listar() {
        $db = Database::conexao();
        $professores = null;
        $retorno = $db->query("SELECT * FROM professor");
        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);

            $professores[] = $professor;
        }

        return $professores;
    }

    /**
     * metodo getProfessor
     * @name getProfessor
     * @param String $codigo
     * @return Boolean
     */
    public static function getProfessor($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM professor WHERE codigo= $codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);
            return $professor;
        }
        return false;
    }

    /**
     * metodo excluir
     * @name excluir 
     * @param String $codigo
     * @return Boolean
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        $professor = null;
        if ($db->query("DELETE FROM professor WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }
}