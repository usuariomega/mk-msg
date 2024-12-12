<form enctype="multipart/form-data" id="form" name="form" method="post" 
	onsubmit="return confirm('Confirma o Envio? \n\nOBS: Se clicou em enviar a todos, será enviado a todos além dos 10 mostrados por padrão.');">
    <table id="table_id" class="display responsive" width="100%">
        <thead>
            <tr>
             <th>NOME:</th>
             <th>CELULAR:</th>
             <th>DATA VENC:</th>
             <th></th>
            </tr>
        </thead>
        <tbody>

          <?php
          foreach ($dados as list($nome, $celular, $datavenc, $linhadig, $qrcode)) {
          echo "
                <tr>
                <td><input type=hidden name='nome[]'		value='$nome'>$nome</td>
                <td><input type=hidden name='celular[]' 	value='$celular'>$celular</td>
                <td><input type=hidden name='datavenc[]'	value='$datavenc'>$datavenc</td>
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
        <div><button class="button" onclick="window.open('logs/', '_blank')" type="button">Verificar logs</button></div>
    </div>
 </form>
</body>

<script>
function sleep(time) {return new Promise((resolve) => setTimeout(resolve, time));}
sleep(2000).then(() => {clearInterval(refreshIntervalId);});
overlay.style.setProperty('max-height', '200px');
</script>