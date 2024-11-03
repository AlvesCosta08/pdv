<?php 
$pag = 'compras';

require_once('../conexao.php');
require_once('verificar-permissao.php');

?>

<div class="mt-4" style="margin-right:25px">
	<?php 
	$query = $pdo->query("SELECT * from compras order by id desc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){ 
		?>
		<small>
			<table id="example" class="table table-hover my-4" style="width:100%">
				<thead>
					<tr>
						<th>Pago</th>
						<th>Produto</th>
						<th>Total</th>
						<th>Data</th>
						<th>Gerente</th>
						<th>Fornecedor</th>
						<th>Tel Fornecedor</th>
						<th>Lote</th>
						<th>Validade</th>
						<th>Arquivo</th>
						
					</tr>
				</thead>
				<tbody>

					<?php 
					for($i=0; $i < $total_reg; $i++){
						foreach ($res[$i] as $key => $value){	}

							$arquivo = $res[$i]['arquivo'];

							//BUSCAR OS DADOS DO USUARIO
							$id_usu = $res[$i]['usuario'];
						$query_f = $pdo->query("SELECT * from usuarios where id = '$id_usu'");
						$res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
						$total_reg_f = @count($res_f);
						if($total_reg_f > 0){ 
							$nome_usuario = $res_f[0]['nome'];
							
						}


						//BUSCAR OS DADOS DO FORNECEDOR
						$id_forn = $res[$i]['fornecedor'];
						$query_f = $pdo->query("SELECT * from fornecedores where id = '$id_forn'");
						$res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
						$total_reg_f = @count($res_f);
						if($total_reg_f > 0){ 
							$nome_forn = $res_f[0]['nome'];
							$tel_forn = $res_f[0]['telefone'];
						}


						//BUSCAR NOME PRODUTO
						$id_prod = $res[$i]['produto'];
						$nome_prod = '';
						$query_f = $pdo->query("SELECT * from produtos where id = '$id_prod'");
						$res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
						$total_reg_f = @count($res_f);
						if($total_reg_f > 0){ 
							$nome_prod = $res_f[0]['nome'];
							
						}


						if($res[$i]['pago'] == 'Sim'){
							$classe = 'text-success';
						}else{
							$classe = 'text-danger';
						}


							//extensão do arquivo
$ext = @pathinfo($arquivo, PATHINFO_EXTENSION);
if($ext == 'pdf'){
	$tumb_arquivo = 'pdf.png';
}else if($ext == 'rar' || $ext == 'zip'){
	$tumb_arquivo = 'rar.png';
}else if($ext == 'doc' || $ext == 'docx' || $ext == 'txt'){
	$tumb_arquivo = 'word.png';
}else if($ext == 'xlsx' || $ext == 'xlsm' || $ext == 'xls'){
	$tumb_arquivo = 'excel.png';
}else if($ext == 'xml'){
	$tumb_arquivo = 'xml.png';
}else{
	$tumb_arquivo = $arquivo;
}

if($arquivo == ""){
	$tumb_arquivo = 'sem-foto.jpg';
}

						?>

						<tr>
							<td>								<i class="bi bi-square-fill <?php echo $classe ?>"></i>
								</td>
									<td><?php echo $nome_prod ?></td>
							<td>R$ <?php echo number_format($res[$i]['total'], 2, ',', '.'); ?></td>

							<td><?php echo implode('/', array_reverse(explode('-', $res[$i]['data']))); ?></td>

							<td><?php echo $nome_usuario ?></td>

							<td><?php echo $nome_forn ?></td>
							
							<td><?php echo $tel_forn ?></td>
							<td><?php echo $res[$i]['lote'] ?></td>
							<td><?php echo implode('/', array_reverse(explode('-', $res[$i]['validade']))); ?></td>


							<td><a target="_blank" href="../img/arquivos/<?php echo $arquivo ?>"><img src="../img/arquivos/<?php echo $tumb_arquivo ?>" width="40px"></a></td>
							
							
						</tr>

					<?php } ?>

				</tbody>

			</table>
		</small>
	<?php }else{
		echo '<p>Não existem dados para serem exibidos!!';
	} ?>
</div>







<script type="text/javascript">
	$(document).ready(function() {
		$('#example').DataTable({
			"ordering": false
		});
	} );
</script>




