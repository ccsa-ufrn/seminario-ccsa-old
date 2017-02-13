        <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <li>
                            <a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard fa-fw"></i> Inicio</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-eye fa-fw"></i> Avaliar<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo base_url('dashboard/paper/evaluate'); ?>">Artigos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('dashboard/poster/evaluate'); ?>">Pôsteres</a>
                                </li>
                                
                                <li>
                                    <a href="<?php echo base_url('dashboard/teachingcases/evaluate'); ?>">Casos para Ensino</a>
                                </li>
                            
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                        <?php 
                            $user = R::findOne('user', 'id = ?', array($this->session->userdata('user_id'))); 
                            if(count($user->ownMinicourseList) || count($user->ownRoundtableList) || count($user->ownWorkshopList)) :  
                        ?>
                        
                            <li>
                                <a href="<?php echo base_url('dashboard/minicourse/my-minicourses'); ?>"><i class="fa fa-inbox" aria-hidden="true"></i> Meus Trabalhos</a>
                            </li>
                            
                        <?php endif; ?>
                        
                        <li>
                            <a href="<?php echo base_url('dashboard/paper/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Artigos</a>
                        </li>

                        <li>
                            <a href="<?php echo base_url('dashboard/poster/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Pôsteres</a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url('dashboard/teachingcases/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Casos para Ensino</a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url('dashboard/minicourse/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Minicurso</a>
                        </li>

                        <li>
                            <a href="<?php echo base_url('dashboard/workshop/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Oficinas</a>
                        </li>

                        <li>
                            <a href="<?php echo base_url('dashboard/roundtable/submit'); ?>"><i class="fa fa-upload fa-fw"></i> Mesa-Redonda</a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url('dashboard/issue/create'); ?>"><i class="fa fa-bug fa-fw"></i> Suporte</a>
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