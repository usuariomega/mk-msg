<?php

//IP do MK-Auth
$servername = "127.0.0.1";

//Usuário do banco de dados do do MK-Auth
$username 	= "nomedousuario";

//Senha do banco de dados do do MK-Auth
$password 	= "suasenha";

//Nome do banco de dados do do MK-Auth
$dbname		= "mkradius";

//Nome do seu provedor
$provedor	= "XYZ";

//Site do seu provedor (OBS: não coloque https://)
$site		= "www.xyz.com.br";

//IP do MkAuth WhatsApp Send Message
$mwsm		= "127.0.0.1";

//Envio automático: Quantos dias antes do prazo
//Para os títulos no prazo
//Lembre de configurar o cron conforme o tutorial
$diasnoprazo= 3;

//Envio automático: Quantos dias após vencer
//Para os títulos vencidos
//Lembre de configurar o cron conforme o tutorial
$diasvencido= 3;

//Envio automático: Quantos dias após pago
//Para os títulos pagos
//Lembre de configurar o cron conforme o tutorial
$diaspago	= 3;

//Tempo de pausa no envio das mensagens entre os clientes
//Será escolhido um valor entre 10 a 90 segundos conforme valores abaixo
//Exemplo: Cliente 2 tempo de envio 40 segundos, Cliente 3, tempo de envio 65 segundos
//Valores em segundos
$tempomin = 10;
$tempomax = 90;


//Não mexa abaixo!!
//Consultas SQL para buscar os clientes no prazo, vencidos e pagos
$sqlnoprazo = "SELECT upper(vtab_titulos.nome_res) as nome_res, 
			   REGEXP_REPLACE(vtab_titulos.celular,'[( )-]+','') AS `celular`, 
			   DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
			   vtab_titulos.linhadig, sis_qrpix.qrcode 
			   FROM vtab_titulos 
			   INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
			   WHERE DATE_FORMAT(datavenc,'%y-%m') = DATE_FORMAT(NOW(),'%y-%m')
			   AND (vtab_titulos.status = 'aberto')
			   ORDER BY nome_res ASC, datavenc ASC;";

$cronnoprazo = "SELECT upper(vtab_titulos.nome_res) as nome_res, 
			   REGEXP_REPLACE(vtab_titulos.celular,'[( )-]+','') AS `celular`, 
			   DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
			   vtab_titulos.linhadig, sis_qrpix.qrcode 
			   FROM vtab_titulos 
			   INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
			   WHERE DATE_FORMAT(datavenc,'%d/%m/%y') = DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL +$diasnoprazo DAY),'%d/%m/%y')
			   AND (vtab_titulos.status = 'aberto')
			   ORDER BY nome_res ASC, datavenc ASC;";

$cronvencido = "SELECT upper(vtab_titulos.nome_res) as nome_res, 
			   REGEXP_REPLACE(vtab_titulos.celular,'[( )-]+','') AS `celular`, 
			   DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
			   vtab_titulos.linhadig, sis_qrpix.qrcode 
			   FROM vtab_titulos 
			   INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
			   WHERE DATE_FORMAT(datavenc,'%d/%m/%y') = DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL -$diasvencido DAY),'%d/%m/%y')
			   AND (vtab_titulos.status = 'vencido')
			   ORDER BY nome_res ASC, datavenc ASC;";

$cronpago	= "SELECT upper(vtab_titulos.nome_res) as nome_res, 
			   REGEXP_REPLACE(vtab_titulos.celular,'[( )-]+','') AS `celular`, 
			   DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
			   vtab_titulos.linhadig, sis_qrpix.qrcode 
  			   FROM vtab_titulos 
			   INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
			   WHERE DATE_FORMAT(datapag,'%d/%m/%y') = DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL -$diaspago DAY),'%d/%m/%y')
        	   AND (vtab_titulos.status = 'pago')
			   ORDER BY nome_res ASC, datavenc ASC;";

?>