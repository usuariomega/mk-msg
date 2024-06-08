<?php
include 'header.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

$result = $conn->query($sqlvencido);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dados[] = array($row["nome_res"], $row["celular"], $row["datavenc"], $row["linhadig"], $row["qrcode"]);
    }
} else { $dados[] = array('Vazio', '-', '-', '-', '-');}
$conn->close();
?>

<div class="menu">
    <button class="button3" onclick="location.href='index.php'" type="button">No prazo</button>
    <button class="button2" onclick="location.href='vencido.php'" type="button">Vencidos</button>
    <button class="button3" onclick="location.href='pago.php'" type="button">Pagos</button>
    <button class="button3" onclick="location.href='msgconf.php'" type="button">Conf. msg</button>
</div>


<div id="overlay" class="overlay">
    <script>
    const refreshIntervalId = setInterval(function() {
        var elem = document.getElementById('overlay');
        elem.scrollTop = elem.scrollHeight;
    }, 1000)
    </script>

<?php
if (!empty($_POST)) {

    if (isset($_POST['posttodos'])) {

        $db = new SQLite3('db/msgdb.sqlite3');
        $sql = "SELECT * FROM msgvencido";
        $result = $db->query($sql);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgvencido = $row['msg'];}
        unset($db);

        if (ob_get_level() == 0) {ob_start();}

        foreach ($dados as list($nome, $celular, $datavenc, $linhadig, $qrcode)) {

            $buscar = array('/%provedor%/', '/%nomeresumido%/', '/%vencimento%/', '/%linhadig%/', '/%copiacola%/', '/%site%/');
            $substituir = array($provedor, $nome, $datavenc, $linhadig, $qrcode, $site, '\1 \2');
            $msg = preg_replace($buscar, $substituir, $msgvencido);

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
				if (!is_dir("$dir/logs/" .$month. "/vencido")) 		{ mkdir("$dir/logs/" .$month. "/vencido"); }
				if (!is_file("$dir/logs/" .$month . "/vencido/index.php")) { copy("$dir/logs/.ler/vencido/index.php", "$dir/logs/" .$month . "/vencido/index.php"); }

   				file_put_contents("$dir/logs/" .$month. "/vencido/vencido_" . date("d-M-Y") . ".log", date("d-M-Y;") . 
                             	   date("H:i:s;") . $nome . ";" . $err . $response ."\n", FILE_APPEND);
            	sleep(rand(30, 300));
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
        $linhadig = $_POST['linhadig'];
        $qrcode = $_POST['qrcode'];
        $check = $_POST['check'];

        $db = new SQLite3('db/msgdb.sqlite3');
        $sql = "SELECT * FROM msgvencido";
        $result = $db->query($sql);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgvencido = $row['msg'];}
        unset($db);

        $existesel = 0;

        if (ob_get_level() == 0) {ob_start();}

        foreach ($nome as $num => $valor) {

            $buscar = array('/%provedor%/', '/%nomeresumido%/', '/%vencimento%/', '/%linhadig%/', '/%copiacola%/', '/%site%/');
            $substituir = array($provedor, $nome[$num], $datavenc[$num], $linhadig[$num], $qrcode[$num], $site, '\1 \2');
            $msg = preg_replace($buscar, $substituir, $msgvencido);

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
				$root = $_SERVER["DOCUMENT_ROOT"]; $dir = $root . "/mkmsg"; $month  = date("Y-m");
				if (!is_dir("$dir/logs/" .$month))					{ mkdir("$dir/logs/" .$month); }
				if (!is_dir("$dir/logs/" .$month. "/vencido")) 		{ mkdir("$dir/logs/" .$month. "/vencido"); }
				if (!is_file("$dir/logs/" .$month . "/vencido/index.php")) { copy("$dir/logs/.ler/vencido/index.php", "$dir/logs/" .$month . "/vencido/index.php"); }

   				file_put_contents("$dir/logs/" .$month. "/vencido/vencido_" . date("d-M-Y") . ".log", date("d-M-Y;") . 
                             	   date("H:i:s;") . $nome[$num] . ";" . $err . $response ."\n", FILE_APPEND);
                $existesel = 1;
            	sleep(rand(30, 300));
            }

        }
        ob_end_flush();
        if (isset($_POST['postsel']) && ($existesel == 1)) {echo "<p><font size='5'>Fim do envio!<br></font></p>";} 
    	elseif (isset($_POST['postsel']) && ($existesel == 0)) {echo "<p><font size='5'>Nenhum item marcado!<br></font></p>";}
    }
}
?>
</div>

<?php include 'footer.php'; ?>
