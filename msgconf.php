<?php include 'header.php';?>

<div class="menu">
    <button class="button3" onclick="location.href='index.php'" type="button">No prazo</button>
    <button class="button3" onclick="location.href='vencido.php'" type="button">Vencidos</button>
    <button class="button3" onclick="location.href='pago.php'" type="button">Pagos</button>
    <button class="button2" onclick="location.href='msgconf.php'" type="button">Conf. msg</button>
</div>

<?php
if (!empty($_POST)) {
    if (isset($_POST['postnoprazo'])) {
		$db = new SQLite3('db/msgdb.sqlite3', SQLITE3_OPEN_READWRITE);
		$db->busyTimeout(5000);
		$db->exec('PRAGMA journal_mode = WAL;');
		$sql = "DELETE FROM msgnoprazo; INSERT INTO msgnoprazo VALUES('".($_POST['msgnoprazo'])."');";
		$result = $db->exec($sql);
		$db->close();
		unset($db);
    }
}

if (!empty($_POST)) {
    if (isset($_POST['postvencido'])) {
		$db = new SQLite3('db/msgdb.sqlite3', SQLITE3_OPEN_READWRITE);
		$db->busyTimeout(5000);
		$db->exec('PRAGMA journal_mode = WAL;');
		$sql = "DELETE FROM msgvencido; INSERT INTO msgvencido VALUES('".($_POST['msgvencido'])."');";
		$result = $db->exec($sql);
		$db->close();
		unset($db);
    }
}

if (!empty($_POST)) {
    if (isset($_POST['postpago'])) {
		$db = new SQLite3('db/msgdb.sqlite3', SQLITE3_OPEN_READWRITE);
		$db->busyTimeout(5000);
		$db->exec('PRAGMA journal_mode = WAL;');
		$sql = "DELETE FROM msgpago; INSERT INTO msgpago VALUES('".($_POST['msgpago'])."');";
		$result = $db->exec($sql);
		$db->close();
		unset($db);
    }
}

$db = new SQLite3('db/msgdb.sqlite3');
$db->busyTimeout(5000);
$db->exec('PRAGMA journal_mode = WAL;');
$sql = "SELECT * FROM msgnoprazo";
$result = $db->query($sql);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgnoprazo 	= $row['msg'];}
$db->close();
unset($db);

$db = new SQLite3('db/msgdb.sqlite3');
$db->busyTimeout(5000);
$db->exec('PRAGMA journal_mode = WAL;');
$sql = "SELECT * FROM msgvencido";
$result = $db->query($sql);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgvencido 	= $row['msg'];}
$db->close();
unset($db);

$db = new SQLite3('db/msgdb.sqlite3');
$db->busyTimeout(5000);
$db->exec('PRAGMA journal_mode = WAL;');
$sql = "SELECT * FROM msgpago";
$result = $db->query($sql);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgpago 	= $row['msg'];}
$db->close();
unset($db);
?>

<div class="flex-container">
    <div>
        <h1>Cadastrar mensagens:</h1>
        <h4>Use:
            <br>## para quebrar balão,
            <br>%0a para quebrar linha no mesmo balão,
            <br>*texto entre astericos* para enviar texto em negrito,
            <br>%provedor% para enviar o nome do provedor,
            <br>%nomeresumido% para puxar o nome do cliente,
            <br>%vencimento% para puxar a data de vencimento,
            <br>%pagamento% para puxar a data de pagamento,
            <br>%linhadig% para puxar a linha digitável do boleto,
            <br>%copiacola% para puxar o PIX copia e cola.
            <br>%site% para enviar o site do provedor.
        </h4>
    </div>
    <div>
        <h1>Exemplo:</h1>
        <h4><br>Provedor %provedor% %0a%0a *Mensagem automática*
            <br><br>##Olá %nomeresumido%! %0a%0a Passando para lembrar <br>do seu boleto com vencimento em %vencimento%
            <br><br>##Linha digitável: ##%linhadig% ##Pix copia e cola: ##%copiacola%
            <br><br>##Ou acesse nossa central em: %0a%0a https://%site%
        </h4>
    </div>
    <div>
        <h1>Resultado:</h1>
        <h5>Provedor XYZ<br><strong>*Mensagem automática*</strong>
            <br><br>Olá Fulano Silva!<br>Passando para lembrar do seu boleto com vencimento em 25/05/2024
            <br><br>Linha digitável: <br>123456.123456 1 1234.123455678
            <br><br>Pix copia e cola: <br>pix.abc12345678-12345678abcdef
            <br><br>Ou acesse nossa central em: <br>https://www.xyz.com.br
        </h5>
    </div>
    <div>
        <h1>Editar msg no prazo:</h1>
        <form enctype='multipart/form-data' method=post 
        onsubmit="return confirm('Confirma a edição da mensagem no prazo?');">
            <textarea type="text" name="msgnoprazo" autocomplete="off" required><?php echo $msgnoprazo; ?></textarea>
            <br><br>
            <button class="button" name="postnoprazo" type="submit"Salvar modelo<br> msg no prazo</button>
            <br><br>
        </form>
    </div>
    <div>
        <h1>Editar msg vencido:</h1>
        <form enctype='multipart/form-data' method=post 
        onsubmit="return confirm('Confirma a edição da mensagem vencida?');">
            <textarea type="text" name="msgvencido" autocomplete="off" required><?php echo $msgvencido; ?></textarea>
            <br><br>
            <button class="button" name="postvencido" type="submit">Salvar modelo<br> msg vencido</button>
            <br><br>
        </form>
    </div>
    <div>
        <h1>Editar msg pago:</h1>
        <form enctype='multipart/form-data' method=post 
        onsubmit="return confirm('Confirma a edição da mensagem paga?');">
            <textarea type="text" name="msgpago" autocomplete="off" required><?php echo $msgpago; ?></textarea>
            <br><br>
            <button class="button" name="postpago" type="submit">Salvar modelo<br> msg pago</button>
            <br><br>
        </form>
    </div>
</div>