# Sistema MK-MSG

Sistema simples com integração entre MK-Auth e o sistema de envio de mensagens por WhatsApp.
<br>Isso foi necessário porque o envio de SMS no MK-Auth não funciona nas versões mais novas.

<br>O sistema só vai mostrar os dados do mês e ano atual.

<br>Painel:

![MK-MSG](https://github.com/usuariomega/mkmsg/assets/70543919/f0ea5018-c46d-4ccb-a538-debe50d3cde6)

<br>Envio:

<img width="660" alt="MK-MSG2" src="https://github.com/usuariomega/mkmsg/assets/70543919/09aff231-7819-4d5f-b37d-fe5033244154">

<br>Resultado:

<img width="346" alt="MK-MSG3" src="https://github.com/usuariomega/mkmsg/assets/70543919/e8ea8926-181b-4624-a02f-b7f7062c43ff">


<br>

<br>Como Instalar:
<br>

<br>É necessário ter o sistema de WhatsApp instalado. 
<br>Por favor instale esse primeiro: https://github.com/MKCodec/Mwsm


<br>Instale o sistema MK-MSG:

<br>OBS: Recomendo fazer isso em uma máquina virtual nova. 
<br>Não é recomendado instalar na mesma máquina do MK-Auth.

<br>Instalar os pacotes:
```sh
sudo apt update
sudo apt install apache2 apache2-utils sqlite3 php php-mysql php-sqlite3 php-curl git
```
<br>


> [!NOTE]
>Config se instalado em Ubuntu ou Debian
```sh
cd /var/www/html/
sudo git clone https://github.com/usuariomega/mkmsg.git
cd /var/www/html/mkmsg/
```
Dar permissão para poder gravar no banco de dados
```sh
sudo chown www-data -R db/
```
<br>


> [!NOTE]
> Config se instalado em Mk-Auth
```sh
cd /var/www/
sudo git clone https://github.com/usuariomega/mkmsg.git
cd /var/www/mkmsg/
```
Dar permissão para poder gravar no banco de dados
```sh
sudo chown www-data -R db/
```
<br><br>


<br>Criar senha para proteger o acesso ao sistema
```sh
sudo htpasswd -c /etc/apache2/.htpasswd admin
```

Ativar o Apache para que leia o arquivo .htaccess e peça a senha ao acessar
```sh
sudo sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
```

Segurança - Não mostrar versão do Apache
```sh
sudo sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-enabled/security.conf
sudo sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-enabled/security.conf
```

Reinicie o Apache
```sh
sudo service apache2 restart
```

Edite o arquivo config e configure o nome do provedor e o site
```sh
cd /var/www/html/mkmsg/
```
ou 
```sh
cd /var/www/mkmsg/
```
<br>Em seguida:
```sh
sudo nano config.php
```


As mensagens personalizadas você pode editar on-line pelo site.

