<?php 
require_once("../../conexao.php");

$id = $_POST['id'];
$total_pago = $_POST['total_pago'];
$total_pago = str_replace(',', '.', $total_pago);

@session_start();
$id_usuario = $_SESSION['id_usuario'];


$debito_cliente = 0;
$query = $pdo->query("SELECT * from contas_receber where cliente = '$id' and pago != 'Sim' order by id desc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){ 
		for($i=0; $i < $total_reg; $i++){
			$debito_cliente += $res[$i]['valor'];
		}
}



$query_con = $pdo->query("UPDATE contas_receber SET pago = 'Sim', usuario = '$id_usuario', data_pgto = curDate() where cliente = '$id' and pago != 'Sim'");


//LANÇAR NAS MOVIMENTAÇÕES
$res = $pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', data = curDate(), usuario = '$id_usuario', descricao = 'Baixa Débito', valor = '$total_pago', id_mov = '$id'");





$total_debito = $debito_cliente - $total_pago;

if($total_pago < $debito_cliente){

	$pdo->query("INSERT INTO contas_receber SET descricao = 'Resíduo Débitos', valor = '$total_debito', usuario = '$id_usuario',  pago = 'Não', data = curDate(), vencimento = curDate(), arquivo = 'sem-foto.jpg', data_pgto = '', cliente = '$id' ");
}

	

echo 'Baixado com Sucesso!';

?>