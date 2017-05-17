        <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <li>
                            <a class="active" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard fa-fw"></i> Inicio</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-search fa-fw"></i> Gerenciar<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo base_url('dashboard/area'); ?>" >Áreas</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/thematicgroup'); ?>" >Grupos temáticos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/coordinator'); ?>" >Coordenadores/Avaliadores</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/administrator'); ?>" >Administradores</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/news'); ?>" >Notícias</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/minicourse/manage'); ?>">Minicursos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/roundtable/manage'); ?>">Mesas redondas</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/conference'); ?>">Conferências</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/workshop/manage'); ?>">Oficinas</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/subevent/manage'); ?>" >Subeventos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/user/manage'); ?>">Inscritos</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-book fa-fw"></i> Certificados e Anais<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/paper'); ?>" >Artigos</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/teachingcase'); ?>" >Casos para Ensino</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/poster'); ?>" >Pôsteres</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/minicourse'); ?>">Minicursos</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/roundtable'); ?>">Mesas-redondas</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/conference'); ?>">Conferências</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/workshop'); ?>">Oficinas</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/certificate/custom'); ?>">Certificado Customizado</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-second-level -->
                                </li>
                                <li>
		                            <a href="#"><i class="fa fa-tasks fa-fw"></i> Programação<span class="fa arrow"></span></a>
		                            <ul class="nav nav-second-level">
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/schedule/paper'); ?>" >Artigos</a>
		                                </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/schedule/poster'); ?>" >Pôsteres</a>
		                                </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/schedule/teachingcase'); ?>">Casos para Ensino</a>
                                        </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/schedule/minicourse'); ?>">Minicursos</a>
		                                </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/schedule/roundtable'); ?>">Mesas-redondas</a>
		                                </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/schedule/conference'); ?>">Conferências</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/schedule/workshop'); ?>">Oficinas</a>
                                        </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/schedule/othersactivities'); ?>">Outras Atividades</a>
		                                </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/schedule/generate'); ?>" target="_blank">Gerar Programação</a>
		                                </li>
		                            </ul>
		                            <!-- /.nav-second-level -->
		                        </li>

                                <li> <!-- REALEASE 1.2.0 -->
                                    <a href="#"><i class="fa fa-tasks fa-fw"></i> Controle Avançado<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/advanced-control/paper'); ?>" >Artigos</a>
		                                </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/advanced-control/poster'); ?>" >Pôsteres</a>
		                                </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/advanced-control/teachingcase'); ?>">Casos para Ensino</a>
                                        </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/advanced-control/minicourse'); ?>">Minicursos</a>
		                                </li>
		                                <li>
		                                    <a href="<?php echo base_url('dashboard/advanced-control/roundtable'); ?>">Mesas-redondas</a>
		                                </li>
                                        <li>
                                            <a href="<?php echo base_url('dashboard/advanced-control/workshop'); ?>">Oficinas</a>
                                        </li>
		                            </ul>
                                </li>

                            </ul>
                            <!-- /.nav-second-level -->

                        </li>

                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Relatório<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo base_url('dashboard/script/general-report'); ?>" target="_blank">Relatório Geral de GT's</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/script/reportpapers'); ?>" target="_blank">Artigos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/paper/get-paper'); ?>" >Baixar Artigos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/script/reportposters'); ?>" target="_blank">Pôsteres</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/poster/get-poster'); ?>" >Baixar Pôsteres</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/script/report-teaching-cases'); ?>" target="_blank">Casos para Ensino</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/teachingcases/get-teaching-case'); ?>" >Baixar Casos para Ensino</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/minicourse/report'); ?>">Minicursos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/roundtable/report'); ?>">Mesas-Redondas</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/workshop/report'); ?>">Oficinas</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/conference/report'); ?>">Conferências</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/user/submiter-report'); ?>">Relatório de Proponentes</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/user/reportinscription'); ?>">Relatório de Inscrito</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/user/report'); ?>" >Inscritos</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <li>
                            <a href="<?php echo base_url('dashboard/configuration'); ?>"><i class="fa fa-cog fa-fw"></i> Configurações do sistema</a>
                        </li>

                        <!--<li>
                            <a href="<?php echo base_url('dashboard/message/send'); ?>"><i class="fa fa-envelope-o fa-fw"></i> Enviar Mensagens</a>
                        </li>-->

                        <li>
                            <a href="<?php echo base_url('dashboard/message'); ?>"><i class="fa fa-envelope-o fa-fw"></i> Mensagens</a>
                        </li>

                        <li>
                            <a href="<?php echo base_url('dashboard/issue/manage'); ?>"><i class="fa fa-bug fa-fw"></i> Suporte</a>
                        </li>

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>

            <!-- /.navbar-static-side -->
        </nav>

        <!- ===================================
            PAGE WRAPPER
        ====================================== -->

        <div id="page-wrapper">
