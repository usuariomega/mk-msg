<?php include 'header.php'; ?>

<div class="menu">
    <button class="button3" onclick="location.href='index.php'" type="button">No prazo</button>
    <button class="button3" onclick="location.href='vencido.php'" type="button">Vencidos</button>
    <button class="button2" onclick="location.href='pago.php'" type="button">Pagos</button>
    <button class="button3" onclick="location.href='msgconf.php'" type="button">Conf. msg</button>
	<form id="formmes" method="get">
        <select name="menumes" class="selectmes" required>
			<option value="">Selecione o mês</option>
			<?php
				$valorsel = date("m-Y", strtotime("-5 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("-4 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("-3 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("-2 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("-1 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y");						 if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("+1 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("+2 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("+3 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("+4 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
				$valorsel = date("m-Y", strtotime("+5 months")); if (isset($_GET['menumes']) && $_GET['menumes']==$valorsel) 
				{ echo "<option value=$valorsel selected>$valorsel</option>"; } else { echo "<option value=$valorsel>$valorsel</option>"; }
			?>
        </select>
	</form>
	<button class="button" type="submit" form="formmes">Enviar</button>
</div> 

<div id="overlay" class="overlay">
    <script>
    const refreshIntervalId = setInterval(function() {
        var elem = document.getElementById('overlay');
        elem.scrollTop = elem.scrollHeight;
    }, 1000)
    </script>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['menumes'])) {$valorsel = $_GET['menumes'];}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

$result = $conn->query("SELECT upper(vtab_titulos.nome_res) as nome_res, 
                        REGEXP_REPLACE(vtab_titulos.celular,'[( )-]+','') AS `celular`, 
                        DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
                        DATE_FORMAT(vtab_titulos.datapag,'%d/%m/%y') AS `datapag`,
                        vtab_titulos.linhadig, sis_qrpix.qrcode 
                        FROM vtab_titulos 
                        INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
                        WHERE DATE_FORMAT(datapag,'%m-%Y') = '$valorsel'
                        AND (vtab_titulos.status = 'pago')
                        ORDER BY nome_res ASC, datavenc ASC;");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dados[] = array($row["nome_res"], $row["celular"], $row["datavenc"], $row["datapag"], $row["linhadig"], $row["qrcode"]);
    }
} else { $dados[] = array('Vazio', '-', '-', '-', '-', '-');}
$conn->close();
?>

<?php
if (!empty($_POST)) {

    if (isset($_POST['posttodos'])) {

        $db = new SQLite3('db/msgdb.sqlite3');
        $sql = "SELECT * FROM msgpago";
        $result = $db->query($sql);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgpago = $row['msg'];}
        unset($db);

        if (ob_get_level() == 0) {ob_start();}

        foreach ($dados as list($nome, $celular, $datavenc, $datapag, $linhadig, $qrcode)) {

            $buscar = array('/%provedor%/', '/%nomeresumido%/', '/%vencimento%/', '/%pagamento%/', '/%linhadig%/', '/%copiacola%/', '/%site%/');
            $substituir = array($provedor, $nome, $datavenc, $datapag, $linhadig, $qrcode, $site, '\1 \2');
            $msg = preg_replace($buscar, $substituir, $msgpago);

            if (isset($_POST['posttodos'])) {

                echo "<br><br>";
                echo "Enviando mensagem para: $nome <br>";

                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_PORT => "8000",
                    CURLOPT_URL => "http://$mwsm:8000/send-message",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "to=55$celular&msg=$msg",
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Basic YWRtaW46YWRtaW4=",
                        "Content-Type: application/x-www-form-urlencoded",
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "Erro: " . $err;
                } else {
                    echo $response;
                }

                ob_flush();
                flush();

                $root = $_SERVER["DOCUMENT_ROOT"]; $dir = $root . "/mkmsg"; $month  = date("Y-m");
                if (!is_dir("$dir/logs/" .$month))					{ mkdir("$dir/logs/" .$month); }
                if (!is_dir("$dir/logs/" .$month. "/pago")) 		{ mkdir("$dir/logs/" .$month. "/pago"); }
                if (!is_file("$dir/logs/" .$month . "/pago/index.php")) { copy("$dir/logs/.ler/pago/index.php", "$dir/logs/" .$month . "/pago/index.php"); }
                file_put_contents("$dir/logs/" .$month. "/pago/pago_" . date("d-M-Y") . ".log", date("d-M-Y;") . date("H:i:s;") . $nome . ";" . $err . $response ."\n", FILE_APPEND);

                sleep(rand($tempomin, $tempomax));
            }

        }
        ob_end_flush();
        if (isset($_POST['posttodos'])) {echo "<p><font size='5'>Fim do envio!<br></font></p>";}
    }
}

if (!empty($_POST)) {

    if (isset($_POST['postsel'])) {

        $nome = $_POST['nome'];
        $celular = $_POST['celular'];
        $datavenc = $_POST['datavenc'];
        $datapag = $_POST['datavenc'];
        $linhadig = $_POST['linhadig'];
        $qrcode = $_POST['qrcode'];
        $check = $_POST['check'];

        $db = new SQLite3('db/msgdb.sqlite3');
        $sql = "SELECT * FROM msgpago";
        $result = $db->query($sql);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgpago = $row['msg'];}
        unset($db);

        $existesel = 0;

        if (ob_get_level() == 0) {ob_start();}

        foreach ($nome as $num => $valor) {

            $buscar = array('/%provedor%/', '/%nomeresumido%/', '/%vencimento%/', '/%pagamento%/', '/%linhadig%/', '/%copiacola%/', '/%site%/');
            $substituir = array($provedor, $nome[$num], $datavenc[$num], $datapag[$num], $linhadig[$num], $qrcode[$num], $site, '\1 \2');
            $msg = preg_replace($buscar, $substituir, $msgpago);

            if ($check[$num] == "1") {

                echo "<br><br>";
                echo "Enviando mensagem para: $nome[$num] <br>";

                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_PORT => "8000",
                    CURLOPT_URL => "http://$mwsm:8000/send-message",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "to=55$celular[$num]&msg=$msg",
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Basic YWRtaW46YWRtaW4=",
                        "Content-Type: application/x-www-form-urlencoded",
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "Erro: " . $err;
                } else {
                    echo $response;
                }

                ob_flush();
                flush();
                $existesel = 1;

                $root = $_SERVER["DOCUMENT_ROOT"]; $dir = $root . "/mkmsg"; $month  = date("Y-m");
                if (!is_dir("$dir/logs/" .$month))					{ mkdir("$dir/logs/" .$month); }
                if (!is_dir("$dir/logs/" .$month. "/pago")) 		{ mkdir("$dir/logs/" .$month. "/pago"); }
                if (!is_file("$dir/logs/" .$month . "/pago/index.php")) { copy("$dir/logs/.ler/pago/index.php", "$dir/logs/" .$month . "/pago/index.php"); }
                file_put_contents("$dir/logs/" .$month. "/pago/pago_" . date("d-M-Y") . ".log", date("d-M-Y;") . date("H:i:s;") . $nome[$num] . ";" . $err . $response ."\n", FILE_APPEND);

                sleep(rand($tempomin, $tempomax));
            }

        }
        ob_end_flush();
        if (isset($_POST['postsel']) && ($existesel == 1)) {echo "<p><font size='5'>Fim do envio!<br></font></p>";} 
    	elseif (isset($_POST['postsel']) && ($existesel == 0)) {echo "<p><font size='5'>Nenhum item marcado!<br></font></p>";}
    }
}
?>
</div>

<form enctype="multipart/form-data" id="form" name="form" method="post" 
	onsubmit="return confirm('Confirma o Envio? \n\nOBS: Se clicou em enviar a todos, será enviado a todos além dos 10 mostrados por padrão.');">
    <table id="table_id" class="display responsive" width="100%">
        <thead>
            <tr>
             <th>NOME:</th>
             <th>CELULAR:</th>
             <th>DATA VENC:</th>
             <th>DATA PAG:</th>
             <th></th>
            </tr>
        </thead>
        <tbody>

          <?php
          foreach ($dados as list($nome, $celular, $datavenc, $datapag, $linhadig, $qrcode)) {
          echo "
                <tr>
                <td><input type=hidden name='nome[]'		value='$nome'>$nome</td>
                <td><input type=hidden name='celular[]' 	value='$celular'>$celular</td>
                <td><input type=hidden name='datavenc[]'	value='$datavenc'>$datavenc</td>
                <td><input type=hidden name='datapag[]'		value='$datapag'>$datapag</td>
                <td><input type=hidden name='linhadig[]'	value='$linhadig'>
                    <input type=hidden name='qrcode[]'		value='$qrcode'>
                    <input type='hidden' name='check[]' 	value='0'>
                    <input type='checkbox' class='check' 
                           onclick='this.previousElementSibling.value=1-this.previousElementSibling.value'>
                </td>
                </tr>
              ";
          }
          ?>

        </tbody>
    </table>
    <br><br>
    <div class="menu">
        <div><button class="button" name="posttodos" type="submit">Enviar para todos</button></div>
        <div><button class="button" name="postsel" type="submit" >Enviar para selecionados</button></div>
        <div><button class="button" onclick="window.open('logs/', '_blank')" type="button">Verificar Logs</button></div>
    </div>
 </form>
</body>
          
<script>
function sleep(time) {return new Promise((resolve) => setTimeout(resolve, time));}
sleep(2000).then(() => {clearInterval(refreshIntervalId);});
overlay.style.setProperty('max-height', '200px');
</script>
