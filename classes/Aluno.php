<?php
require_once "./classes/Database.php";
/**
 * Classe Aluno
 * @name Aluno
 * @author Germano da Silva Pinheiro
 * @access public 
 * @version 2.5.4
 */
class Aluno {
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
     * @name matricula
     * @access private
     */
    private $matricula;
    /**
     * @name turma
     * @access private
     */
    private $turma;

    /**
     * Método setAluno()
     * @name setAluno
     * @access public
     * @param Int $codigo
     * @param String $nome
     * @param String $matricula
     * @param Object $turma
     * @return Void
     */
    public function setAluno($codigo, $nome, $matricula, $turma) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->turma = $turma;
    }

    /**
     * Metodo getCodigo()
     * @name getCodigo
     * @access public
     * @return $this->codigo
     */
    public function getCodigo() {
        return $this->codigo;
    }
    /**
     * Metodo getNome()
     * @name getNome
     * @access public
     * @return $this->nome
     */
    public function getNome() {
        return $this->nome;
    }
    /**
     * Metodo getMatricula()
     * @name getMatricula
     * @access public
     * @return $this->matricula
     */
    public function getMatricula() {
        return $this->matricula;
    }
    /**
     * Metodo getTurma()
     * @name getTurma
     * @access public
     * @return $this->turma
     */
    public function getTurma() {
        return $this->turma;
    }

    /**
     * Metodo Listar()
     * @name listar
     * @access public
     * @return $alunos
     */
    public static function listar() {
        $db = Database::conexao();
        $alunos = null;
        $retorno = $db->query("SELECT * FROM aluno");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);

            $alunos[] = $aluno;
        }

        return $alunos;
    }

    /**
     * Metodo Excluir()
     * @param Int $codigo
     * @return Boolean
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        if ($db->query("DELETE FROM aluno WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }

    /**
     * Metodo Salvar
     * @name salvar
     * @return Boolean
     */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO aluno (nome, matricula, turma_codigo) VALUES (:nome,:matricula,:turma)");
                $stm->execute(array(":nome" => $this->getNome(), ":matricula" => $this->getMatricula(), ":turma" => $this->getTurma()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE aluno SET nome=:nome,matricula=:matricula,turma_codigo=:turma_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":matricula" => $this->matricula, ":turma_codigo" => $this->turma->getCodigo(), ":codigo" => $this->codigo));
            }
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }

    /**
     * Metodo getAluno
     * @name getAluno
     * @param String $codigo
     * @return Boolean
     */
    public static function getAluno($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM aluno WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);
            return $aluno;
        }
        return false;
    }
}