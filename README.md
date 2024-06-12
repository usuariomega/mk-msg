# Sistema MK-MSG

Sistema simples com integração entre MK-Auth e o sistema de envio de mensagens por WhatsApp Mwsm.

Permite o envio de mensagens para todos os clientes com títulos no prazo, vencido ou pagos de forma manual ou automática.

<br>

### Painel:
![Screenshot 2024-06-12 at 19-48-26 MK-MSG](https://github.com/usuariomega/mkmsg/assets/70543919/1b6e63d0-000e-4c11-b502-24325bb34e79)

<br>

### Envio:
![Screenshot 2024-06-12 at 19-53-18 MK-MSG](https://github.com/usuariomega/mkmsg/assets/70543919/732f8471-bff2-40a7-acd2-e8b5f57ce0e8)

<br>

### Resultado:
<img width="346" alt="MK-MSG3" src="https://github.com/usuariomega/mkmsg/assets/70543919/e8ea8926-181b-4624-a02f-b7f7062c43ff">

<br>
<br>
<br>

### Leitor de Logs:
![Screenshot 2024-06-12 at 19-55-05 MK-MSG](https://github.com/usuariomega/mkmsg/assets/70543919/5aad9b05-11b2-4aef-aaaa-e9a3155792c9)

<br>

### Como Instalar: 

### 1. É necessário ter o sistema de WhatsApp instalado.
Por favor instale primeiro: https://github.com/MKCodec/Mwsm

<br>

### 2. Instale o sistema MK-MSG:
Recomendo fazer isso em uma máquina virtual nova. 

<details>
<summary> Clique aqui para expandir </summary>

### Instalar os pacotes: 

```sh
sudo apt update
sudo apt install apache2 apache2-utils sqlite3 php php-mysql php-sqlite3 php-curl git
```

<br>

<details>
<summary>

## Se instalado em Ubuntu ou Debian: Clique Aqui

</summary>

```sh
cd /var/www/html/
sudo git clone https://github.com/usuariomega/mkmsg.git
cd /var/www/html/mkmsg/
```

Dar permissão ao Apache para poder gravar no banco de dados as mensagens personalizadas e salvar os logs dos envios
```sh
sudo chown -R www-data:www-data db/
sudo chown -R www-data:www-data logs/
```
</details>

<details>
<summary>

## Se instalado no Mk-Auth:  Clique Aqui

</summary>
  
```sh
cd /var/www/
sudo git clone https://github.com/usuariomega/mkmsg.git
cd /var/www/mkmsg/
```

Dar permissão ao Apache para poder gravar no banco de dados as mensagens personalizadas e salvar os logs dos envios

```sh
sudo chown -R www-data:www-data db/
sudo chown -R www-data:www-data logs/
```

Dar permissão ao Apparmor para que o PHP possa ler e gravar no banco de dados as mensagens personalizadas

```sh
sed -i 's/}$/        \/var\/www\/mkmsg\/** rwk, }/g' /etc/apparmor.d/usr.sbin.php-fpm7.3
sudo apparmor_parser -r /etc/apparmor.d/usr.sbin.php-fpm7.3
```

Permitir que o Mk-Auth deixe acessar o endereço /mkmsg (MK-Auth 23 em diante)

```sh
sudo nano /var/www/.htaccess
```

Apague a ultima linha:

```sh
RewriteRule ^([a-zA-Z0-9\/]+)$ index.hhvm?$1 [NC,L]
```

Em seguida Ctrl + X para salvar. Aperte (Y ou S) + enter para salvar. (Depende do idioma do sistema operacional).

</details>

<br>

### Prossiga com o resto da instalação comum aos 2 sistemas

### Criar senha para proteger o acesso ao sistema 
```sh
sudo htpasswd -c /etc/apache2/.htpasswd admin
```
Essa senha será pedida ao acessar o sistema em http://ip/mkmsg
Nesse caso acima, criaremos o usuario admin e a senha será definida após dar enter.
### Ativar o Apache para que leia o arquivo .htaccess e peça a senha ao acessar
```sh
sudo sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
```

### Segurança - Não mostrar versão do Apache 
```sh
sudo sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-enabled/security.conf
sudo sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-enabled/security.conf
```

### Reinicie o Apache 
```sh
sudo service apache2 restart
```

### Edite o arquivo config e configure o nome do provedor e o site 
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
</details>

<br>

### 3. Configuração do acesso ao banco de dados do MK-Auth:

<details>
<summary> Clique aqui para expandir </summary>

### Para poder ter acesso ao banco de dados, no servidor do MK-Auth faça:

Mantenha as aspas e mude o usuário e senha em: **nomedousuario** e **suasenha**

Coloque o IP de onde roda o sistema MK-MSG em **192.168.0.20** (IP de exemplo, use o IP da sua VM).

### Rode o comando abaixo para criar o usuário com permissão de leitura do banco, cole uma linha por vez:

```
mysql -uroot -pvertrigo -Dmkradius
CREATE USER 'nomedousuario'@'192.168.0.20' IDENTIFIED BY 'suasenha';
GRANT SELECT ON mkradius.* TO 'nomedousuario'@'192.168.0.20';
commit;
quit;
```

<br>

### Será necessário mudar o IP do banco de dados. 
Como o MariaDB do Mk-Auth é antigo, ele só permite adicionar um endereço de IP. Por padrão ele só roda em localhost não permitindo acesso externo. Mude para o IP local do seu MK-Auth. Se não souber, digite ifconfig e use o mesmo IP na cofiguração abaixo:

```
sudo nano /etc/mysql/conf.d/50-server.cnf 
```
Mude:
bind-address    = 127.0.0.1

Para o ip local do seu servidor Mk-Auth:
```
bind-address    = 192.168.0.150
````

Se quiser deixar aberto para qualquer IP mude para (bind-address    = 0.0.0.0). Não recomendo essa prática por questões de segurança. 

Em seguida:
```
sudo service mysql restart
```

### No arquivo config.php no sistema MK-MSG mude para:

//IP do MK-Auth
<br>$servername = "192.168.0.150";

//Usuário do banco de dados do do MK-Auth
<br>$username 	= "nomedousuario";

//Senha do banco de dados do do MK-Auth
<br>$password 	= "suasenha";

//Nome do banco de dados do do MK-Auth
<br>$dbname		= "mkradius";

</details>

<br>

### 4. Configuração para automatizar os envios:

<details>
<summary> Clique aqui para expandir </summary>

### Será necessário configurar a quantidade de dias antes e depois no arquivo config.php

//Quantos dias antes do prazo
<br>$diasnoprazo= 3;

//Quantos dias após vencer
<br>$diasvencido= 3;

//Quantos dias após pago
<br>$diaspago	= 3;

Exemplos:
<br>Título vence dia 10, hoje é dia 7, será enviado a mensagem a todos que vencem no dia 10. Consulta SQL = (07 + 3).
<br>Título venceu dia 04, hoje é dia 7, será enviado a mensagem a todos que venceram no dia 04. Consulta SQL = (07 - 3).
<br>Título foi pago dia 12, hoje é dia 15, será enviado a mensagem a todos que pagaram no dia 12. Consulta SQL = (15 - 3).

### Configurando a automação:

```
sudo crontab -e
```
Adicione no final:

Lembre de mudar **suasenha** pela senha criada em sudo htpasswd -c /etc/apache2/.htpasswd admin
```
0 9  * * * curl -X POST -F 'posttodos=1' http://admin:suasenha@127.0.0.1/mkmsg/cronnoprazo.php > /dev/null 2>&1
0 10 * * * curl -X POST -F 'posttodos=1' http://admin:suasenha@127.0.0.1/mkmsg/cronvencido.php > /dev/null 2>&1
0 11 * * * curl -X POST -F 'posttodos=1' http://admin:suasenha@127.0.0.1/mkmsg/cronpago.php > /dev/null 2>&1
````

Será enviado todos os dias as 9h para mensagens com títulos no prazo, 10h para mensagens com títulos vencidos e 11h para mensagens com títulos pagos.

OBS: Se a consulta não retornar títulos, não será enviado.

Exemplo: Configurado dias no prazo para 3 dias, hoje é dia 10, será enviado a mensagem para todos que vencem no dia 13. 

**Se não houver títulos para o dia 13, não será enviado.** E assim por diante:

- Dia 14 + 3 = Envia mensagem se existir título a vencer (no prazo) no dia 17
- Dia 15 + 3 = Envia mensagem se existir título a vencer (no prazo) no dia 18

</details>

<br>
<br>

### 5. Todos os logs estarão em http://ip/mkmsg/logs

### 6. As mensagens personalizadas de envio você pode editar on-line pelo site.

### 7. **Acesse o sistema em http://ip/mkmsg**
