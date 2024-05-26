<?php

//IP do MK-Auth
$servername = "127.0.0.1";

//Usuário do banco de dados do do MK-Auth
$username 	= "root";

//Senha do banco de dados do do MK-Auth
$password 	= "vertrigo";

//Nome do banco de dados do do MK-Auth
$dbname		= "mkradius";

//Nome do seu provedor
$provedor	= "XYZ";

//Site do seu provedor (OBS: não coloque https://)
$site		= "www.xyz.com.br";

//IP do MkAuth WhatsApp Send Message
$mwsm		= "127.0.0.1";

//Não mexa abaixo baixo!!
//Consultas SQL para buscar os clientes no prazo, vencidos e pagos
$sqlnoprazo = "SELECT upper(vtab_titulos.nome_res) as nome_res, 
			   REGEXP_REPLACE(vtab_titulos.celular,'[()-]+','') AS `celular`, 
			   DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
			   vtab_titulos.linhadig, sis_qrpix.qrcode 
			   FROM vtab_titulos 
			   INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
			   WHERE DATE_FORMAT(datavenc,'%y-%m') = DATE_FORMAT(NOW(),'%y-%m')
			   AND (vtab_titulos.status = 'aberto')
			   ORDER BY nome_res ASC, datavenc ASC;";

$sqlvencido = "SELECT upper(vtab_titulos.nome_res) as nome_res, 
			   REGEXP_REPLACE(vtab_titulos.celular,'[()-]+','') AS `celular`, 
			   DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
			   vtab_titulos.linhadig, sis_qrpix.qrcode 
			   FROM vtab_titulos 
			   INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
			   WHERE DATE_FORMAT(datavenc,'%y-%m') = DATE_FORMAT(NOW(),'%y-%m')
			   AND (vtab_titulos.status = 'vencido')
			   ORDER BY nome_res ASC, datavenc ASC;";

$sqlpago	= "SELECT upper(vtab_titulos.nome_res) as nome_res, 
			   REGEXP_REPLACE(vtab_titulos.celular,'[()-]+','') AS `celular`, 
			   DATE_FORMAT(vtab_titulos.datavenc,'%d/%m/%y') AS `datavenc`,
			   vtab_titulos.linhadig, sis_qrpix.qrcode 
  			   FROM vtab_titulos 
			   INNER JOIN sis_qrpix ON vtab_titulos.uuid_lanc = sis_qrpix.titulo 
			   WHERE DATE_FORMAT(datavenc,'%y-%m') = DATE_FORMAT(NOW(),'%y-%m')
        	   AND (vtab_titulos.status = 'pago')
			   ORDER BY nome_res ASC, datavenc ASC;";

?>