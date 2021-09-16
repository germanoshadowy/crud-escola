<?php
require_once "./classes/Database.php";
require_once "./classes/Professor.php";
/**
 * Classe Turma
 * @name Turma
 * @author Germano da Silva Pinheiro
 * @access public 
 * @version 2.5.4
 */
class Turma {
    /**
     * @name $codigo
     * @access private
     */
    private $codigo;
    /**
     * @name $curso
     * @access private
     */
    private $curso;
    /**
     * @name $nome
     * @access private
     */
    private $nome;
    /**
     * @name $professor
     * @access private
     */
    private $professor;

    /**
     * metodo setTurma
     * @name setTurma
     * @param String $codigo
     * @param String $curso
     * @param String $nome
     * @param String $professor
     */
    public function setTurma($codigo, $curso, $nome, $professor) {
        $this->codigo = $codigo;
        $this->curso = $curso;
        $this->nome = $nome;
        $this->professor = $professor;
    }

    /**
     * metodo getCodigo
     * @name getCodigo
     * @return $this->codigo
     */
    public function getCodigo() {
        return $this->codigo;
    }
    /**
     * metodo getNome
     * @name getNome
     * @return $this->nome
     */
    public function getNome() {
        return $this->nome;
    }
    /**
     * metodo getCurso
     * @name getCurso
     * @return $this->curso
     */
    public function getCurso() {
        return $this->curso;
    }
    /**
     * metodo getProfessor
     * @name getProfessor
     * @return $this->professor
     */
    public function getProfessor() {
        return $this->professor;
    }

    /**
     * metodo Listar
     * @name listar
     * @return String $turma
     */
    public static function listar() {
        $db = Database::conexao();
        $turmas = null;
        $retorno = $db->query("SELECT * FROM turma");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);

            $turmas[] = $turma;
        }

        return $turmas;
    }

    /**
     * metodo excluir 
     * @name excluir
     * @param Int $codigo
     * @return Boolean
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        $turmas = null;
        if ($db->query("DELETE FROM turma WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }

    /**
     * metodo salvar
     * @name salvar
     * @return Boolean
    */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO turma (nome, curso, professor_codigo) VALUES (:nome,:curso,:professor)");
                $stm->execute(array(":nome" => $this->getNome(), ":curso" => $this->getCurso(), ":professor" => $this->getProfessor()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE turma SET nome=:nome,curso=:curso,professor_codigo=:professor_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":curso" => $this->curso, ":professor_codigo" => $this->professor->getCodigo(), ":codigo" => $this->codigo));
            }
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }

    /**
     * metodo getTurmas
     * @name getTurmas
     * @param Int $codigo
     * @return Boolean
     */
    public static function getTurma($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM turma WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);
            return $turma;
        }
        return false;
    }
}