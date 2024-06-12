<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>MK-MSG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.8/r-3.0.2/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.8/r-3.0.2/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                order: [
                    [0, 'asc']
                ],
                select: false,
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 10001, targets: 1 },
                    { responsivePriority: 2, targets: -1 }
                                    ],
                pagingType: 'numbers',
                language: {
                    search: "Buscar:",
                    "lengthMenu": "_MENU_",
                    "zeroRecords": "Sem registros",
                	"emptyTable": "Selecione o dia e clique em enviar no menu acima.",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "Sem registros disponíveis",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    select: {
                        rows: {
                            _: "Selecionados %d linhas",
                            0: "Clique em uma linha para selecionar",
                            1: "Selecionado 1 linha"
                        }
                    }
                }
            });
        });
    </script>
    <style>
        body {
            font-family: Consolas, "Trebuchet MS", Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        .menu {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .select1 {
            cursor: pointer;
            width: 160px;
            height: 50px;
            border: solid 2px #00b32b;
            border-radius: 5px;
            padding: 5px 5px;
            margin-top: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 16px;
            font-family: consolas, sans-serif;
        }

        .button,
        .submit {
            background-color: #00b32b;
            border: none;
            border-radius: 5px;
            color: white;
            width: 160px;
            height: 50px;
            padding: 5px 5px;
            margin-top: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 16px;
            font-family: consolas, sans-serif;
            cursor: pointer;
        }

        .button2,
        .submit {
            background-color: #003fff;
            border: none;
            border-radius: 5px;
            color: white;
            width: 160px;
            height: 50px;
            padding: 5px 5px;
            margin-top: 10px;
            text-align: center;
            font-size: 16px;
            font-family: consolas, sans-serif;
            cursor: pointer;
        }

        .button3,
        .submit {
            background-color: #395dca;
            border: none;
            border-radius: 5px;
            color: white;
            width: 160px;
            height: 50px;
            padding: 5px 5px;
            margin-top: 10px;
            text-align: center;
            font-size: 16px;
            font-family: consolas, sans-serif;
            cursor: pointer;
        }

        div.dt-container div.dt-layout-cell {
            display: inline;
        }

        div.dt-container .dt-input {
            font-size: 18px;
        }

        .dt-container .dt-length {
            float: left;
            padding-bottom: 10px;
        }

        .dt-container .dt-search {
            float: none;
            text-align: right;
            padding-bottom: 10px;
        }

        table.dataTable thead th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        table.dataTable>tbody>tr>th,
        table.dataTable>tbody>tr>td {
            padding-right: 35px;
        }

        table.dataTable.display tbody tr:hover {
            box-shadow: inset 0 0 0 9999px rgb(13 110 253 / 26%);
        }

        div.dt-container .dt-info,
        div.dt-container .dt-paging {
            text-align: center;
            padding-top: 10px;
        }

        div.dt-container .dt-paging .dt-paging-button {
            padding: 1.5em 2.0em;
        }
    </style>
</head>
<body>
<form>
    <div class="menu">
        <button class="button2" onclick="location.href='../noprazo'" type="button">No Prazo</button>
        <button class="button3" onclick="location.href='../pago'" type="button">Pagos</button>
        <button class="button3" onclick="location.href='../vencido'" type="button">Vencidos</button>
        <button class="button3" onclick="location.href='../../'" type="button">Voltar</button>
    
            <select class="select1" name="arquivolog" required>
                <option value="">Selecione o dia</option>
                <?php 
                foreach(glob('*.log') as $filename){

                        $filename = basename($filename);
                        echo "<option value='" . $filename . "'>".$filename."</option>";
                    }
                ?>
            </select>
            <button class="button" type="submit">ENVIAR</button>

    </div>
</form>
    <table id="table_id" class="display responsive">
        <thead>
            <tr>
                <th>Data:</th>
                <th>Hora:</th>
                <th>Nome:</th>
                <th>Resultado:</th>
            </tr>
        </thead>
        <tbody>
            <?php
	            if ( isset( $_GET["arquivolog"] )) {
                $file = fopen($_GET["arquivolog"], "r") or die("Não foi possível carregar o arquivo!");
                    while($resultorig = fgets($file)) {
                        if (!feof($file)) {
							$buscar = array('/{"Status":"Success".*/', '/{"Status":"Fail".*/');
							$substituir = array('Enviado com sucesso!','Erro ao enviar!','\1 \2');
							$result = preg_replace($buscar,$substituir,$resultorig);
            	            list($data, $hora, $nome, $resultado) = array_pad(explode(";", $result), 4, null);
            ?>
        <tr>
            <td><?=$data ?></td>
            <td><?=$hora ?></td>
            <td><?=$nome ?></td>
            <td><?=$resultado ?></td>
        </tr>
            <?php
        				}
                    }
                fclose($file);
                }
            ?>
        </tbody>
    </table>
</body>
</html>