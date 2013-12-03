<!-- tampilan modul soal mulai dari sini -->
<div class="content">
  <h2>Hasil Try Out Online</h2>
</div>

<div class="content bg-white">
  <div class="row-fluid">
    <div class="span6">

      <?php foreach ($result as $key => $value): ?>

      <h4><?php echo $key; ?></h4>
      <table class="table table-bordered">
      <tr>
        <td>Benar : <?php echo $value['benar'];?> soal</td>
      </tr>
      <tr>
        <td>Salah : <?php echo $value['salah'];?> soal</td>
      </tr>
      <tr>
        <td>Kosong : <?php echo $value['kosong'];?> soal</td>
      </tr>
      <tr>
        <td>Nilai : <?php echo $value['nilai']; ?></td>
      </tr>
      </table>

      <?php endforeach; ?>

      <h4>Nilai Keseluruhan</h4>
      <table class="table table-bordered">
      <tr>
        <td>Benar : <?php echo $total_benar;?> soal</td>
      </tr>
      <tr>
        <td>Salah : <?php echo $total_salah;?> soal</td>
      </tr>
      <tr>
        <td>Kosong : <?php echo $total_kosong;?> soal</td>
      </tr>
      <tr>
        <td>Nilai Total : <?php echo $total; ?></td>
      </tr>
      </table>

    </div>

    <div class="span6">
      
    </div>
  </div>
</div>
          <!-- tampilan modul soal berakhir disini -->