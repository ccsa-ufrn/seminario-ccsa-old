                        <thead>
                            <tr>
                                <th>Consolidar?</th>
                                <th>Título </th>
                                <th>Turno</th>
                                <th>Detalhes</th>
                            </tr>
                        </thead>
                        <?php foreach ($mcs as $mc): ?>
                            <tr id="tr-minicourse-<?php echo $mc->id; ?>">
                        
                                <!-- AJEITAR ESTA QUERY -->

                                <?php 

                                    $rows = R::getAll("SELECT mf.*
                                                        FROM minicoursedayshift AS mds 
                                                        JOIN minicoursefinal AS mf 
                                                        WHERE mf.id = mds.minicoursefinal_id 
                                                        AND mf.minicourse_id = ?",
                                                        array($mc['id'])
                                                    ); 
                                    $msd = R::convertToBeans( 'minicoursefinal', $rows );
                                    
                                ?>

                                <td class="check text-center">
                                    <input type="checkbox" <?php if(count($msd)): ?> checked="true" <?php endif; ?> name="check" value="<?php echo $mc->id; ?>" />
                                </td>
                                <td class="title"><?php echo $mc->title; ?></td>
                                <td class="shift"><?php echo $mc->shift; ?></td>
                                <td class="view">
                                    <button id="openMinicourseDetails" data-target="#modalMinicourseDetails" style="display:none;"></button>
                                    <button type="button" class="mOpenDetails btn btn-block btn-success" data-toggle="modal" data-data="<?php echo $mc->id; ?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>