<div class="container-fluid" ng-controller="appCtl">

	<style>
	.cont{
		width:50%;
		min-width:218px;
		margin-left:25%;
 	}

 	@media (max-width:550px) {

		.cont{
			width:100%;
			margin-left:0px;
			margin-right:0px;
		}

	}

	.modal-backdrop{ /* O fundo preto do Modal só vai aparecer se tiver esta classe. Bug do Angular */
	  bottom: 0;
	  position: fixed;
	}
		
	#form-create-db{
		display:none;
	}

	#form-system-config{
		display:none;
	}

	#form-create-admin{
		display:none;
	}

	#form-adjust-datehour{
		display:none;
	}

	#form-adjust-email{
		display:none;
	}

	</style>

		<?php echo form_open(base_url('install/doinstall'), array('id' => '','novalidate' => '', 'class' => 'waiting')); ?>

			<div class="row" style="padding:20px;">

			<div class="cont" ng-hide="hide.databaseForm">
	  		
				<h1>Instalação do Sistema do Seminário - Passo 1</h1>
				<hr/>
				<p>
				Bem vindo à instalação do <b>Sistema do Seminário</b>. Antes de continuar, verifique se o seu servidor atende aos seguintes requisitos:
				</p>
				<ul>
					<li><b>PHP 5.1.x</b> ou <b>mais recente</b></li>
					<li><b>Mysql 4.1</b> ou <b>mais recente</b></li>
				</ul>
				<p>
					O primeiro passo a ser realizado é verificarmos os dados para a conexão com o <b>Sistema de Gerenciamento de Banco de Dados</b>. O sistema, por enquanto, só está aceitando conexões com o <b>Mysql</b>. Vamos testá-la?
				</p>

				<div class="form-group">
		        	<label for="name">Host</label>
		            <input type="text" class="form-control" placeholder="Host" ng-model="host" autofocus>
		        </div>

		        <div class="form-group">
		        	<label for="name">Usuário</label>
		            <input type="text" class="form-control" placeholder="Usuário" ng-model="user" >
		        </div>

		        <div class="form-group">
		        	<label for="name">Senha</label>
		            <input type="password" class="form-control" placeholder="Senha" ng-model="password" required >
		        </div>

		        <hr>
		        <p class="text-danger">Você precisa <b>Testar a Conexão</b> antes de <b>Continuar</b></p>
		        <button type="button" class="btn-success btn btn-lg" dfc-btn-test-conn ng-click="testConn()" >Testar Conexão</button>
		        <button type="button" class="btn-info btn btn-lg" ng-disabled="continueIsDisabled" ng-click="databaseContinue()" >Continuar</button>

			</div>

			<div ng-hide="hide.createDatabaseForm" class="cont">
	  		
				<h1>Instalação do Sistema do Seminário - Passo 2</h1>
				<hr/>
				<p>
					Agora que já temos as informações necessárias para acessar o <b>Sistema de Gerenciamento de Banco de Dados</b>, vamos precisar <b>criar</b> um banco de dados para o <b>Sistema do Seminário</b>.
				</p>

				<div class="form-group">
		        	<label for="name">Nome do Banco de Dados</label>
		            <input type="text" class="form-control" placeholder="Nome do Banco de Dados" ng-model="databasename" required />
		        </div>

		        <hr>

		        <p class="text-danger">Antes de continuar, verifique se realmente não há banco de dados com esse nome.</p>
		        <button type="button" class="btn-info btn btn-lg btn-continue" ng-click="creatingDatabaseContinue()" >Continuar</button>

			</div>

			<!-- Configurações do Sistema -->

			<div ng-hide="hide.systemConfig" class="cont">
	  		
				<h1>Instalação do Sistema do Seminário - Passo 3</h1>
				<hr/>
				<p>
					Agora que já temos todos os dados para o acesso ao SGBD, podemos continuar com as configurações do sistema. Vamos lá?
				</p>

					<div class="form-group">
			        	<label for="name">Título do Sistema</label>
			            <input type="text" class="form-control" placeholder="Ex.: XX Seminário de Pesquisa do CCSA" name="system-title" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Domínio Permanente/Principal</label>
			            <input type="text" class="form-control" placeholder="Ex.: seminario2016.ccsa.ufrn.br" name="system-domain-perm" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Domínio Temporário</label>
			            <input type="text" class="form-control" placeholder="Ex.: seminario.ccsa.ufrn.br" name="system-domain-temp" required autofocus/>
			        </div>

			        <hr>

			        <div class="form-group">
			        	<label for="name">Data Final para Submissão de Artigos</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-limit-paper" value="" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Data Final para Submissão de Pôsteres</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-limit-poster" value="" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Data Final para Submissão de Minicursos</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-limit-minicourse" value="" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Data Final para Submissão de Mesa-Redondas</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-limit-roundtable" value="" required autofocus/>
			        </div>

			        <hr>

			        <p class="text-warning">Datas <b>inicial</b> e <b>final</b> para os participantes do seminário se inscreverem em minicursos.</p>

			        <div class="form-group">
			        	<label for="name">Data Inicial para Inscrições em Minicursos</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-ini-minicourse" value="" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Data Final para Inscrições em Minicursos</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-end-minicourse" value="" required autofocus/>
			        </div>

			        <hr>

			        <p class="text-warning">Datas <b>inicial</b> e <b>final</b> para os participantes do seminário se inscreverem em mesas-redondas.</p>

			        <div class="form-group">
			        	<label for="name">Data Inicial para Inscrições em Mesas-Redondas</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-ini-roundtable" value="" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Data Final para Inscrições em Mesas-Redondas</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-end-roundtable" value="" required autofocus/>
			        </div>

			        <hr>

			        <p class="text-warning">Datas <b>inicial</b> e <b>final</b> para os participantes do seminário se inscreverem em coferências.</p>

			        <div class="form-group">
			        	<label for="name">Data Inicial para Inscrições em Conferências</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-ini-conference" value="" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Data Final para Inscrições em Conferências</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-end-conference" value="" required autofocus/>
			        </div>

			        <hr>

			        <p class="text-warning">Datas <b>inicial</b> e <b>final</b> para os participantes do seminário se inscreverem em oficinas.</p>

			        <div class="form-group">
			        	<label for="name">Data Inicial para Inscrições em Oficinas</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-ini-workshop" value="" required autofocus/>
			        </div>

			        <div class="form-group">
			        	<label for="name">Data Final para Inscrições em Oficinas</label>
			            <input type="text" class="form-control input-data-masked" placeholder="dd/mm/aaaa" name="date-inscription-end-workshop" value="" required autofocus/>
			        </div>

			        <hr>
			        <button type="button" class="btn-info btn btn-lg btn-continue" ng-click="configuringSystemContinue()" >Continuar</button>

			</div>

			<!-- Configuração do Administrador -->	

			<div ng-hide="hide.createAdminUser"  id="form-create-admin" class="cont">
	  		
				<h1>Instalação do Sistema do Seminário - Passo 4</h1>
				<hr/>
				<p>
					Agora crie um administrador para o sistema:
				</p>

					<div class="form-group">
			        	<label for="name">Email</label>
			            <input type="text" class="form-control" placeholder="Email do Administrador" name="system-admin-email" required />
			        </div>

			        <div class="form-group">
			        	<label for="name">Senha</label>
			            <input type="password" class="form-control" placeholder="Senha Administrador" name="system-admin-pass" required />
			        </div>

			        <hr>
			        <button type="button" class="btn-info btn btn-lg btn-continue" >Continuar</button>

			</div>	

			<!-- Verificar Hora/Data -->

			<div ng-hide="hide.verifyDateHour" id="form-adjust-datehour" class="cont">
	  		
				<h1>Instalação do Sistema do Seminário - Passo 5</h1>
				<hr/>
				<p>
					Este passo é <b>importantíssimo</b>! Para garantir que o sistema irá funcionar corretamente 
					para os períodos de inscrições das diversas atividades, precisamos garantir que a 
					<b>data/hora</b> do sistema esteja ajustada.

					Abaixo o sistema irá exibir a data/hora configurada <b>atualmente</b> no servidor. Caso a
					<b>data/hora</b> não esteja de acordo com a data/hora oficial da região, você irá precisar
					configurá-la corretamente. Um dos possíveis motivos para a má configuração no servidor são os seguintes:
					<ul>
						<li>
						Má configuração da região atual no arquivo <b>index.php</b> deste sistema. Abra o arquivo index.php 
						na pasta principal deste sistema, e procure por "TIMEZONE DEFINITION", verifique se a região é a correta. 
						Caso não seja, modifique-a, salve o arquivo e verifique se o horário abaixo foi corrigido.
						</li>
						<li>
						Outro possível problema é a configuração errada do TIMEZONE nos arquivos de configuração do servidor web, 
						ou data/hora errada no próprio servidor.
						</li>
					</ul>

				</p>

				<div id="show-date-hour-server">
					
				</div>

				<p>
				Ao modificar as configurações da hora/data no servidor, nos arquivos de configurações ou no arquivo index.php,
				ela automaticamente é exibida aqui, você não precisar atualizar esta página para verificar a hora. Caso a data/hora
				esteja configurada corretamente, basta <b>continuar</b> a instalação.
				</p>

		        <hr>
		        <button type="button" class="btn-info btn btn-lg btn-continue" >Continuar</button>

			</div>	

			<!-- Configuração de Email -->

			<div ng-hide="hide.verifyEmail" id="form-adjust-email" class="cont">
	  		
				<h1>Instalação do Sistema do Seminário - Passo 6</h1>
				<hr/>
				<p>
					O último passo antes da instalação do sistema é a configuração do <b>servidor de email</b> que será utilizado. Para configurar o servidor de email, acesse <b>/application/config/email.php</b>.
					Após realizar as configurações, não é preciso atualizar esta página para enviar emails teste, com o botão abaixo, para o email definido como <b>email do administrador</b>. Ao enviar um email teste, verifique se ele chegou ao <b>email do administrador</b>. Caso tenha chegado, basta iniciar a instalação.
				</p>

				
				<button type="button" class="btn-info btn btn-lg btn-send-email-test" >Enviar email teste</button>

		        <hr>
		        <button type="submit" class="btn-success btn btn-lg btn-continue" >Instalar</button>

			</div>	

		</form>
		
	</div>

</div>