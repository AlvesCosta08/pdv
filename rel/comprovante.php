<?php 
include('../conexao.php');

require_once("data_formatada.php"); 

$id = $_GET['id'];

//BUSCAR AS INFORMAÇÕES DO PEDIDO
$res = $pdo->query("SELECT * from vendas where id = '$id' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$hora = $dados[0]['hora'];
$total_venda = $dados[0]['valor'];
$valor_recebido = $dados[0]['valor_recebido'];
$tipo_pgto = $dados[0]['forma_pgto'];
$status = $dados[0]['status'];
$troco = $dados[0]['troco'];
$data = $dados[0]['data'];
$desconto = $dados[0]['desconto'];
$operador = $dados[0]['operador'];
$cliente = $dados[0]['cliente'];

$nome_cliente = 'Não Informado';
$cpf_cliente = '';

$res = $pdo->query("SELECT * from clientes where id = '$cliente' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
if(@count($dados) > 0){
	$nome_cliente = $dados[0]['nome'];
	$cpf_cliente = $dados[0]['cpf'];
}



$data2 = implode('/', array_reverse(explode('-', $data)));

$res = $pdo->query("SELECT * from forma_pgtos where codigo = '$tipo_pgto' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$nome_pgto = $dados[0]['nome'];

$res = $pdo->query("SELECT * from usuarios where id = '$operador' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$nome_operador = $dados[0]['nome'];

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



<div class="printer-ticket">		
	<div  class="th title"><?php echo $nome_sistema ?></div>

	<div  class="th">
		<?php echo $endereco_sistema ?> <br />
		<small>Contato: <?php echo $telefone_sistema ?> 
		<?php if($cnpj_sistema != ""){echo ' / CNPJ '. @$cnpj_sistema; } ?>
	</small>  
</div>



<div  class="th">Cliente <?php echo $nome_cliente ?> <?php if($cpf_cliente != ""){ ?>CPF: <?php echo $cpf_cliente ?> <?php } ?>			
<br>
Venda: <b><?php echo $id ?></b> - Data: <?php echo $data2 ?> Hora: <?php echo $hora ?>
</div>

<div  class="th title" >Comprovante de Venda</div>

<div  class="th">CUMPOM NÃO FISCAL</div>

<?php 
$res = $pdo->query("SELECT * from itens_venda where venda = '$id' order by id asc");
		$dados = $res->fetchAll(PDO::FETCH_ASSOC);
		$linhas = count($dados);

		$sub_tot;
		for ($i=0; $i < count($dados); $i++) { 
			foreach ($dados[$i] as $key => $value) {
			}

			$id_produto = $dados[$i]['produto']; 
			$quantidade = $dados[$i]['quantidade'];
			$id_item= $dados[$i]['id'];
			$valor = $dados[$i]['valor_unitario'];


			$res_p = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
			$dados_p = $res_p->fetchAll(PDO::FETCH_ASSOC);
			$nome_produto = $dados_p[0]['nome'];  
			//$valor = $dados_p[0]['valor_venda'];
			

			$total_item = $valor * $quantidade;
	

			?>

	<div class="row itens">

		<div align="left" class="col-9"> <?php echo $quantidade ?> - <?php echo $nome_produto ?>

	</div>		

	<div align="right" class="col-3">
	R$ <?php

				@$total_item;
				@$sub_tot = @$sub_tot + @$total_item;
				$sub_total = $sub_tot;
				

				$sub_total = number_format( $sub_total , 2, ',', '.');
				$total_item = number_format( $total_item , 2, ',', '.');
				$total = number_format( $total_venda , 2, ',', '.');
				

				echo $total_item ;
		?>
	</div>

</div>

<?php } ?>

<div class="th" style="margin-bottom: 7px"></div>


	<div class="row valores">			
		<div class="col-6">SubTotal</div>
		<div class="col-6" align="right">R$ <?php echo @$sub_total ?></div>
	</div>		

	<div class="row valores">			
		<div class="col-6">Desconto</div>
		<div class="col-6" align="right"> <?php echo @$desconto ?></div>
	</div>	

	<div class="row valores">			
		<div class="col-6">Total</div>
		<div class="col-6" align="right"><b>R$ <?php echo @$total ?></b></div>
	</div>	

	<div class="row valores">			
		<div class="col-6">Total Pago</div>
		<div class="col-6" align="right">R$ <?php echo @$valor_recebido ?></div>
	</div>	

	<div class="row valores">			
		<div class="col-6">Troco</div>
		<div class="col-6" align="right">R$ <?php echo @$troco ?></div>
	</div>		


<div class="th" style="margin-bottom: 10px"></div>

	<div class="row valores">			
		<div class="col-6">Forma de Pagamento</div>
		<div class="col-6" align="right"> <?php echo @$nome_pgto ?></div>
	</div>	

		<div class="row valores">			
		<div class="col-6">Operador</div>
		<div class="col-6" align="right"> <?php echo @$nome_operador ?></div>
	</div>	


<div class="th" style="margin-bottom: 10px"></div>

