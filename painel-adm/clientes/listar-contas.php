<?php 
require_once("../../conexao.php");
$data_hoje = date('Y-m-d');
$id = $_POST['id'];

$valor_pendente = 0;
$valor_vencido = 0;
$valor_total = 0;

$query = $pdo->query("SELECT * from contas_receber where cliente = '$id' and pago != 'Sim' order by id desc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){ 

echo <<<HTML
<small>
			<table id="example" class="table table-hover my-4" style="width:100%">
				<thead>
					<tr>
						<th>Pago</th>
						<th>Descrição</th>
						<th>Valor</th>						
						<th>Vencimento</th>	
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>

HTML;
for($i=0; $i < $total_reg; $i++){
						foreach ($res[$i] as $key => $value){	}

							$id_usu = $res[$i]['usuario'];
						$id_conta = $res[$i]['id'];
						$valor_conta = $res[$i]['valor'];
						$data = $res[$i]['data'];
						$query_p = $pdo->query("SELECT * from usuarios where id = '$id_usu'");
						$res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);
						$nome_usu = $res_p[0]['nome'];


						if($res[$i]['pago'] == 'Sim'){
							$classe = 'text-success';
						}else{
							$classe = 'text-danger';
						}

						$extensao = strchr($res[$i]['arquivo'], '.');
						if($extensao == '.pdf'){
							$arquivo_pasta = 'pdf.png';
						}else{
							$arquivo_pasta = $res[$i]['arquivo'];
						}

						$cliente = $res[$i]['cliente'];
						$query_p = $pdo->query("SELECT * from clientes where id = '$cliente'");
						$res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);
						if(@count($res_p) > 0){
							$nome_cli = '('.@$res_p[0]['nome'].')';
						}else{
							$nome_cli = '';
						}

						$data_venc =  $res[$i]['vencimento'];
						$pago =  $res[$i]['pago'];

						if(strtotime($data_venc) < strtotime($data_hoje)){
							$vencido = 'text-danger';
							$valor_vencido += $valor_conta;
						}else{
							$vencido = '';
							$valor_pendente += $valor_conta;
						}

						$valorF = number_format($res[$i]['valor'], 2, ',', '.');
						$vencF = implode('/', array_reverse(explode('-', $res[$i]['vencimento'])));
						$dataF = implode('/', array_reverse(explode('-', $data)));
						$valor_contaF = number_format($valor_conta, 2, ',', '.');

						$valor_total = $valor_vencido + $valor_pendente;

						$valor_vencidoF = number_format($valor_vencido, 2, ',', '.');
						$valor_pendenteF = number_format($valor_pendente, 2, ',', '.');
						$valor_totalF = number_format($valor_total, 2, ',', '.');
						

echo <<<HTML
<tr class="{$vencido}">
							<td>								<i class="bi bi-square-fill {$classe}"></i>
							</td>

							<td>{$res[$i]['descricao']} <small><span style="color:blue">{$dataF}</span></small></td>

							<td>R$ {$valorF}</td>

							

							<td>{$vencF}</td>
							
						
							<td>									

								<a href="#" onclick="baixar('{$id_conta}', '{$valor_contaF}', '{$id}')" title="Baixar Conta" style="text-decoration: none">
									<i class="bi bi-check-square-fill text-success mx-1"></i>

								</a>

								
							</td>
						</tr>
HTML;
}
echo <<<HTML
</tbody>
			</table>

<div align="right">
	<span style="margin-right: 20px">Vencidas: R$ <span class="text-danger">{$valor_vencidoF}</span></span>
	<span style="margin-right: 20px">Pendentes: R$ <span class="">{$valor_pendenteF}</span></span>
	<span style="margin-right: 20px">Total: R$ <span class="text-primary">{$valor_totalF}</span></span>
</div>
<div>
<a href="../rel/detalhamento.php?id={$id}" target="_blank">Imprimir Detalhamento</a>
</div>

<div align="right">
<a class="btn btn-primary" href="#" onclick="baixarTudo('{$id}', '{$nome_cli}')">Baixar Todas</a>
</div>
		</small>
HTML;
}else{
		echo '<p>Não existem contas à pagar para este cliente!!';
	}

?>


<script type="text/javascript">
	function baixarTudo(id, nome){
		$('#nome-baixar-contas').text(nome);
		$('#id-baixar-contas').val(id);
		$('#valor_contas').text("<?=$valor_totalF?>");
		$('#total_pago').val("<?=$valor_total?>");

		var myModal = new bootstrap.Modal(document.getElementById('modalBaixarContas'), {
			
		})

		myModal.show();
	}
</script>