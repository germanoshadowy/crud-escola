<?php
include "./classes/Professor.php";
$professores = Professor::listar();

foreach ($professores as $professor) {
    echo "Código : {$professor->getCodigo()}<br>";
    echo "Nome :{$professor->getNome()}<br>";
    echo "-------------------------------------------<br>";
}

$professor = new Professor();
$professor->setProfessor(null, "Malaquias Fulgencio");
var_dump($professor);
$professor->salvar();