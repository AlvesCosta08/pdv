<?php 
include('../conexao.php');

require_once("data_formatada.php"); 
$data_hoje = date('Y-m-d');

$id = $_GET['id'];

$valor_pendente = 0;
$valor_vencido = 0;
$valor_total = 0;

//BUSCAR AS INFORMAÇÕES DO PEDIDO
$res = $pdo->query("SELECT * from clientes where id = '$id' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
if(@count($dados) > 0){
	$nome_cliente = $dados[0]['nome'];
	$cpf_cliente = $dados[0]['cpf'];
}


?>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php if(@$_GET['imp'] != 'Não'){ ?>
<script type="text/javascript">
	$(document).ready(function() {    		
		window.print();
		window.close(); 
	} );
</script>
<?php } ?>

<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

<style type="text/css">
	*{
		margin:0px;

		/*Espaçamento da margem da esquerda e da Direita*/
		padding:0px;
		background-color:#ffffff;


	}
	.text {
		&-center { text-align: center; }
	}
	
	.printer-ticket {
		display: table !important;
		width: 100%;

		/*largura do Campos que vai os textos*/
		max-width: 400px;
		font-weight: light;
		line-height: 1.3em;

		/*Espaçamento da margem da esquerda e da Direita*/
		padding: 0px;
		font-family: TimesNewRoman, Geneva, sans-serif; 

		/*tamanho da Fonte do Texto*/
		font-size: <?php echo $fonte_comprovante ?>px; 



	}
	
	.th { 
		font-weight: inherit;
		/*Espaçamento entre as uma linha para outra*/
		padding:5px;
		text-align: center;
		/*largura dos tracinhos entre as linhas*/
		border-bottom: 1px dashed #000000;
	}

	.itens { 
		font-weight: inherit;
		/*Espaçamento entre as uma linha para outra*/
		padding:5px;
		
	}

	.valores { 
		font-weight: inherit;
		/*Espaçamento entre as uma linha para outra*/
		padding:2px 5px;
		
	}


	.cor{
		color:#000000;
	}
	
	
	.title { 
		font-size: 12px;
		text-transform: uppercase;
		font-weight: bold;
	}

	/*margem Superior entre as Linhas*/
	.margem-superior{
		padding-top:5px;
	}
	
	
}
</style>



<div class="printer-ticket" style="color:#000">		
	<div  class="th title"><?php echo $nome_sistema ?></div>

	<div  class="th" style="color:#000">
		<?php echo $endereco_sistema ?> <br />
		<small>Contato: <?php echo $telefone_sistema ?> 
		<?php if($cnpj_sistema != ""){echo ' / CNPJ '. @$cnpj_sistema; } ?>
	</small>  
</div>



<div style="color:#000"  class="th">Cliente <?php echo $nome_cliente ?> <?php if($cpf_cliente != ""){ ?>CPF: <?php echo $cpf_cliente ?> <?php } ?>			
<br>
<small><?php echo mb_strtoupper($data_formatada) ?></small>
</div>

<div style="color:#000" class="th title" >Detalhamento de Débitos</div>

<?php 
$query = $pdo->query("SELECT * from contas_receber where cliente = '$id' and pago != 'Sim' order by id desc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){ 

echo <<<HTML
<small>
			<table id="example" class="table" style="width:100%; font-size:{$fonte_comprovante}; color:#000">
				<thead>
					<tr>						
						<th>Descrição</th>
						<th>Valor</th>
						<th>Data</th>	
						<th>Vencimento</th>	
						
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

							<td>{$res[$i]['descricao']}</td>
							<td>R$ {$valorF}</td>	
							<td>{$dataF}</td>	
							<td>{$vencF}</td>
							
						
						
						</tr>
HTML;
}
echo <<<HTML
</tbody>
			</table>

		</small>
HTML;
}else{
		echo '<p>Não existem dados para serem exibidos!!';
	}

?>
<div class="th" style="margin-bottom: 7px"></div>


	<div class="row valores" style="color:#000">			
		<div class="col-6">Vencidas</div>
		<div class="col-6" align="right">R$ <?php echo @$valor_vencidoF ?></div>
	</div>		

	<div class="row valores" style="color:#000">			
		<div class="col-6">Pendentes</div>
		<div class="col-6" align="right"> <?php echo @$valor_pendenteF ?></div>
	</div>	

	<div class="row valores" style="color:#000">			
		<div class="col-6">Total</div>
		<div class="col-6" align="right"><b>R$ <?php echo @$valor_totalF ?></b></div>
	</div>	
	


<div class="th" style="margin-bottom: 10px"></div>
