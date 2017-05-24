<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Relatório de Proponentes por Trabalho</h1>

    <table id="tableThematicGroups" class="datatable table table-striped table-bordered ">

      <thead>
        <tr>
          <th>Trabalho</th>
          <th>Título</th>
          <th>Local</th>
          <th>Data/Hora</th>
          <th>Autor</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($minicourses as $m): ?>
          <tr>
            <td>Minicurso</td>
            <td><?php echo $m->title; ?></td>
            <td><?php echo $m->user->name; ?></td>
            <td><?php echo $m->user->email; ?></td>
            <td><?php echo $m->user->phone; ?></td>
          </tr>
        <?php endforeach ?>

       <!-- <?php foreach ($workshops as $m): ?>
          <tr>
            <td><?php echo $m->title; ?></td>
            <td>Oficina</td>
            <td><?php echo $m->user->name; ?></td>
            <td><?php echo $m->user->email; ?></td>
            <td><?php echo $m->user->phone; ?></td>
          </tr>
        <?php endforeach ?>

        <?php<th>Trabalho</th> foreach ($roundtables as $m): ?>
          <tr>
            <td><?php echo $m->title; ?></td>
            <td>Mesa Redonda</td>
            <td><?php echo $m->user->name; ?></td>
            <td><?php echo $m->user->email; ?></td>
            <td><?php echo $m->user->phone; ?></td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($conferences as $m): ?>
          <tr>
            <td><?php echo $m->title; ?></td>
            <td>Conferência</td>
            <td><?php echo $m->user->name; ?></td>
            <td><?php echo $m->user->email; ?></td>
            <td><?php echo $m->user->phone; ?></td>
          </tr>
        <?php endforeach ?>-->

      </tbody>

    </table>

  </div>
</div>
