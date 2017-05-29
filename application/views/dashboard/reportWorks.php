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

          <?php
            $horarios = $m->sharedMinicoursedayshiftList;

            $diashoras = Array();

            foreach($horarios as $h) {
              array_push($diashoras,  (new DateTime($h->date))->format('d/m/Y'). ' / ' . $h->shift);
            }
          ?>

          <tr>
            <td>Minicurso</td>
            <td><?php echo $m->title; ?></td>
            <td><?php echo $m->consolidatedlocal; ?></td>
            <td>
              <?php foreach($diashoras as $d) : ?>
                <span class="badge"><?php echo $d; ?></span>
              <?php endforeach; ?>
            </td>
            <td><?php echo $m->user->name; ?></td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($workshops as $m): ?>

          <?php
            $horarios = $m->sharedWorkshopdayshiftList;

            $diashoras = Array();

            foreach($horarios as $h) {
              array_push($diashoras,  (new DateTime($h->date))->format('d/m/Y'). ' / ' . $h->shift);
            }
          ?>

          <tr>
            <td>Oficinas</td>
            <td><?php echo $m->title; ?></td>
            <td><?php echo $m->local; ?></td>
            <td>
              <?php foreach($diashoras as $d) : ?>
                <span class="badge"><?php echo $d; ?></span>
              <?php endforeach; ?>
            </td>
            <td><?php echo $m->user->name; ?></td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($roundtables as $m): ?>
          <tr>
            <td>Mesa-Redonda</td>
            <td><?php echo $m->title; ?></td>
            <td><?php echo $m->consolidatedlocal; ?></td>
            <td>
              <span class="badge"><?php echo (new DateTime($m->roundtabledayshift->date))->format('d/m/Y'). ' / ' . $m->roundtabledayshift->shift; ?></span>
            </td>
            <td><?php echo $m->user->name; ?></td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($conferences as $m): ?>
          <tr>
            <td>Conferência</td>
            <td><?php echo $m->title; ?></td>
            <td><?php echo $m->local; ?></td>
            <td>
              <span class="badge"><?php echo (new DateTime($m->conferencedayshift->date))->format('d/m/Y'). ' / ' . $m->conferencedayshift->shift; ?></span>
            </td>
            <td><?php echo $m->user->name; ?></td>
          </tr>
        <?php endforeach ?>

      </tbody>

    </table>

  </div>
</div>
