<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Controle Avançado - Artigos</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-4">
  </div>
  <div class="col-lg-4">
    <button type="button" class="btn btn-block btn-success"
      data-toggle="modal" data-target="#modal-add-paper">
      ADICIONAR ARTIGO
    </button>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <table id="tablePaper" class="datatable table table-striped table-bordered ">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Autores</th>
          <th>Arquivo</th>
          <th>Situação</th>
          <th>Submissão</th>
          <th>Temática</th>
          <th>Ações</th>
        </tr>
      </thead>
      <?php foreach ($papers as $p): ?>
        <tr id="tr-paper-<?php echo $p->id;
?>">
          <td class="title"><?php echo $p->title;
?></td>
          <td class="authors"><?php echo $p->authors;
?></td>
          <td class="paper"><a href="<?php echo $p->paper;
?>">Baixar</a></td>
          <td class="paper">Pendente de Avaliação</td>
          <td class="sender"><?php echo $p->user->name;
?></td>
          <td class="tg"><?php echo $p->thematicgroup->name;
?></td>
          <td class="editar">
            <button type="button" class="btn btn-block btn-success"
              data-toggle="modal" data-target="#modalEditPaper"
              data-data="<?php echo $p->id;
?>">
              EDITAR
            </button>
          </td>
        </tr>
      <?php endforeach ?>
    </table>
  </div>
</div>
<!-- MODAL - ADD PAPER -->
<div class="modal fade" id="modal-add-paper" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">

        <div class="form-group">
          <label for="file">Título*</label>
          <input type="text" class="form-control" placeholder="Nome *" name="name" />
        </div>

        <div class="form-group">
          <label for="file">Grupo Temático *</label>
          <input type="text" class="form-control" placeholder="Instituição *" name="institution"/>
        </div>
        <button class="btn btn-default" >Adicionar Autor</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL - ADD AUTHOR -->
<div class="modal fade modal-paper-add-author" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <div class="form-group">
          <label for="file">Nome Completo*</label>
          <input type="text" class="form-control" placeholder="Nome *" name="name" />
        </div>
        <div class="form-group">
          <label for="file">Instituição *</label>
          <input type="text" class="form-control" placeholder="Instituição *" name="institution"/>
        </div>
        <button class="btn btn-default" >Adicionar Autor</button>
      </div>
    </div>
  </div>
</div>
