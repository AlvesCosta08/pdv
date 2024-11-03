<?php 
require_once("../conexao.php"); 

require_once("data_formatada.php"); 

$data_atual = date('Y-m-d');

?>
<!DOCTYPE html>
<html>
<head>

<style>

@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left: 0px; top: -110px; bottom: 100px; right: 0px; height: 35px; text-align: center; padding-bottom: 100px; }
#content {margin-top: 0px;}
#footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 80px; }
#footer .page:after {content: counter(page, my-sec-counter);}
body {font-family: 'Tw Cen MT', sans-serif;}

.marca{
	position:fixed;
	left:50;
	top:100;
	width:80%;
	opacity:8%;
}

</style>

</head>
<body>
<?php 
if($marca_dagua == 'Sim'){ ?>
<img class="marca" src="<?php echo $url_sistema ?>img/logo_rel.jpg">	
<?php } ?>


<div id="header" >

	<div style="border-style: solid; font-size: 10px; height: 50px;">
		<table style="width: 100%; border: 0px solid #ccc;">
			<tr>
				<td style="border: 1px; solid #000; width: 7%; text-align: left;">
					<img style="margin-top: 0px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo_rel.jpg" width="40px">
				</td>
				<td style="width: 33%; text-align: left; font-size: 13px;">
				
				</td>
				<td style="width: 5%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 40%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>PRODUTOS COM VENCIMENTO PRÓXIMO</big></b><br><br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 9px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:30%">PRODUTO</td>
					<td style="width:13%">DATA COMPRA</td>
					<td style="width:13%">QTD COMPRADA</td>
					<td style="width:15%">LOTE</td>
					<td style="width:14%">VENCIMENTO</td>
					<td style="width:15%">STATUS</td>
					
					
				</tr>
			</thead>
		</table>
</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>

<div id="content" style="margin-top: 0;">



		<table style="width: 100%; table-layout: fixed; font-size:9px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php 
$produtos_ativos = 0;
$produtos_inativos = 0;
$estoque_baixo = 0;
$query = $pdo->query("SELECT * from alerta_vencimentos where data_alerta <= curDate() order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	$id_alerta = $res[$i]['id'];
						$obs = $res[$i]['obs'];
						$status = $res[$i]['status'];
						$data_vencimento = $res[$i]['data_vencimento'];									
						//BUSCAR NOME PRODUTO
						$id_prod = $res[$i]['produto'];						
						$query_f = $pdo->query("SELECT * from produtos where id = '$id_prod'");
						$res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
						$total_reg_f = @count($res_f);
						if($total_reg_f > 0){ 
							$nome_prod = $res_f[0]['nome'];							
						}


							//BUSCAR DADOS COMPRA
						$id_compra = $res[$i]['compra'];						
						$query_f = $pdo->query("SELECT * from compras where id = '$id_compra'");
						$res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
						$total_reg_f = @count($res_f);
						if($total_reg_f > 0){ 
							$data = $res_f[0]['data'];		
							$quantidade = $res_f[0]['quantidade'];		
							$lote = $res_f[0]['lote'];	

						}

						if($res[$i]['status'] == 'Conferido'){
							$classe = 'azul.jpg';
						}else{
							$classe = 'vermelho.jpg';
						}


						if(strtotime($data_vencimento) == strtotime($data_atual)){
							$classe_data = 'red';
						}else{
							$classe_data = '';
						}
	
  	 ?>

  	 
      <tr style="">
<td style="width:30%">
	<img src="<?php echo $url_sistema ?>img/<?php echo $classe ?>" width="10px">
	<?php echo $nome_prod ?>
		
	</td>
<td style="width:13%"><?php echo implode('/', array_reverse(explode('-', $data))); ?></td>
<td style="width:13%"><?php echo $quantidade ?></td>
<td style="width:15%"><?php echo $lote ?></td>
<td style="width:14%; color:<?php echo $classe_data ?>"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_vencimento']))); ?></td>
<td style="width:15%"><?php echo $res[$i]['status']; ?></td>

    </tr>

<?php } } ?>
				</tbody>
	
			</thead>
		</table>
	


</div>
<hr>
		<table>
			<thead>
				<tbody>
					<tr>
						<td style="font-size: 10px; width:180px; text-align: right;"> </td>

						<td style="font-size: 10px; width:180px; text-align: right;"></td>

						<td style="font-size: 10px; width:180px; text-align: right;"></td>

							<td style="font-size: 10px; width:180px; text-align: right;"><b>Alertas de Lotes de Compras: <?php echo $linhas ?></td>
						
					</tr>
				</tbody>
			</thead>
		</table>

</body>

</html>


