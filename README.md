# seminario-ccsa-old
Antiga versão do sistema do Seminário de Pesquisa do CCSA

## Configuração
Na instalação, é necessário criar dois arquivos:

### application/config/database.php
Arquivo contendo informações sobre a base de dados, precisa ser uma versão do **MySQL**. Em `application/config`, existe um arquivo chamado `database_template.php`, que é um template de como o arquivo deve ser configurado, basta renomeá-lo para `database.php` e substituir os campos solicitados.

### application/config/email.php
Arquivo contendo informações sobre o servidor de email. Em `application/config`, existe um arquivo chamado `email_template.php`, que é um template de como o arquivo deve ser configurado, basta renomeá-lo para `email.php` e substituir os campos solicitados.

### Mod Rewrite
O sistema utiliza o Mod Rewrite do Apache, logo ele precisa ser ativado.
