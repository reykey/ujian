<div class="content">
  <h2>Try Out Online</h2>
</div>

<div class="content bg-white">
  <div class="row-fluid">
    <div class="span12">
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore, dolore repellat animi culpa dicta ut veritatis accusantium nulla corrupti mollitia aut optio ratione incidunt voluptate reiciendis voluptas maiores aspernatur suscipit.</p>

      <!-- ini mulai soal ditampilkan -->
      <?php if (!empty($paket)): ?>

      <?php //dump($paket_user);?>
      <table class="table">
        <thead>
          <tr>
            <th>Sessi</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Berakhir</th>
            <th>Nilai</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
        <?php $i=1; foreach( $paket['entries'] as $item): ?>
        <tr>
          <?php
          $tglSekarang = date('Y-m-d H:i:s');
          $dtglBuka = date('Y-m-d H:i:s', $item['paket_id']['tanggal_buka']);
          $tglTutup = date('Y-m-d H:i:s',$item['paket_id']['tanggal_tutup']);
          ?>

          <td><strong><?php echo $item['paket_id']['judul']; ?></strong></td>
          <td><?php echo date("d F Y, H:i", $item['paket_id']['tanggal_buka']); ?></td>
          <td><?php echo date("d F Y, H:i", $item['paket_id']['tanggal_tutup']); ?></td>
          <td><?php echo $item['nilai']; ?></td>

          <?php if($tglSekarang >= $dtglBuka && $tglSekarang <= $tglTutup): ?>
            <?php if($item['status_pengerjaan']['key'] == 'belum'): ?>
              <td style="text-align:right">
                <?php echo anchor('tryout/prepare/'.$item['id'], "Kerjakan", array('class'=>'btn green btn-success')); ?>
              </td>
            <?php else: ?>
              <td></td>
            <?php endif; ?>
          <?php elseif ($tglSekarang <= $dtglBuka): ?>
            <td style="text-align:right"><label class="label">Belum Dibuka</label></td>
          <?php elseif ($tglSekarang >= $tglTutup): ?>
            <td style="text-align:right"><label class="label">Expired</label></td>
          <?php endif; ?>
        </tr>
        <?php $i++; endforeach; ?> 
        </tbody>
      </table>

      <?php else: ?>
        <div class="no_data"><?php echo lang('ujian:no_items'); ?></div>
      <?php endif;?>

    </div>
  </div>
</div>          