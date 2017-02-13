                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74320903-1', 'auto');
  ga('send', 'pageview');

</script>

<section style="text-align : center; margin: 40px 0px;">                                                                                                                                                                                                                                                        
    
    <!-- <a class="btn-lg btn-success" href="<?php echo base_url('getCertificate'); ?>">Acessar Certificado</a> -->
                                                        
    <!-- <a class="btn-lg btn-success" style="font-size : 24px;padding:20px;text-decoration:none;" href="http://www.seminario2016.ccsa.ufrn.br/anais">ANAIS DO SEMINÁRIO</a> -->
    
    <a style="margin:20px;" href="http://www.seminario2016.ccsa.ufrn.br" target="_blank">Anais de 2016</a>
    
</section>

<section class="news">
			
    <div class="row">
        <div class="col-xs-12">
            
            <h1> <a name="noticia" ></a> NOTÍCIAS</h1>
            
            <div class="row">
                        
                <?php if(!count($news)): ?>
                    
                    <div class="col-lg-12">
                        <p class="text-danger">Nenhuma notícia recente.</p>
                    </div>
                
                <?php else: ?>
                
                    <div class="row">
                        
                        <?php if ( isset( $fixedNew )  ) : ?>
                        
                                <div class="news-item fixed-news col-lg-3 col-md-4 col-sm-6 ">
                                    <h1><a href="<?php echo base_url('news#'.$fixedNew->id); ?>"><i class="fa fa-external-link"></i> <?php echo $fixedNew->title; ?></a></h1>
                                    <p><a href="<?php echo base_url('news#'.$fixedNew->id); ?>"><?php echo character_limiter(strip_tags($fixedNew->text),270); ?></a></p>
                                </div>
                        
                        <?php endif; ?>
                        
                        <?php if(isset($news[0])): ?>
                    
                            <div class="news-item col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                
                                <h1>
                                    <a href="<?php echo base_url('news#'.$news[0]->id); ?>">
                                        <i class="fa fa-external-link"></i> <?php echo $news[0]->title; ?>
                                    </a>
                                </h1>
                            
                                <p>
                                    <a href="<?php echo base_url('news#'.$news[0]->id); ?>"><?php echo character_limiter(strip_tags($news[0]->text),270); ?></a>
                                </p>
                                
                            </div>
                        
                        <?php endif; ?>
                        
                        <?php if(isset($news[1])): ?>
                        
                            <div class="news-item col-lg-3 col-md-4 col-sm-6 col-xs-12 hidden-xs ">
                                <h1><a href="<?php echo base_url('news#'.$news[1]->id); ?>"><i class="fa fa-external-link"></i> <?php echo $news[1]->title; ?></a></h1>
                                <p><a href="<?php echo base_url('news#'.$news[1]->id); ?>"><?php echo character_limiter(strip_tags($news[1]->text),270); ?></a></p>
                            </div>
                        
                        <?php endif; ?>
                        
                        <?php if(isset($news[2])): ?>
                        
                            <div class="news-item col-lg-3 col-md-4 col-sm-6 hidden-sm hidden-xs">
                                <h1><a href="<?php echo base_url('news#'.$news[2]->id); ?>"><i class="fa fa-external-link"></i> <?php echo $news[2]->title; ?></a></h1>
                                <p><a href="<?php echo base_url('news#'.$news[2]->id); ?>"><?php echo character_limiter(strip_tags($news[2]->text),270); ?></a></p>
                            </div>
                        
                        <?php endif; ?>
                        
                        <?php if ( !isset( $fixedNew ) ) : ?>
                        
                            <?php if(isset($news[3])): ?>
                        
                                <div class="news-item col-lg-3 col-md-4 col-sm-6 hidden-sm hidden-xs hidden-md ">
                                    <h1><a href="<?php echo base_url('news#'.$news[3]->id); ?>"><i class="fa fa-external-link"></i> <?php echo $news[3]->title; ?></a></h1>
                                    <p><a href="<?php echo base_url('news#'.$news[3]->id); ?>"><?php echo character_limiter(strip_tags($news[3]->text),270); ?></a></p>
                                </div>
                                
                            <?php endif; ?>
                        
                        <?php endif; ?>
                        
                    </div>

                    <div class="news-more col-lg-12">
                        <a href="<?php echo base_url('news'); ?>">MAIS NOTÍCIAS</a>
                    </div>
                
                <?php endif; ?>
                
                
                
            </div>
            
        </div>				
    </div>	

</section>
    
    
    <section class="download-documents">
                
        <div class="row">
            <div class="col-xs-12">
                
                <h1><a name="documentos-download" ></a>DOCUMENTOS PARA<span>DOWNLOAD</span></h1>
                
                <ul>
                    
                    <li> 
                        <a href="<?php echo asset_url(); ?>download/Normas.docx" target="_blank">
                            <i class="fa fa-file-text"></i> 
                            <span>Normas</span>
                        </a>
                    </li>
                    
                </ul>
                
            </div>				
        </div>	
    
    </section>

</div>

<section class="inscription" ng-controller="inscriptionCtl">
    
    <div class="blackback" ng-show="registerComplete">
        <p><span><i class="fa fa-check-circle"></i></span>VOCÊ FOI CADASTRADO COM SUCESSO <span><a href="" data-toggle="modal" data-target=".index-login-modal">FAÇA O LOGIN PARA CONTINUAR</a>.</span></p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                
                <h1><a name="inscription" ></a> INSCRIÇÃO<span>PARA O SEMINÁRIO</span></h1>
                
                <!-- <h1 style="color: yellow; text-align: right;"><a name="inscription" ></a> INSCRIÇÕES ATÉ 1 DE MAIO</h1> -->
                
                <?php echo form_open('noaction', array('id' => 'form-inscription','novalidate' => '', 'name' => 'fCreateUser' , 'class' => 'form-horizontal', 'ng-submit' => 'register(fCreateUser.$valid,fCreateUser)')); ?>
                    
                    <div class="form-group">
                        <label for="form-inscription-name" class="col-sm-2 control-label">Nome Completo *</label>
                        <div class="col-sm-10">
                            
                            <input type="text" class="form-control" id="form-inscription-name" ng-model="name" name="name" placeholder="Nome Completo" autocomplete="off" required ng-disabled="isLoading">
                           
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.name.$invalid || fCreateUser.name.$invalid && !fCreateUser.name.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.name.$error.required" style="color:yellow;font-weight:600;">O campo Nome é obrigatório.</p>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="form-inscription-email" class="col-sm-2 control-label">Email *</label>
                        <div class="col-sm-10">
                            
                            <input type="email" class="form-control" id="form-inscription-email" ng-model="email" placeholder="Email" name="email" autocomplete="off" required ng-disabled="isLoading">
                            
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.email.$invalid || fCreateUser.email.$invalid && !fCreateUser.email.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.email.$error.required" style="color:yellow;font-weight:600;">O campo Email é obrigatório.</p>
                                <p class="help-block" ng-show="fCreateUser.email.$error.email" style="color:yellow;font-weight:600;">Precisa ser um Email válido</p>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="form-inscription-cpf" class="col-sm-2 control-label">CPF *</label>
                        <div class="col-sm-10">
                            
                            <input type="text" class="form-control" id="form-inscription-cpf" ng-model="cpf" placeholder="CPF" name="cpf" data-mask="00000000000" autocomplete="off" required ng-disabled="isLoading">
                            
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.cpf.$invalid || fCreateUser.cpf.$invalid && !fCreateUser.cpf.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.cpf.$error.required" style="color:yellow;font-weight:600;">O campo CPF é obrigatório.</p>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="form-inscription-category"  class="col-sm-2 control-label">Categoria *</label>
                        <div class="col-sm-10">
                            
                            <select class="form-control" ng-model="category" id="form-inscription-category" name="category" required ng-disabled="isLoading">
                                <option value="instructor">Docente/Técnico-Administrativo</option>
                                <option value="student" selected> Discente</option>
                                <option value="noacademic" selected> Sem vínculo acadêmico</option>
                            </select>
                            
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.category.$invalid || fCreateUser.category.$invalid && !fCreateUser.category.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.category.$error.required" style="color:yellow;font-weight:600;">O campo Categoria é obrigatório.</p>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="form-inscription-institution" class="col-sm-2 control-label">Instituição de Origem *</label>
                        <div class="col-sm-10">
                            
                            <input type="text" class="form-control" id="form-inscription-institution" ng-model="institution" autocomplete="off" name="institution" placeholder="Instituição de Origem" ng-disabled="isLoading" required>
                            
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.institution.$invalid || fCreateUser.institution.$invalid && !fCreateUser.institution.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.institution.$error.required" style="color:yellow;font-weight:600;">O campo Instituição é obrigatório.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label for="form-inscription-phone" class="col-sm-2 control-label">Telefone *</label>
                        <div class="col-sm-10">
                            
                            <input type="text" class="form-control" id="form-inscription-phone" ng-model="phone" autocomplete="off" placeholder="Telefone" name="phone" data-mask="(00)00000-0000" required ng-disabled="isLoading">
                            
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.phone.$invalid || fCreateUser.phone.$invalid && !fCreateUser.phone.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.phone.$error.required" style="color:yellow;font-weight:600;">O campo Telefone é obrigatório.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label for="form-inscription-password" class="col-sm-2 control-label">Senha *</label>
                        <div class="col-sm-10">
                            
                            <input type="password" class="form-control" id="form-inscription-password" autocomplete="off" ng-model="password" name="password" placeholder="Senha" required ng-disabled="isLoading">
                            
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.password.$invalid || fCreateUser.password.$invalid && !fCreateUser.password.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.password.$error.required" style="color:yellow;font-weight:600;">O campo Senha é obrigatório.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="form-inscription-repassword" class="col-sm-2 control-label">Repetir Senha *</label>
                        <div class="col-sm-10">
                            
                            <input type="password" class="form-control" id="form-inscription-repassword" autocomplete="off" ng-model="repassword" name="repassword" placeholder="Repetir Senha" required ng-disabled="isLoading">
                            
                            <div  
                                ng-show="fCreateUser.$submitted && fCreateUser.repassword.$invalid || fCreateUser.repassword.$invalid && !fCreateUser.repassword.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fCreateUser.repassword.$error.required" style="color:yellow;font-weight:600;">O campo Repetir Senha é obrigatório.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-info" ng-disabled="isLoading" >
                                <span ng-show="!isLoading">Registrar-se</span>
                                <span ng-show="isLoading">Processando...</span>
                            </button>
                        </div>
                    </div>
                    
                </form>

                <!-- <p>Em breve</p> -->
            
            </div>
        </div>
                
    </div>
        
</section>

<div class="container">
    
    <section class="thematic-groups">
    
        <div class="row">
            <div class="col-xs-12">
                
                <h1><a name="grupo-tematico" ></a> GRUPOS <span>TEMÁTICOS</span></h1>
                
            </div>				
        </div>
        
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                
                <ul class="first-level">
                    <li>
                        TURISMO
                        <ul class="second-level">
                            
                            <li>
                                
                                <a data-id="0">Gestão de Turismo</a>
                                <ul class="third-level ul-0">
                                    <li><span>Coordenadores:</span> Lissa Ferreira, Luiz Mendes Filho e Leilianne Barreto.</li>
                                    <li><span>Ementa:</span> Competitividade Turística de Destinos e Organizações; Redes e Alianças Estratégicas; Marketing Turístico; Comunicação e Promoção Turística; Gestão da Qualidade dos Serviços Turísticos; Planejamento e Gestão em Turismo.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="1">Planejamento e Organização do Turismo</a>
                                <ul class="third-level ul-1">
                                    <li><span>Coordenadores:</span> Wilker Nóbrega e Kerlei Sonaglio.</li>
                                    <li><span>Ementa:</span> Turismo, governança e Participação. governabilidade Popular e as Políticas Públicas de Turismo. Teoria do Planejamento Turístico. Planejamento e Organização do Turismo. Planejamento e Segmentação Turística.</li>
                                </ul>
                                
                            </li>
                            
                            <!--<li>
                                
                                <a data-id="2">Hospitalidade</a>
                                <ul class="third-level ul-2">
                                    <li><span>Coordenadores:</span> Sueli Moreira, Michel Vieira e Saulo Gomes.</li>
                                    <li><span>Ementa:</span> Fundamentos da Hospitalidade. Gestão Hoteleira. Gestão de A&B. Gestão de Pessoas em Hospitalidade. Aspectos sociais e culturais do Turismo. Planejamento de Roteiros. Consultoria Turística e Agenciamento.</li>
                                </ul>
                                
                            </li> --> 
                            
                        </ul>
                    </li>
                </ul> <!-- END.DEPARTAMENTO DE TURISMO -->
                
                <ul class="first-level">
                    <li>
                        CIÊNCIAS <span>CONTÁBEIS</span>
                        <ul class="second-level">
                            
                            <li>
                                
                                <a data-id="3">Controladoria</a>
                                <ul class="third-level ul-3">
                                    <li><span>Coordenadores:</span> Adilson Tavares, Alexandro Barbosa e Maxwell Celestino.</li>
                                    <li><span>Ementa:</span> Sistemas de Informação, Controles de Gestão, Custos, Custeio da Qualidade, orçamento empresarial, Avaliação de desempenho, informação de custo e qualidade do gasto público.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="4">Contabilidade para Usuários Externos</a>
                                <ul class="third-level ul-4">
                                    <li><span>Coordenadores:</span> Clayton Levy.</li>
                                    <li><span>Ementa:</span> Contabilidade Societária, IFRS, Tributação, Auditoria e Perícia Contábil, Educação Fiscal, Contabilidade Pública e Governamental.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="5">Educação e Pesquisa em Contabilidade</a>
                                <ul class="third-level ul-5">
                                    <li><span>Coordenadores:</span> Aneide Oliveira Araujo e Edmilson Jovino de Oliveira.</li>
                                    <li><span>Ementa:</span> Formação do Contador; Processo de ensino-aprendizagem; Estilos e Estratégias de Aprendizagem em Ciências Contábeis; Avaliação de aprendizagem; Tecnologia da Educação: Educação online; metodos de pesquisa qualitativa e quantitativa em contabilidade; Perfil e Evolução do egresso; Expectativas de mercado e do curso de Ciências Contábeis; Avaliação de programas educacionais.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="6">Contabilidade, Controle Interno e Auditoria aplicados ao setor público</a>
                                <ul class="third-level ul-6">
                                    <li><span>Coordenadores:</span> </li>
                                    <li><span>Ementa:</span> </li>
                                </ul>
                                
                            </li>

                            <!--
                            <li>
                                
                                <a data-id="6">Informação de Custo e Qualidade do Gasto Público</a>
                                <ul class="third-level ul-6">
                                    <li><span>Coordenadores:</span> Victor Branco de Holanda.</li>
                                    <li><span>Ementa:</span> Contabilidade, Informação de Custos e Qualidade do Gasto Aplicados ao Setor Público como Instrumento de Controle Social e Cidadania.</li>
                                </ul>
                                
                            </li> -->
                            
                        </ul>
                    </li>
                </ul> <!-- END.DEPARTAMENTO DE CIÊNCIA CONTÁBEIS -->
                
                <ul class="first-level">
                    <li>
                        ECONOMIA
                        <ul class="second-level">
                            
                            <li>
                                
                                <a data-id="7">Análise Econômica Multissetorial, Estratégica e Conjuntural</a>
                                <ul class="third-level ul-7">
                                    <li><span>Coordenadores:</span>André Luís Cabral Lourenço , Denílson da Silva Araújo, Fabrício Pitombo Leite, Luziene Dantas de Macedo, Márcia Maria de Oliveira Bezerra, William Eufrasio Nunes Pereira.</li>
                                    <li><span>Ementa:</span> O GT preocupa-se com o estudo das relações entre as atividades econômicas, o qual é realizado por meio de modelos multissetoriais, e das consequências dessas inter-relações para a análise de um espaço econômico específico, seja um espaço nacional ou subnacional. Estimativas de matrizes insumo-produto, nacional e/ou estadual. Análise das relações econômicas intersetoriais para o espaço nacional, regional ou estadual. Análise conjuntural e estudos de análise estratégica para o Brasil tipicamente relacionada ao médio e longo prazos. Modelos de consistência entre fluxos e estoques (stock-flow consistent - SFC) e modelos macroeconômicos em geral.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="8">Desenvolvimento Econômico</a>
                                <ul class="third-level ul-8">
                                    <li><span>Coordenadores:</span> Denilson da Silva Araújo, William Eufrásio Nunes Pereira, Marconi Gomes da Silva, Márcia Maria de Oliveira Bezerra e André Luiz Cabral de Lourenço.</li>
                                    <li><span>Ementa:</span> Discute as contribuições teóricas e de políticas que analisem o desenvolvimento nas diversas vertentes do pensamento econômico, priorizando as questões macroeconômicas.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="9">Grupo Interdisciplinar de Estudos e Avaliação de Políticas Públicas (GIAPP)</a>
                                <ul class="third-level ul-9">
                                    <li><span>Coordenadores:</span> João Matos Filho e Lincoln Moraes de Souza.</li>
                                    <li><span>Ementa:</span> Discute as contribuições acadêmicas sobre avaliação e análise de políticas públicas, inclusive: avaliação de produtos, efeitos e impactos das diversas políticas como saúde, educação, agricultura, assistência social etc.</li>
                                </ul>
                                
                            </li>
                            
                            
                            <li>
                                
                                <a data-id="10">Estudos e Pesquisas em Espaço, Trabalho, Inovação e Sustentabilidade</a>
                                <ul class="third-level ul-10">
                                    <li><span>Coordenadores:</span> Denilson da Silva Araújo, William Eufrásio Nunes Pereira, Marconi Gomes da Silva, Maria Lussieu da Silva e Valdênia Apolinário.</li>
                                    <li><span>Ementa:</span> Discute as questões teóricas e políticas da economia regional e urbana, priorizando as questões do trabalho, da inovação e da sustentabilidade nesses espaços.</li>
                                </ul>
                                
                            </li>
                         
                            <li>
                                
                                <a data-id="11">Métodos Quantitativos Aplicados à Economia</a>
                                <ul class="third-level ul-11">
                                    <li><span>Coordenadores:</span> Igor Ezio Marcial Silva,  João Paulo Martins Guedes, Janaina da Silva Alves e Diego de Maria André.</li>
                                    <li><span>Ementa:</span> Discute trabalhos que mostrem contribuições metodológicas quantitativas aplicadas à Economia. Neste caso, pretende-se analisar temas como saúde, educação, criminalidade, crescimento econômico, integração de mercados etc, utilizando ferramentas estatísticas, matemáticas e econométricas que tem sido aplicadas na literatura nacional e internacional, a fim de trazer contribuições ao desenvolvimento regional. </li>
                                </ul>
                                
                            </li>
                        </ul>
                    </li>
                </ul> <!-- END.DEPARTAMENTO DE ECONOMIA -->
                
                <ul class="first-level">
                    <li>
                        CIÊNCIAS <span>ADMINISTRATIVAS</span>
                        <ul class="second-level">

                            <li>
                                
                                <a data-id="18">Administração Pública, Empreendedorismo e Desenvolvimento</a>
                                <ul class="third-level ul-18">
                                    <li><span>Coordenadores:</span> Aline Virginia, Medeiros Nelson e Dalvanir Avelino da Silva.</li>
                                    <li><span>Ementa:</span> O GT tem como objetivo constituir-se num espaço para apresentação de trabalhos que envolvam reflexões sobre as possibilidades e limitações das políticas que tem como foco o desenvolvimento humano em suas dimensões (econômica, social, política, ambiental e cultural), bem como a análise da institucionalização de práticas e ações inovadoras e empreendedoras no campo da administração pública, bem como na articulação entre Mercado e Estado.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="101">Condições de Trabalho e Saúde do Trabalhador</a>
                                <ul class="third-level ul-101">
                                    <li><span>Coordenadores:</span> Maria Teresa Pires Costa, Antônio Alves Filho. </li>
                                    <li><span>Ementa:</span> Condições de trabalho e sua relação com a saúde do trabalhador; qualidade de vida no trabalho. </li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="14">Desafios da Implementação da Administração Estratégica nas Organizações</a>
                                <ul class="third-level ul-14">
                                    <li><span>Coordenadores:</span> Vidal Sunción Infante.</li>
                                    <li><span>Ementa:</span></li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="12">Estratégias Políticas de Gestão</a>
                                <ul class="third-level ul-12">
                                    <li><span>Coordenadores:</span> Miguel Eduardo Moreno Añez e Maxwell dos Santos Celestino.</li>
                                    <li><span>Ementa:</span> Aprendizagem organizacional; Dinâmica de sistemas; Pensamento sistêm co; Modelagem de negócios; Simulação empresarial; Jogos de empresas; Aspectos Teóricos e Metodológicos da Vantagem Competitiva.</li>
                                </ul>
                                
                            </li>
                            
                            
                            <li>
                                
                                <a data-id="13">Finanças</a>
                                <ul class="third-level ul-13">
                                    <li><span>Coordenadores:</span> Vinício Almeida.</li>
                                    <li><span>Ementa:</span> Finanças empresariais; controle gerencial; mercado de capitais; governança corporativa.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="15">Gestão de Sistemas e Tecnologia da Informação</a>
                                <ul class="third-level ul-15">
                                    <li><span>Coordenadores:</span> Anatália Saraiva Martins Ramos.</li>
                                    <li><span>Ementa:</span> Governo Eletrônico e Tecnologia da Informação em Organizações Não Governamentais;Aspectos Socioambientais de SI/TI.</li>
                                </ul>
                                
                            </li> 
                            
                            <li>
                                
                                <a data-id="16">Gestão de Políticas Socias</a>
                                <ul class="third-level ul-16">
                                    <li><span>Coordenadores:</span> Dinah dos Santos Tinoco.</li>
                                    <li><span>Ementa:</span> Discutir as novas configurações das políticas sociais brasileiras: finalidades, arranjos institucionais, instrumentos e processos.</li>
                                </ul>
                                
                            </li>
                            
                             <li>
                                
                                <a data-id="17">Gestão de Pessoas e Comportamento Organizacional</a>
                                <ul class="third-level ul-17">
                                    <li><span>Coordenadores:</span> Patrícia Whebber Souza de Oliveira.</li>
                                    <li><span>Ementa:</span> Discutir as políticas e práticas de gestão de pessoas e os processos de comportamento organizacional no âmbito individual, grupal e organizacional e suas interfaces com o desenvolvimento das organizações.</li>
                                </ul>
                                
                            </li>
                            
                            
                            
                            <li>
                                
                                <a data-id="19">Gestão Social, Reforma Agrária e Desenvolvimento Territorial </a>
                                <ul class="third-level ul-19">
                                    <li><span>Coordenadores:</span> Washington Souza e Pâmela Brandão.</li>
                                    <li><span>Ementa:</span> Trata-se de grupo de trabalho destinado à discussão de processos e tendências do campo da Gestão Social, incluindo o universo das organizações do Terceiro Setor (não-governamentais, cooperativas, associações, grupos de trabalho e produção informais), além de temas específicos de estágios da reforma agrária e do desenvolvimento territorial no Rio Grande do Norte e no Brasil.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="22">Inovação na Gestão Pública</a>
                                <ul class="third-level ul-22">
                                    <li><span>Coordenadores:</span> Hironobu Sano e Lilian Sumiya.</li>
                                    <li><span>Ementa:</span> Discutir trabalhos que abordem as inovações no campo da gestão pública e das políticas públicas, contribuindo para a disseminação das boas práticas e, com isso, com o fortalecimento do setor público e a melhoria nos serviços ofertados à população. </li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="20">Marketing</a>
                                <ul class="third-level ul-20">
                                    <li><span>Coordenadores:</span> Thelma  Pignataro.</li>
                                    <li><span>Ementa:</span> Administração de Marketing. Marketing no Século XXI como ferramenta pedagógica no contexto educacional. Marketing em áreas especiais. Gerenciamento do processo de marketing, marketing virtual e inteligência competitiva.</li>
                                </ul>
                                
                            </li>
                            
                            <!--
                            <li>
                                
                                <a data-id="21">Administração Pública, Empreendedorismo e Desenvolvimento</a>
                                <ul class="third-level ul-21">
                                    <li><span>Coordenadores:</span> Aline Virginia, Medeiros Nelson e Dalvanir Avelino da Silva.</li>
                                    <li><span>Ementa:</span> O GT tem como objetivo constituir-se num espaço para apresentação de trabalhos que envolvam reflexões sobre as possibilidades e limitações das políticas que tem como foco o desenvolvimento, bem como a análise da institucionalização de práticas e ações inovadoras e empreendedoras no campo da administração pública. </li>
                                </ul>
                                
                            </li>
                            -->
                            
                            
                        </ul>
                    </li>
                </ul> <!-- END.DEPARTAMENTO DE CIÊNCIAS ADMINISTRATIVAS -->
                
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                
                <ul class="first-level">
                    <li>
                        CIÊNCIAS <span>DA INFORMAÇÃO</span>
                        <ul class="second-level">
                            
                            <li>
                                
                                <a data-id="23">Estudos Históricos e Epistemológicos da Biblioteconomia e da Ciência da Informação</a>
                                <ul class="third-level ul-23">
                                    <li><span>Coordenadores:</span> Jacqueline de Araújo Cunha, Antônia Neta e Francisco Galdino.</li>
                                    <li><span>Ementa:</span> Estudos Históricos e Epistemológicos da Ciência da Informação. Constituição do campo científico e questões epistemológicas e históricas da Ciência da informação e seu objeto de estudo - a informação. Reflexões e discussões sobre a disciplinaridade, interdisciplinaridade e transdisciplinaridade, assim como a construção do conhecimento na área. Estudos sobre perfil, formação, competências profissionais e mercado de trabalho. Estudos sobre os tipos de unidades de informação.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="24">Gestão da Informação e Conhecimento</a>
                                <ul class="third-level ul-24">
                                    <li><span>Coordenadores:</span> Andréa Carvalho, Eliane Silva, Francisco Galdino, Pedro Neto.</li>
                                    <li><span>Ementa:</span> Gestão de ambientes, sistemas, unidades, serviços, produtos de informação e recursos informacionais. Estudos de fluxos, processos, uso e usuários da informação como instrumentos de gestão. Gestão do conhecimento e aprendizagem organizacional no contexto da Ciência da Informação. Marketing da informação, monitoramento ambiental e inteligência competitiva. Estudos métricos da informação. Gestão documental, segurança da informação e preservação e conservação de documentos. Empreendedorismo informacional.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="25">Informação, Tecnologia e Mediação</a>
                                <ul class="third-level ul-25">
                                    <li><span>Coordenadores:</span>  Andréa Carvalho, Eliane Silva, Fernando Vechiato, Jacqueline de Araújo Cunha e Luciana Moreira Carvalho.</li>
                                    <li><span>Ementa:</span> Planejamento, implantação e avaliação de produtos e serviços de informação. Redes, recursos e fontes de informação. Estudo dos processos e das relações entre mediação, circulação e apropriação de informações, em diferentes contextos. Tecnologias de Informação e Comunicação. Redes sociais. Repositórios digitais. Curadoria de conteúdos. Arquitetura da informação.</li>
                                </ul>
                                
                            </li> 
                            
                            <li>
                                
                                <a data-id="26">Organização e Representação do Conhecimento</a>
                                <ul class="third-level ul-26">
                                    <li><span>Coordenadores:</span> Jacqueline Souza, Luciana Moreira Carvalho, Mônica Marques, Nádia Vanti, Rildeci Medeiros.</li>
                                    <li><span>Ementa:</span> Teorias, metodologias e práticas relacionadas à organização de documentos e da informação em ambiências informacionais, tais como: arquivos, museus, bibliotecas e congêneres. Compreende, também, os estudos relacionados aos processos, produtos e instrumentos de representação do conhecimento. Estudos sobre análise da informação.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="27">Política e Economia da Informação</a>
                                <ul class="third-level ul-27">
                                    <li><span>Coordenadores:</span> Pedro Neto, Andréa Carvalho e Eliane Silva. </li>
                                    <li><span>Ementa:</span> Políticas de informação e suas expressões em diferentes campos. Sociedade da informação. Informação, Estado e governo. Propriedade intelectual. Acesso à informação. Economia política da informação e da comunicação; produção colaborativa. Informação, conhecimento e inovação. Inclusão informacional e inclusão digital. Ética e informação. Informação e meio ambiente. </li>
                                </ul>
                                
                            </li>
                                    
                        </ul>
                    </li>
                </ul> <!-- END.DEPARTAMENTO DE CIÊNCIA DA INFORMAÇÃO -->
                
                <ul class="first-level">
                    <li>
                        SERVIÇO <span>SOCIAL</span>
                        <ul class="second-level">
                            
                            <li>
                                
                                <a data-id="28">Questão Social, Políticas Sociais e Serviço Social</a>
                                <ul class="third-level ul-28">
                                    <li><span>Coordenadores:</span> Iris Maria de Oliveira, Ilena Felipe Barros, Roberto Marinho Alves da Silva, Edla Hoffmann, Rosangela Alves de Oliveira, Eliana Andrade da Silva.</li>
                                    <li><span>Ementa:</span> Estudos e pesquisas sobre a Questão Social e suas expressões; Análise de políticas sociais – sua gênese e desenvolvimento e a inserção profissional do Serviço Social nos processos de formulação.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="29">Direitos Humanos, Diversidade Humana e Serviço Social</a>
                                <ul class="third-level ul-29">
                                    <li><span>Coordenadores:</span> Rita de Lourdes de Lima, Silvana Mara de Morais dos Santos, Miriam de Oliveira Inácio , Andrea Lima da Silva , Tassia Rejane Monte dos Santos , Ilka de Lima Souza, Maria Celia Correia Nicolau, Tibério Oliveira.</li>
                                    <li><span>Ementa:</span> Consiste num espaço de socialização de estudos e pesquisas na área de Serviço Social e áreas afins, e tem como principal objetivo contribuir com a reflexão crítica sobre os processos de violação dos direitos humanos na sociedade capitalista, considerando a diversidade humana em suas diferentes expressões: gênero; identidade de gênero; raça-etnia; orientação sexual.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="30">Questão Urbana Agrária, Ambiental, Movimentos Sociais e Serviço Social</a>
                                <ul class="third-level ul-30">
                                    <li><span>Coordenadores:</span> Silvana Mara de Morais Dos Santos, Rita de Lourdes De Lima, Miriam de Oliveira Inácio, Andrea Lima da Silva, Tassia Rejane Monte dos Santos, Ilka de Lima Souza, Maria Celia Correia Nicolau, Daniela Neves, Henrique Wellen.</li>
                                    <li><span>Ementa:</span> Consiste num espaço de socialização de estudos e pesquisas na área de Serviço Social e áreas afins e tem como principal objetivo a contribuição à reflexão crítica de pesquisas que estudam as seguintes temáticas: Propriedade privada da terra, trabalho e formas de produção e reprodução capitalista.</li>
                                </ul>
                                
                            </li>
                            
                        </ul>
                    </li>
                </ul> <!-- END.DEPARTAMENTO DE SERVIÇO SOCIAL -->
                
                <ul class="first-level">
                    <li>
                        DIREITO <span>PRIVADO E PÚBLICO</span>
                        <ul class="second-level">
                            
                            <li>
                                
                                <a data-id="31">Direito das Relações de Consumo</a>
                                <ul class="third-level ul-31">
                                    <li><span>Coordenadores:</span> Yanko Marcius de Alencar Xavier, Patrícia Borba Vilar Guimarães, Fabricio Gemano Alves, Anderson da Silva Lanzillo.</li>
                                    <li><span>Ementa:</span> O Código Civil brasileiro dedica todo um capítulo aos direitos da personalidade, categoria da qual o legislador se ocupa pela primeira vez.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="103">Direitos Fundamentais da Constituição Federal</a>
                                <ul class="third-level ul-103">
                                    <li><span>Coordenadores:</span> Leonardo Martins, Beatriz Ferreira de Almeida, Jair Soares de Oliveira Segundo, Jone Fagner Rafael Maciel, Suzana Cecília Cortez de Araújo e Silva</li>
                                    <li><span>Ementa:</span> Do ponto de vista jurídico-constitucional, os direitos fundamentais configuram, em seu conjunto, um sistema jurídico das liberdades individuais e de direitos prestacionais oponíveis em face de todos os órgãos estatais, funcionalmente pertinentes aos três poderes institucionalizados. Sua grande importância no sistema  político e na sociedade contemporânea é inegável. Isso é o que pode ser facilmente verificado, por exemplo, nas discussões e decisões do Supremo Tribunal Federal, na política legislativa e, no âmbito do Poder Executivo (Administração Pública), na discussão e formulação das chamadas políticas públicas. Trata-se de um sistema normativo, dotado de supremacia dentro do Estado de Direito e, por isso, que vincula e limita o poder estatal. Não obstante, seu desenvolvimento avançou para relações jurídicas mais complexas, tais como os deveres prestacionais do Estado, concretizadores do princípio do Estado social e a tutela de direitos difusos e coletivos. Destarte, alguns dos objetos a serem analisados pelo presente GT são: direitos e garantias individuais e coletivos, proteção multinível dos direitos fundamentais, eficácia das normas definidoras de direitos fundamentais nas relações entre particulares, constitucionalização do direito de família teoria geral dos direitos fundamentais e interpretação constitucional.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="32">Direito e Desenvolvimento</a>
                                <ul class="third-level ul-32">
                                    <li><span>Coordenadores:</span> Yanko Marcius de Alencar Xavier, Patrícia Borba Vilar Guimarães, Fabricio Gemano Alves, Anderson da Silva Lanzillo.</li>
                                    <li><span>Ementa:</span> Direito e Desenvolvimento. Constitucionalismo e estrutura econômica. Legislação como instrumento de políticas públicas. Planejamento econômico. Regulação estatal, serviços públicos e atividade econômica. Disciplina da atividade empresarial.</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                
                                <a data-id="33">Direito e Regulação dos Recursos Naturais e da Energia</a>
                                <ul class="third-level ul-33">
                                    <li><span>Coordenadores:</span> Yanko Marcius de Alencar Xavier, Patrícia Borba Vilar Guimarães, Fabricio Gemano Alves, Anderson da Silva Lanzillo.</li>
                                    <li><span>Ementa:</span> Direito dos Recursos Hídricos: princípios hídricos constitucionais, política nacional dos recursos hídricos, usuário-pagador, atribuições e competências, conflitos.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="34">Direito, Linguagem e Sociedade</a>
                                <ul class="third-level ul-34">
                                    <li><span>Coordenadores:</span> Anderson Souza da Silva Lanzillo</li>
                                    <li><span>Ementa:</span> O objetivo do presente grupo de trabalho é discutir as relações entre Direito e Linguagem em seus variados aspectos a partir de múltiplas perspectivas teóricas, tendo em vista o aspecto social dessa inter-relação. Modernamente cresce a percepção de que o exercício democrático do Direito não significa apenas ter acesso aos meios institucionais ligados ao sistema de justiça estatal, mas também compreender esses meios, o que implica no acesso, claridade e compreensão da linguagem jurídica. Interessa especialmente trabalhos que discutam: a) aspectos epistemológicos e teóricos gerais ligados ao Direito e à Linguagem (Teoria e Filosofia do Direito, e Ciências da Linguagem); b) argumentação e hermenêutica jurídica; c) a linguagem jurídica e seus gêneros (Linguística e Dogmática Jurídica); d) direito, linguagem e práticas sociais/ acesso à justiça.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="105">Direito Internacional e sua Efetivação na Ordem Jurídica Interna</a>
                                <ul class="third-level ul-105">
                                    <li><span>Coordenadores:</span> Jahyr-Philippe Bichara, Thiago Oliveira Moreira, Diogo Pignataro de Oliveira, Marconi Neves Macedo</li>
                                    <li><span>Ementa:</span> Direito Internacional. Direito Estatal. Soberania. Tratados Internacionais. Aplicação. Jurisdição. Efetividade. Tribunal Penal Internacional.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="71">Trabalho Infantil e a Proteção da Criança e do Adolescente</a>
                                <ul class="third-level ul-71">
                                    <li><span>Coordenadores:</span> Zeu Palmeira Sobrinho, Fabiana Mota</li>
                                    <li><span>Ementa:</span> O GT se propõe a servir de espaço de debate sobre as pesquisas desenvolvidas na atualidade sobre o trabalho infantil, em suas diversas modalidades, tais como: TIA – Trabalho Infantil Artístico; TIE – Trabalho Infantil Esportivo: TID – Trabalho Infantil Doméstico; TIC – Trabalho Infantil no Campo;? TIR – Trabalho Infantil nas Ruas; TIS – Trabalho Infantil Sexual; TIP – Trabalho Infantil Perigoso; TII – Trabalho Infantil Indígena, etc. A importância da temática se justifica diante dos reflexos da realidade do trabalho infantil sobre o desenvolvimento das crianças e adolescentes. Num contexto de globalização hegemônica, a relação de exploração da força de trabalho infanto-juvenil ?teve um aumento no Bfrasil, em 2014, e vem sendo associado a problemas como evasão escolar, viol& ecirc;ncia, bullying, ?doenças ocupacionais, precarização do trabalho e exclusão social. O trabalho infantil aqui é entendido como a atividade, onerosa ou não, reputada inadequada ou nociva ao desenvolvimento pleno da criança e do adolescente. Conforme o marco jurídico do Brasil, o trabalho infantil é a atividade realizada pelas crianças e ou pelos adolescentes que estão abaixo da idade legal mínima permitida para figurarem como sujeitos da relação de emprego.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="104">Jurisdição Constitucional e o Novo Código de Processo Civil</a>
                                <ul class="third-level ul-104">
                                    <li><span>Coordenadores:</span> Ana Beatriz Rebello Presgrave</li>
                                    <li><span>Ementa:</span> urisdição Constitucional. Ações Constitucionais. Vinculação das Decisões. O processo no novo Código de Processo Civil.</li>
                                </ul>
                                
                            </li>
    
                        </ul>
                    </li>
                    
                </ul> <!-- END.DEPARTAMENTO DE DIREITO PÚBLICO E PRIVADO -->
                
                <ul class="first-level">
                    <li>
                        TRANSDISCIPLINAR
                        <ul class="second-level">
                            
                            <li>
                                
                                <a data-id="43">Casos para Ensino</a>
                                <ul class="third-level ul-43">
                                    <li><span>Coordenadores:</span> Maria Váleria Pereira de Araújo e Pâmela de Medeiros Brandão.</li>
                                    <li><span>Ementa:</span> A apresentação do caso refere-se ao relato descritivo da situação e do problema a…podendo a critério do autor se subdividir. Uma boa apresentação do caso deve apresentar: uma introdução definindo o problema a ser investigado e…que requeiram análise para resolver uma questão específica.</li>
                                </ul>
                                
                            </li>

                            <li>
                                
                                <a data-id="44">Arte e Marxismo</a>
                                <ul class="third-level ul-44">
                                    <li><span>Coordenadores:</span> Henrique Wellen e Zeu Palmeira Sobrinho.</li>
                                    <li><span>Ementa:</span> Análises do complexo estético a partir da sua relação com a sociedade, tendo por fundamento o materialismo histórico. Apresentações e debates de ensaios e pesquisas sobre artistas, obras de arte e movimentos estéticos, assim como teorias, tradições e autores que, historicamente, fomentaram essas discussões. E, diante das determinações que consubstanciam a legalidade específica da figuração artística, estabelecer reflexões sobre o realismo crítico e a teoria do reflexo.</li>
                                </ul>
                                
                            </li>
                            
                        </ul>
                   
                    </li>
                    
                </ul> <!-- END. -->
                
            </div>					
        </div>
    
    </section>		

</div>

<section class="contact">

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                
                <h1><a name="contact"></a>CONTATO</h1>
                
                <?php echo form_open('noaction', array('id' => 'form-contact-message', 'ng-controller' => 'contactCtl' ,'novalidate' => '', 'name' => 'fSendMessage' , 'class' => 'form-horizontal', 'ng-submit' => 'sendMessage(fSendMessage.$valid,fSendMessage)')); ?>
                
                    <div class="form-group">
                       <label for="form-contact-name" class="col-sm-2 control-label">Nome</label>
                        <div class="col-sm-10">
                            
                            <input type="text" class="form-control" id="form-contact-name" autocomplete="off" ng-model="name" name="name" placeholder="Nome" required>
                            
                            <div  
                                ng-show="fSendMessage.$submitted && fSendMessage.name.$invalid || fSendMessage.name.$invalid && !fSendMessage.name.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fSendMessage.name.$error.required" style="color:yellow;font-weight:600;">O campo Nome é obrigatório.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                       <label for="form-contact-email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            
                            <input type="email" class="form-control" id="form-contact-email" autocomplete="off" ng-model="email" name="email" placeholder="Email" required>
                            
                            <div  
                                ng-show="fSendMessage.$submitted && fSendMessage.email.$invalid || fSendMessage.email.$invalid && !fSendMessage.email.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fSendMessage.email.$error.required" style="color:yellow;font-weight:600;">O campo Email é obrigatório.</p>
                                <p class="help-block" ng-show="fSendMessage.email.$error.email" style="color:yellow;font-weight:600;">É necessário que seja um Email válido.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                       <label for="form-contact-subject" class="col-sm-2 control-label">Assunto</label>
                        <div class="col-sm-10">
                            
                            <input type="text" class="form-control" id="form-contact-subject" autocomplete="off" ng-model="subject" name="subject" placeholder="Assunto" required>
                            
                            <div  
                                ng-show="fSendMessage.$submitted && fSendMessage.subject.$invalid || fSendMessage.subject.$invalid && !fSendMessage.subject.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fSendMessage.subject.$error.required" style="color:yellow;font-weight:600;">O campo Assunto é obrigatório.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                       <label for="form-contact-msg" class="col-sm-2 control-label">Mensagem</label>
                        <div class="col-sm-10">
                            
                            <textarea class="form-control" id="form-contact-msg" rows="10" autocomplete="off" ng-model="msg" name="msg" placeholder="Mensagem" required></textarea>
                            
                            <div  
                                ng-show="fSendMessage.$submitted && fSendMessage.msg.$invalid || fSendMessage.msg.$invalid && !fSendMessage.msg.$pristine" 
                            >
                                
                                <p class="help-block" ng-show="fSendMessage.msg.$error.required" style="color:yellow;font-weight:600;">O campo Mensagem é obrigatório.</p>
                                
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-info" >Enviar</button>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
                
    </div>
        
</section>

<div class="modal fade index-login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-sm">

        <div class="modal-content" ng-controller="loginCtl">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> {{ title }}</h4>
            </div>
            <div class="modal-body body-login">
                
                <div class="row">
                    <div class="col-lg-12">
                        
                        <div class="login" ng-show="mode.login">
                                
                            <?php echo form_open('noaction'); ?>
                            
                                <input type="email" class="form-control" name="email" placeholder="Email" ng-model="loginEmail" >
                                <input type="password" class="form-control" name="password" placeholder="Senha" ng-model="loginPassword" > 
                                
                                <button class="btn btn-info" ng-click="signin()" ng-disabled="isLoading" >
                                    
                                    <span ng-show="!isLoading">Entrar</span>
                                    <span ng-show="isLoading">Processando...</span>
                                    
                                </button>
                            
                            </form>

                            <hr>
                            
                            <a href="" ng-click="changeMode('retrievePassword')" >Esqueci a senha</a>
                            
                        </div>
                        
                        <div class="retrieve-password" ng-show="mode.retrievePassword">
                            
                            <?php echo form_open('noaction'); ?>
                            
                                <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="off" ng-model="rpEmail">
                                
                                <button class="btn btn-info" ng-click="retrievePassword()" ng-disabled="isLoading">
                                    
                                    <span ng-show="!isLoading">Recuperar Senha</span>
                                    <span ng-show="isLoading">Processando...</span>
                                    
                                </button>
                            
                            </form>
                            
                            <hr>
                            
                            <a href="" ng-click="changeMode('login')">Voltar para login</a>
                            
                        </div>
                        
                    </div>
                    
                </div>

            </div>

        </div>
        
    </div>
</div>
