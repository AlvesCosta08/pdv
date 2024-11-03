<?php 
$tabela = 'contas_receber';
require_once("../../conexao.php");

@session_start();
$id_usuario = $_SESSION['id_usuario'];

$id = $_POST['id'];
$dias_frequencia = $_POST['frequencia'];
$qtd_parcelas = $_POST['parcelas'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$descricao = $res[0]['descricao'];
$usuario_lanc = $res[0]['usuario'];
$valor = $res[0]['valor'];
$pago = $res[0]['pago'];
$data_lanc = $res[0]['data'];
$data_venc = $res[0]['vencimento'];
$arquivo = $res[0]['arquivo'];
$data_pgto = $res[0]['data_pgto'];
$cliente = $res[0]['cliente'];
$id_venda = $res[0]['id_venda'];


for($i=1; $i <= $qtd_parcelas; $i++){

	$nova_descricao = $descricao . ' - Parcela '.$i;
	$novo_valor = $valor / $qtd_parcelas;
	$dias_parcela = $i - 1;
	$dias_parcela_2 = ($i - 1) * $dias_frequencia;

	if($i == 1){
		$novo_vencimento = $data_venc;
	}else{

		if($dias_frequencia == 30 || $dias_frequencia == 31){
			
			$novo_vencimento = date('Y/m/d', strtotime("+$dias_parcela month",strtotime($data_venc)));


		}else{
			
			$novo_vencimento = date('Y/m/d', strtotime("+$dias_parcela_2 days",strtotime($data_venc)));
		}
		
	}

		
		
		$novo_valor = number_format($novo_valor, 2, ',', '.');
		$novo_valor = str_replace('.', '', $novo_valor);
		$novo_valor = str_replace(',', '.', $novo_valor);
		$resto_conta = $valor - $novo_valor * $qtd_parcelas;
		$resto_conta = number_format($resto_conta, 2);
		
		if($i == $qtd_parcelas){
			$novo_valor = $novo_valor + $resto_conta;
		}


	$pdo->query("INSERT INTO $tabela set descricao = '$nova_descricao', cliente = '$cliente', valor = '$novo_valor', usuario = '$id_usuario', data = curDate(), vencimento = '$novo_vencimento', arquivo = '$arquivo', pago = 'Não', id_venda = '$id_venda'");

}

$pdo->query("DELETE from $tabela where id = '$id'");

echo 'Parcelado com Sucesso';


?>