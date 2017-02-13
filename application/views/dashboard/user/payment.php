            
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Pagamento</h1>
                    
                    <?php if($success!=null): ?>
                        <div class='alert alert-success'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <strong><?php echo $success; ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($error!=null): ?>
                        <div class='alert alert-danger'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <strong><?php echo $error; ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <?php if( $user->paid=='no' || $user->paid=='rejected' ): ?>
                    
                    <?php if($user->paid=='rejected'): ?>
                        <p class="text-danger"> Seu pagamento foi rejeitado. Tente novamente. </p>
                    <?php endif; ?>
                        
                    <p>Para participar do evento, é necessário realizar o pagamento de uma taxa de inscrição seguindo os seguintes passos:</p>
                    
                    <ol>
                        <!--<li> Clicar no link do <a href="https://www.tesouro.fazenda.gov.br/pt/preenchimento" target="_blank">Tesouro Nacional</a> e seguir as orientações da guia de recolhimento da União (GRU-IMPRESSÃO).</li>-->
                        <li> 
                            <b>Com muita atenção</b>, preencher os campos do link <a href="https://consulta.tesouro.fazenda.gov.br/gru_novosite/gru_simples.asp" target="_blank">Guia de Recolhimento da União (GRU impressão)</a>, em amarelo com os seguintes códigos e informações:
                            <ol type="I">
                                <li><b>Unidade Favorecida: </b>153113 (Refere-se à Unidade Centro de Ciências Sociais Aplicadas);</li>
                                <li><b>Gestão: </b>15234 Refere-se à UFRN (Universidade Federal do Rio Grande do Norte);</li>
                                <li><b>Código de Recolhimento: </b>28911-6 (Refere-se à Descrição do Recolhimento = SFIN/SREPUG Serviços Educacionais);</li>
                                <li>Clicar em <b>Consultar</b>.</li>
                            </ol>
                        </li>
                        <li>Em seguida clicar em avançar</li>
                        <li>
                            Na página seguinte, preencher apenas os campos:
                            <ol type="I">
                                <li><b>Número de Referência: </b>1668;</li>
                                <li><b>Competência (mês/ano): </b>Mês e Ano em questão;</li>
                                <li><b>Nome do contribuinte: </b>Nome do Participante</li>
                                <li><b>CPF: </b> do Participante;</li>
                                <li><b>Valor Principal: </b><b>R$20,00</b> para <b>ALUNO</b> e <b>R$40,00</b> para <b>PROFESSOR/TÉCNICO</b></li>
                                <li><b>Valor Total: </b>mesmo que <b>Valor Principal</b></li>
                                <li>Após o preenchimento, clicar em <b>Emitir GRU</b> e imprimir a guia para pagamento no Banco do Brasil.</li>
                            </ol>
                        </li>
                        <li>Após o pagamento do GRU no Banco do Brasil, envie-nos o comprovante de pagamento juntamente com o GRU ( com número visível ) utilizando o campo a seguir:</li>
                    </ol>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div style="background-color:#c1494b;padding:20px;color:white;margin-top:10px;margin-bottom:10px;">

                                <?php echo form_open(base_url('dashboard/user/submitpayment'), array('id' => 'formSubmitPayment','novalidate' => '','class' => 'waiting')); ?>

                                    <div class="row">
                                        <div class="col-md-12" >
                                            
                                            <div class="form-group">
                                                <label for="file">Comprovante de Pagamento *</label>
                                                <p style="color:white;font-weight:bold;">IMPORTANTE: Você precisa enviar uma imagem do comprovante de pagamento e do boleto juntos, e com boa visualização dos registros numéricos dos documentos.</p>
                                                <input id="paymentupload" type="file" name="userfile" data-url="<?php echo base_url(); ?>dashboard/user/paymentupload" />
                                                <figure class="loading" style="display:none;font-size:12;margin-top:0px;"><img  src="<?php echo asset_url(); ?>img/loading.gif" /> Carregando, aguarde... </figure>
                                                <input style="width:0px;height:0px;border:none;position:absolute;top:-200px;" type="text" name="payment" value="" required />
                                                <p class="file-desc text-success"></p>
                                                <p class="text-danger" style="color:white;"><?php if($validation!=null): ?> <?php echo $validation['payment']; ?> <?php endif; ?></p>
                                            </div>
                                            
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-lg-12 success-container">
                                            <div class="success"></div>
                                            <button type="submit" class="btn btn-lg btn-success">Submeter Comprovante</button>
                                        </div>
                                    </div>
                                
                                </form>
                                
                            </div>
                        </div>
                    </div>                    

                    <p style="margin-top:10px;margin-bottom:30px;">Qualquer <b>problema, dificuldade ou dúvida</b> encontrada durante o processo de pagamento e envio do comprovante, você pode <a href="<?php echo base_url('dashboard/issue/create'); ?>">Abrir um chamado</a>.</p>
                
                    <?php elseif($user->paid=='pendent'): ?>
                
                        <p class="text-red"> Em avaliação</p>
                
                    <?php elseif($user->paid=='accepted'): ?>
                
                        <p class="text-success"> Seu pagamento foi aceito, você está inscrito!</p>
                
                    <?php elseif($user->paid=='free'): ?>
                
                        <p class="text-success"> Você foi isento do pagamento, parabêns!</p>
                
                    <?php endif; ?>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
