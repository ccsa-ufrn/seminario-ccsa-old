<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Meus Certificados</h1>
    </div>

    <table class="table table-hover">
    	
    	<tr>
    		<th>Nome</th>
    		<th>Certificado</th>
    	</tr>

    	<!-- CERTIFICADO DE PARTICIPAÇÃO DO SEMINÁRIO -->
    	<?php $i=0; if($user->paid=="accepted" || $user->paid=="free"): ?>
    		<tr>
    			<td>Certificado de Participação</td>
    			<td><a class="btn-generate-certificate" style="cursor:pointer;" data-type="user" data-id="<?php echo $user->id; ?>" data-first="<?php if(isset($user->certgen)) echo 'no'; else echo 'yes'; ?>">Ver Certificado</a></td>
    			<?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-user-'.$user->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $user->id; ?>">
                    <input style="display:none;" type="text" name="type" value="user">
                </form>
    		</tr>
   		<?php $i++; endif; ?>

   		<!-- CERTIFICADOS DE MINICURSOS -->
   		<?php $cm = R::find('minicourse_user',' cernn="yes" AND user_id=? ',array($user->id)); ?>
   		<?php foreach ($cm as $c): ?>

   			<tr>
   				<td>Certificado (Minicurso) - <b><?php echo $c->minicourse->title; ?></b></td>
   				<td><a class="btn-generate-minicourse-certificate" style="cursor:pointer;" data-type="minicourse" data-id="<?php echo $c->id; ?>">Ver Certificado</a></td>
   				<?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-minicourse-'.$c->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $c->id; ?>">
                    <input style="display:none;" type="text" name="type" value="minicourse">
                </form>
   			</tr>

   		<?php $i++; endforeach; ?>

   		<!-- CERTIFICADOS DE MESA-REDONDA -->
   		<?php $cm = R::find('roundtable_user',' cernn="yes" AND user_id=? ',array($user->id)); ?>
   		<?php foreach ($cm as $c): ?>

   			<tr>
   				<td>Certificado (Mesa-redonda) - <b><?php echo $c->roundtable->title; ?></b></td>
   				<td><a class="btn-generate-roundtable-certificate" style="cursor:pointer;" data-type="roundtable" data-id="<?php echo $c->id; ?>">Ver Certificado</a></td>
   				<?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-roundtable-'.$c->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $c->id; ?>">
                    <input style="display:none;" type="text" name="type" value="roundtable">
                </form>
   			</tr>

   		<?php $i++; endforeach; ?>

   		<!-- CERTIFICADOS DE CONFERÊNCIA -->
   		<?php $cm = R::find('conference_user',' cernn="yes" AND user_id=? ',array($user->id)); ?>
   		<?php foreach ($cm as $c): ?>

   			<tr>
   				<td>Certificado (Conferência) - <b><?php echo $c->conference->title; ?></b></td>
   				<td><a class="btn-generate-conference-certificate" style="cursor:pointer;" data-type="conference" data-id="<?php echo $c->id; ?>">Ver Certificado</a></td>
   				<?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-conference-'.$c->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $c->id; ?>">
                    <input style="display:none;" type="text" name="type" value="conference">
                </form>
   			</tr>

   		<?php $i++; endforeach; ?>

   		<!-- CERTIFICADOS DE OFICINAS -->
   		<?php $cm = R::find('user_workshop',' cernn="yes" AND user_id=? ',array($user->id)); ?>
   		<?php foreach ($cm as $c): ?>

   			<tr>
   				<td>Certificado (Oficina) - <b><?php echo $c->workshop->title; ?></b></td>
   				<td><a class="btn-generate-workshop-certificate" style="cursor:pointer;" data-type="workshop" data-id="<?php echo $c->id; ?>">Ver Certificado</a></td>
   				<?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-workshop-'.$c->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $c->id; ?>">
                    <input style="display:none;" type="text" name="type" value="workshop">
                </form>
   			</tr>

   		<?php $i++; endforeach; ?>

   		<!-- CERTIFICADOS DE ARTIGOS -->
   		<?php $cm = R::find('paper',' cernn="yes" AND user_id=? AND evaluation="accepted" ',array($user->id)); ?>
   		<?php foreach ($cm as $c): ?>

   			<tr>
   				<td>Certificado (Artigo) - <b><?php echo $c->title; ?></b></td>
   				<td><a class="btn-generate-paper-certificate" style="cursor:pointer;" data-type="paper" data-id="<?php echo $c->id; ?>">Ver Certificado</a></td>
   				<?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-paper-'.$c->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $c->id; ?>">
                    <input style="display:none;" type="text" name="type" value="paper">
                </form>
   			</tr>

   		<?php $i++; endforeach; ?>

   		<!-- CERTIFICADOS DE PÔSTERES -->
   		<?php $cm = R::find('poster',' cernn="yes" AND user_id=? ',array($user->id)); ?>
   		<?php foreach ($cm as $c): ?>

   			<tr>
   				<td>Certificado (Pôster) - <b><?php echo $c->title; ?></b></td>
   				<td><a class="btn-generate-poster-certificate" style="cursor:pointer;" data-type="poster" data-id="<?php echo $c->id; ?>">Ver Certificado</a></td>
   				<?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-poster-'.$c->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $c->id; ?>">
                    <input style="display:none;" type="text" name="type" value="poster">
                </form>
   			</tr>

   		<?php $i++; endforeach; ?>

      <?php $cm = R::find('paper',' cernn="yes" AND user_id=? AND evaluation="asPoster" AND asposter="accepted" ',array($user->id)); ?>
      <?php foreach ($cm as $c): ?>

        <tr>
          <td>Certificado (Pôster) - <b><?php echo $c->title; ?></b></td>
          <td><a class="btn-generate-pposter-certificate" style="cursor:pointer;" data-type="pposter" data-id="<?php echo $c->id; ?>">Ver Certificado</a></td>
          <?php echo form_open(base_url('dashboard/certificate/generate'), array('target' => '_blank','id' => 'formGenerate-pposter-'.$c->id,'novalidate' => '','class' => 'waiting')); ?>
                    <input style="display:none;" type="text" name="id" value="<?php echo $c->id; ?>">
                    <input style="display:none;" type="text" name="type" value="pposter">
                </form>
        </tr>

      <?php $i++; endforeach; ?>

   		<?php if($i==0): ?>
   			<tr>
   				<td colspan="2">Nenhum certificado</td>
   			</tr>
   		<?php endif; ?>

    </table>

</div>