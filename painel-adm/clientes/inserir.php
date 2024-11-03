<?php 
require_once("../../conexao.php");

$nome = $_POST['nome'];

$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];

$id = $_POST['id'];

$antigo = $_POST['antigo'];


if($antigo != $cpf){
// EVITAR DUPLICIDADE NO CPF
	$query_con = $pdo->prepare("SELECT * from clientes WHERE cpf = :cpf");
	$query_con->bindValue(":cpf", $cpf);
	$query_con->execute();
	$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_con) > 0){
		echo 'O CPF do cliente já está cadastrado!';
		exit();
	}
}

if($id == ""){
	$res = $pdo->prepare("INSERT INTO clientes SET nome = :nome, cpf = :cpf, telefone = :telefone, endereco = :endereco");
}else{
	$res = $pdo->prepare("UPDATE clientes SET nome = :nome, cpf = :cpf, telefone = :telefone, endereco = :endereco WHERE id = :id");		
	$res->bindValue(":id", $id);
	
}

$res->bindValue(":nome", $nome);
$res->bindValue(":cpf", $cpf);
$res->bindValue(":telefone", $telefone);
$res->bindValue(":endereco", $endereco);
$res->execute();
echo 'Salvo com Sucesso!';
?>