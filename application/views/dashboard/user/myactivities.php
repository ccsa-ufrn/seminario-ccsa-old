            
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Minhas Atividades</h1>
                    
                    <?php 

                        echo "<p style='text-transform:uppercase;'><b>Nome:</b> ".$user->name."</p>";
                        echo "<p><b style='text-transform:uppercase;'>Email:</b> ".$user->email."</p>";
                        echo "<p style='text-transform:uppercase;'><b>Telefone:</b> ".$user->phone."</p>";
                        echo "<p style='text-transform:uppercase;'><b>Instituição:</b> ".$user->institution."</p>";
                        echo "<p style='text-transform:uppercase;'><b>Pagamento:</b> ";
                            if($user->paid=='accepted')
                                echo "Realizado";
                            else if($user->paid=='pendent')
                                echo "Esperando avaliação do pagamento";
                            else if($user->paid=='rejected')
                                echo "Comprovante de pagamento rejeitado, esperando novo envio de comprovante";
                            else if($user->paid=='no')
                                echo "Ainda não enviou nenhum comprovante";
                            else if($user->paid=='free')
                                echo "Isento";
                        echo "</p>";

                        echo "<h3>ARTIGOS SUBMETIDOS</h3>";

                        if(!count($user->ownPaperList))
                            echo "Nenhum artigo submetido.";
                        else{

                            echo "<table class='table'>"; 

                            foreach ($user->ownPaperList as $p) {
                                echo "<tr>";
                                echo "<td>".$p->title."</td>";
                                echo "<td>";
                                    if($p->evaluation=='accepted')
                                        echo "Aceito";
                                    else if($p->evaluation=='asPoster')
                                        if($p->asposter=='rejected')
                                            echo "Rejeitado como pôster";
                                        if($p->asposter=='accepted')  
                                            echo "Aceito como pôster"; 
                                    else if($p->evaluation=='rejected')
                                        echo "Rejeitado";
                                echo "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";

                        }

                        echo "<h3>PÔSTERES SUBMETIDOS</h3>";

                        if(!count($user->ownPosterList))
                            echo "Nenhum pôster submetido.";
                        else{

                            echo "<table class='table'>"; 

                            foreach ($user->ownPosterList as $p) {
                                echo "<tr>";
                                echo "<td>".$p->title."</td>";
                                echo "<td>";
                                    if($p->evaluation=='accepted')
                                        echo "Aceito";
                                    else if($p->evaluation=='rejected')
                                        echo "Rejeitado";
                                echo "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                            
                        }

                        echo "<h3>MESAS-REDONDAS SUBMETIDAS</h3>";

                        if(!count($user->ownRountableList))
                            echo "Nenhuma mesa-redonda submetida.";
                        else{

                            echo "<table class='table'>"; 

                            foreach ($user->ownRountableList as $p) {
                                echo "<tr>";
                                echo "<td>".$p->title."</td>";
                                echo "<td>";
                                    if($p->consolidated=='yes')
                                        echo "Aceita";
                                    else if($p->evaluation=='no')
                                        echo "Rejeitada";
                                echo "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                            
                        }

                        echo "<h3>INSCRIÇÕES EM MINICURSOS</h3>";

                        if(!count($user->sharedMinicourseList))
                            echo "Não se inscreveu em minicursos.";
                        else{
                            
                            echo "<table class='table'>"; 

                            foreach ($user->sharedMinicourseList as $p) {
                                echo "<tr>";
                                echo "<td>".$p->title."</td>";
                                echo "</tr>";
                            }

                            echo "</table>";

                        }

                        echo "<h3>INSCRIÇÕES EM CONFERÊNCIAS</h3>";

                        if(!count($user->sharedConferenceList))
                            echo "Não se inscreveu em conferências.";
                        else{

                            echo "<table class='table'>"; 

                            foreach ($user->sharedConferenceList as $p) {
                                echo "<tr>";
                                echo "<td>".$p->title."</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                            
                        }

                        echo "<h3>INSCRIÇÕES EM MESAS-REDONDAS</h3>";

                        if(!count($user->sharedRoundtableList))
                            echo "Não se inscreveu em mesas-redondas.";
                        else{
                            
                            echo "<table class='table'>"; 

                            foreach ($user->sharedRoundtableList as $p) {
                                echo "<tr>";
                                echo "<td>".$p->title."</td>";
                                echo "</tr>";
                            }

                            echo "</table>";

                        }

                        echo "<h3>INSCRIÇÕES EM OFICINAS</h3>";

                        if(!count($user->sharedWorkshopList))
                            echo "Não se inscreveu em oficinas.";
                        else{

                            echo "<table class='table'>"; 

                            foreach ($user->sharedWorkshopList as $p) {
                                echo "<tr>";
                                echo "<td>".$p->title."</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                            
                        }

                    ?>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
