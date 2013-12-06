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
      <!-- <tr>
        <td>Nilai Total : <?php echo $total; ?></td>
      </tr> -->
      </table>

    </div>

    <div class="span6">
      <fieldset>
        <legend><?php echo $paket->judul_paket; ?></legend>
        <dt>Peserta</dt><dd><?php echo $this->current_user->display_name; ?></dd>
        <br>
        <dt>Status Ujian</dt><dd><?php echo ($status_ujian == 'mati')? "<span style='color:red'>Nilai Mati</span>" : "<span style='color:green'>Lulus</span>"; ?></dd>
      </fieldset>
      <br>
      <div id="totalnilai">
        <dt>Total Nilai</dt><span class="label label-success"><span id="large"><?php echo $total; ?></span>/ <?php echo 180*4; ?></span>
      </div>
    </div>
  </div>
</div>

<style>
  #totalnilai {
    text-align: center;
  }
  #totalnilai span span#large {
    font-size: 150px;
    text-shadow: 3px 6px 5px rgba(0, 0, 0, 0.25);
    -webkit-box-shadow:none;
    -moz-box-shadow:none;
    box-shadow:none;
    background-color: transparent;
  }
  #totalnilai span {
    background-color: #05C1EB;
    border-radius: 20px;
    padding: 80px 25px 40px;
    text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.25);
    -webkit-box-shadow:rgba(255, 255, 255, 0.258824) 0px 10px 18px 7px inset, rgba(0, 0, 0, 0.1) 0px -10px 18px 7px inset;
    -moz-box-shadow:rgba(255, 255, 255, 0.258824) 0px 10px 18px 7px inset, rgba(0, 0, 0, 0.1) 0px -10px 18px 7px inset;
    box-shadow:rgba(255, 255, 255, 0.258824) 0px 10px 18px 7px inset, rgba(0, 0, 0, 0.1) 0px -10px 18px 7px inset;
  }
</style>
