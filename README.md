# Sistema MK-MSG

Sistema simples com integração entre MK-Auth e o sistema de envio de mensagens por WhatsApp.
Isso foi necessário porque o envio de SMS no MK-Auth não funciona nas versões mais novas.

O sistema só vai mostrar os dados do mês e ano atual.

Instalar:

É necessário ter o sistema de WhatsApp instalado. 
Por favor instale esse primeiro: https://github.com/MKCodec/Mwsm

Instale o sistema MK-MSG:

OBS: Eu recomendo fazer isso em uma máquina virtual nova. 
Não é recomendado instalar na mesma máquina do MK-Auth.

sudo apt update
sudo apt install apache2 apache2-utils sqlite3 php php-sqlite3 php-curl git


		//Config se instalado em Ubuntu ou Debian
		
		git clone https://github.com/usuariomega/mkmsg.git
		cd /var/www/html/mkmsg/
		//Dar permissão para poder gravar no banco de dados
		sudo chown www-data -R db/


		//Config se instalado em Mk-Auth

		cd /var/www/
		git clone https://github.com/usuariomega/mkmsg.git
		cd /var/www/mkmsg/
		//Dar permissão para poder gravar no banco de dados
		sudo chown www-data -R db/


//Criar senha para proteger o acesso ao sistema
sudo htpasswd -c /etc/apache2/.htpasswd admin

//Ativar o Apache para que leia o arquivo .htaccess e peça a senha ao acessar
sudo sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

//Segurança - Não mostrar versão do Apache
sudo sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-available/security.conf
sudo sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf

//Reinicie o Apache
sudo service apache2 restart

//Edite o arquivo config e configure o nome do provedor e o site
cd /var/www/html/mkmsg/    ou 
cd /var/www/mkmsg/   

sudo nano config.php


As mensagens personalizadas você pode editar on-line pelo site.

