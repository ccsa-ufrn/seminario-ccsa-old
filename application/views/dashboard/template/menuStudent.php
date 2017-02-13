        <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <li>
                            <a <?php if($active=='home'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard fa-fw"></i> Inicio</a>
                        </li>

                        <li>
                            <a <?php if($active=='certificate'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/certificate/getcertificate'); ?>"><i class="fa fa-certificate fa-fw"></i> Certificado</a>
                        </li>

                        <!-- <li>
                            <a <?php if($active=='myactivities'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/user/myactivities'); ?>"><i class="fa fa-list-alt fa-fw"></i> Resumo das minhas atividades</a>
                        </li> -->

                        <li>
                            <a <?php if($active=='payment'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/user/payment'); ?>"><i class="fa fa-money fa-fw"></i> Pagamento</a>
                        </li>
                        
                        <?php 
                            $user = R::findOne('user', 'id = ?', array($this->session->userdata('user_id'))); 
                            if(count($user->ownMinicourseList) || count($user->ownRoundtableList) || count($user->ownWorkshopList)) :  
                        ?>
                        
                            <li>
                                <a <?php if($active=='my-minicourses') : ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/minicourse/my-minicourses'); ?>"><i class="fa fa-inbox" aria-hidden="true"></i> Meus Trabalhos</a>
                            </li>
                            
                        <?php endif; ?>
                        
                        <li>
                            <a <?php if($active=='minicourseenroll'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/minicourse/enroll'); ?>"><i class="fa fa-pencil"></i> Inscrever-se em Minicursos</a>
                        </li>
                        
                        <li>
                            <a <?php if($active=='roundtableenroll'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/roundtable/enroll'); ?>"><i class="fa fa-pencil"></i> Inscrever-se em Mesas-Redondas</a>
                        </li>
                        
                        <li>
                            <a <?php if($active=='conferenceenroll'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/conference/enroll'); ?>"><i class="fa fa-pencil"></i> Inscrever-se em Conferências</a>
                        </li>

                        <li>
                            <a <?php if($active=='workshopenroll'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/workshop/enroll'); ?>"><i class="fa fa-pencil"></i> Inscrever-se em Oficinas</a>
                        </li>

                        <li>
                            <a <?php if($active=='submit-paper'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/paper/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Artigos</a>
                        </li>

                        <li>
                            <a <?php if($active=='submit-poster'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/poster/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Pôsteres</a>
                        </li>
                        
                        <li>
                            <a <?php if($active=='submit-teaching-cases'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/teachingcases/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Casos para Ensino</a>
                        </li>

                        <li>
                            <a <?php if($active=='submit-minicourse'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/minicourse/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Minicursos</a>
                        </li>

                        <li>
                            <a <?php if($active=='submit-workshop'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/workshop/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Oficinas</a>
                        </li>
                        
                        <li>
                            <a <?php if($active=='issue'): ?> class="active" <?php endif; ?> href="<?php echo base_url('dashboard/issue/create'); ?>"><i class="fa fa-bug fa-fw"></i> Suporte</a>
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