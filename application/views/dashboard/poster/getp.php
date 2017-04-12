<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Baixar Pôster</h1>
    <table class="datatable table table-striped table-bordered ">
      <thead>
        <tr>
          <th style="width: 65%;">Nome</th>
          <th>Enviou Arte?</th>
          <th style="width: 100px;">Baixar Arte</th>
          <th style="width: 100px;">Baixar Resumo</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posters as $p): ?>
          <tr>
            <td><?php echo titleCase($p->title);?></td>
            <td><?php if($p->art) echo 'SIM'; else echo 'NÃO'; ?></td>
            <td class="text-center">
              <?php if($p->art): ?>
                <a href="<?php echo asset_url().'upload/posters/art/'.$p->art;?>"
                  target="_blank">Baixar Arte</a>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
            <td class="text-center">
              <?php if($p->poster): ?>
                <a href="<?php echo asset_url().'upload/posters/'.$p->poster;?>"
                  target="_blank">Baixar Resumo</a>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
