<?php
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

$result = $conn->query($cronnoprazo);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dados[] = array($row["nome_res"], $row["celular"], $row["datavenc"], $row["linhadig"], $row["qrcode"]);
    }
} else { echo "\nSem dados para enviar! \n"; exit();}
$conn->close();
?>

<?php
if (!empty($_POST)) {

    if (isset($_POST['posttodos'])) {

        $db = new SQLite3('db/msgdb.sqlite3');
        $sql = "SELECT * FROM msgnoprazo";
        $result = $db->query($sql);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {$msgnoprazo = $row['msg'];}
        unset($db);

        if (ob_get_level() == 0) {ob_start();}

        foreach ($dados as list($nome, $celular, $datavenc, $linhadig, $qrcode)) {

            $buscar = array('/%provedor%/', '/%nomeresumido%/', '/%vencimento%/', '/%linhadig%/', '/%copiacola%/', '/%site%/');
            $substituir = array($provedor, $nome, $datavenc, $linhadig, $qrcode, $site, '\1 \2');
            $msg = preg_replace($buscar, $substituir, $msgnoprazo);

            if (isset($_POST['posttodos'])) {

                echo "\n";
                echo date('d-M-Y') . "  " . date("H:i:s") . "  " . "Enviando mensagem para: " . str_pad($nome,20);

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
                if (!is_dir("$dir/logs/" .$month. "/noprazo")) 		{ mkdir("$dir/logs/" .$month. "/noprazo"); }
                if (!is_file("$dir/logs/" .$month . "/noprazo/index.php")) { copy("$dir/logs/.ler/noprazo/index.php", "$dir/logs/" .$month . "/noprazo/index.php"); }
                file_put_contents("$dir/logs/" .$month. "/noprazo/noprazo_" . date("d-M-Y") . ".log", date("d-M-Y;") . date("H:i:s;") . $nome . ";" . $err . $response ."\n", FILE_APPEND);

                sleep(rand($tempomin, $tempomax));
            }

        }
        ob_end_flush();
        if (isset($_POST['posttodos'])) {echo "\n\nFim do envio!\n\n";}
    }

}
else {echo "Use o Cron para automatizar esse envio.
           <br>Lembre de mudar <b>suasenha</b> pela senha criada em sudo htpasswd -c /etc/apache2/.htpasswd admin<br>
           <br>Comando:<br> sudo crontab -e <br>
           0 9 * * * curl -X POST -F 'posttodos=1' http://admin:suasenha@127.0.0.1/mkmsg/cronnoprazo.php > /dev/null 2>&1";}
?>