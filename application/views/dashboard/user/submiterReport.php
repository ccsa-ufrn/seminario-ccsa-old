<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Relatório de Proponentes por Trabalho</h1>

    <table id="tableThematicGroups" class="datatable table table-striped table-bordered ">

      <thead>
        <tr>
          <th>Título</th>
          <th>Tipo - Trabalho</th>
          <th>Proponente - Nome</th>
          <th>Proponente - Email</th>
          <th>Proponente - Telefone</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($minicourses as $m): ?>
          <tr>
            <td><?php echo $m->title; ?></td>
            <td>Minicurso</td>
            <td><?php echo $m->user->name; ?></td>
            <td><?php echo $m->user->email; ?></td>
            <td><?php echo $m->user->phone; ?></td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($workshops as $m): ?>
          <tr>
            <td><?php echo $m->title; ?></td>
            <td>Oficina</td>
            <td><?php echo $m->user->name; ?></td>
            <td><?php echo $m->user->email; ?></td>
            <td><?php echo $m->user->phone; ?></td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($roundtables as $m): ?>
          <tr>
            <td><?php echo $m->title; ?></td>
            <td>Mesa Redonda</td>
            <td><?php echo $m->user->name; ?></td>
            <td><?php echo $m->user->email; ?></td>
            <td><?php echo $m->user->phone; ?></td>
          </tr>
        <?php endforeach ?>

      </tbody>

    </table>

  </div>
</div>
