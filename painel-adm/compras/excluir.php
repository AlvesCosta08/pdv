<?php 
require_once("../../conexao.php");

$id = $_POST['id'];

$query_con = $pdo->query("SELECT * FROM compras WHERE id = '$id'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res_con);
if($total_reg > 0){ 
$pago = $res_con[0]['pago'];
$quantidade = $res_con[0]['quantidade'];
$id_produto = $res_con[0]['produto'];

if($pago == 'Sim'){
	echo 'Essa compra já está concluída, você não pode excluí-la!';
	exit();
}
}

//abater produtos estoque
$query_q = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
$res_q = $query_q->fetchAll(PDO::FETCH_ASSOC);
$estoque = @$res_q[0]['estoque'];
$novo_estoque = $estoque - $quantidade;

$query_con = $pdo->query("UPDATE produtos SET estoque = '$novo_estoque' WHERE id = '$id_produto'");

$query_con = $pdo->query("DELETE from contas_pagar WHERE id_compra = '$id'");

$query_con = $pdo->query("DELETE from compras WHERE id = '$id'");




echo 'Excluído com Sucesso!';



 ?>