<?php 
require_once("../../conexao.php");

$id = $_POST['id'];
$status = $_POST['status'];
$obs = $_POST['obs'];

$res = $pdo->prepare("UPDATE alerta_vencimentos SET obs = :obs, status = :status  WHERE id = '$id'");		
	
$res->bindValue(":obs", $obs);
$res->bindValue(":status", $status);
$res->execute();

echo 'Excluído com Sucesso!';



 ?>