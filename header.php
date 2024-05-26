<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>MK-MSG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            order: [
                [0, 'asc']
            ],
            select: true,
            responsive: true,
            pagingType: 'numbers',
            language: {
                search: "Buscar:",
                "lengthMenu": "_MENU_",
                "zeroRecords": "Sem registros",
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

    .button,
    .submit {
        background-color: #00b32b;
        border: 2px;
        border-radius: 5px;
        color: white;
        width: 180px;
        height: 50px;
        margin: 5px;
        text-align: center;
        font-size: 16px;
        font-family: sans-serif;
        cursor: pointer;
    }

    .button2,
    .submit {
        background-color: #003fff;
        border: none;
        border-radius: 5px;
        color: white;
        width: 180px;
        height: 50px;
        padding: 8px 5px;
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
        width: 180px;
        height: 50px;
        padding: 8px 5px;
        margin-top: 10px;
        text-align: center;
        font-size: 16px;
        font-family: consolas, sans-serif;
        cursor: pointer;
    }

    .check {
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
    }

    .overlay {
        margin: 20px;
        max-height: 72vh;
        background-color: #000;
        filter: alpha(opacity=80);
        -moz-opacity: 0.8;
        -khtml-opacity: 0.8;
        opacity: 0.8;
        z-index: 1;
        text-align: center;
        color: #fff;
        overflow-y: scroll;
    }

    .flex-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        font-size: 12px;
    }

    .flex-container>div {
        background-color: #f1f1f1;
        width: 400px;
        height: 250px;
        margin: 20px;
        text-align: center;
    }

    h4 {
        text-align: left;
        margin: 10px;
    }

    textarea {
        font-size: 11px;
        width: 350px;
        height: 100px;
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
        padding-right: 30px;
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
    <?php include 'config.php'; ?>