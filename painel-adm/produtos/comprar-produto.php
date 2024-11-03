<?php 
require_once("../../conexao.php");
@session_start();

$id_usuario = $_SESSION['id_usuario'];

$fornecedor = $_POST['fornecedor'];
$valor_compra = $_POST['valor_compra'];
$valor_compra = str_replace(',', '.', $valor_compra);
$quantidade = $_POST['quantidade'];
$quantidade2 = $_POST['quantidade'];
$lucro = $_POST['lucro'];
$lucro = str_replace('$', '', $lucro);
$valor_venda = $_POST['valor_venda'];
$valor_venda = str_replace(',', '.', $valor_venda);
$id = $_POST['id-comprar'];
$lote = $_POST['lote'];
$validade = $_POST['validade'];
$alerta = $_POST['alerta'];

if($quantidade == 0){
	echo 'A quantidade precisa ser superior a 0';
	exit();
}


//SCRIPT PARA SUBIR arquivo NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['arquivo']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../img/arquivos/' .$nome_img;
if (@$_FILES['arquivo']['name'] == ""){
  $arquivo = "sem-foto.jpg";
}else{
    $arquivo = $nome_img;
}

$imagem_temp = @$_FILES['arquivo']['tmp_name']; 
$ext = pathinfo($arquivo, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf' or $ext == 'rar' or $ext == 'zip' or $ext == 'doc' or $ext == 'docx' or $ext == 'txt' or $ext == 'xlsx' or $ext == 'xlsm' or $ext == 'xls' or $ext == 'xml' ){
move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Arquivo não permitida!!';
	exit();
}


$total_compra = $quantidade * $valor_compra;

//ATUALIZAR ESTOQUE
$query_q = $pdo->query("SELECT * FROM produtos WHERE id = '$id'");
$res_q = $query_q->fetchAll(PDO::FETCH_ASSOC);
$estoque = $res_q[0]['estoque'];
$quantidade += $estoque;

$res = $pdo->prepare("UPDATE produtos SET estoque = :quantidade, fornecedor = :fornecedor, valor_compra = :valor_compra, valor_venda = :valor_venda, lucro = :lucro, lote = :lote, validade = :validade WHERE id = :id");
$res->bindValue(":quantidade", $quantidade);
$res->bindValue(":fornecedor", $fornecedor);
$res->bindValue(":valor_compra", $valor_compra);
$res->bindValue(":valor_venda", $valor_venda);
$res->bindValue(":lucro", $lucro);
$res->bindValue(":lote", $lote);
$res->bindValue(":validade", $validade);
$res->bindValue(":id", $id);
$res->execute();



$res = $pdo->prepare("INSERT compras SET total = :total, data = curDate(), usuario = :usuario, fornecedor = :fornecedor, pago = 'Não', quantidade = '$quantidade2', produto = '$id', lote = :lote, validade = :validade, alerta = :alerta, arquivo = '$arquivo'");
$res->bindValue(":usuario", $id_usuario);
$res->bindValue(":fornecedor", $fornecedor);
$res->bindValue(":total", $total_compra);
$res->bindValue(":lote", $lote);
$res->bindValue(":validade", $validade);
$res->bindValue(":alerta", $alerta);
$res->execute();
$id_compra = $pdo->lastInsertId();


$res = $pdo->prepare("INSERT contas_pagar SET vencimento = curDate(), descricao = 'Compra de Produtos', valor = :valor, data = curDate(), usuario = :usuario, pago = 'Não', arquivo = 'sem-foto.jpg', id_compra = '$id_compra'");
$res->bindValue(":usuario", $id_usuario);
$res->bindValue(":valor", $total_compra);
$res->execute();

if($alerta > 0){
	$data_alerta = date('Y/m/d', strtotime("-$alerta days",strtotime($validade)));
	$pdo->query("INSERT alerta_vencimentos SET compra = '$id_compra', produto = '$id', data_compra = curDate(), data_vencimento = '$validade', data_alerta = '$data_alerta', status = 'Pendente'");
}

echo 'Salvo com Sucesso!';
?>