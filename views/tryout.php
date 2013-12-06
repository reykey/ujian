<div id="tempat_timer"><span id="timer">00 : 00 : 00</span></div>

<?php //dump($paketSoal);?>
<?php 
  $sess = $this->session->userdata('jam_mulai');
  $jam_mulai = $sess;
  $jam_sekarang = new DateTime('now');
  // dump($paketSoal);
  $alokasi = $paketSoal->alokasi_waktu * 60;
  $selisih = $jam_sekarang->getTimestamp() - $jam_mulai;
  // echo $jam_mulai."<br>".$jam_sekarang->getTimestamp()."<br>".$selisih."<br>".$alokasi; 
?>

<div class="content">
  <?php //dump($group); ?>
  <h2 class="paketsoal" id="<?php echo $paketSoal->id; ?>"><?php echo lang('ujian:paket'); ?> - <?php echo 'judul' ; ?></h2>
</div>

<div class="content bg-white" style="margin-bottom:50px;">
  <div class="row-fluid">
    <div class="span12">

      <!-- ini mulai soal ditampilkan -->
      <ul class="quiz">
        
        <?php $i=1; foreach($group as $item): ?>
        <li class="group">
          <h4><?php echo $item['judul_grup']; ?></h4>
          <p><?php echo nl2br($item['instruksi']); ?></p>
        </li>

        <?php foreach($item['soal'] as $soallist): ?>
        <li class="questions">
            <span class="number"><?php echo $i; ?></span>
            <div class="question">
              <?php echo $soallist['pertanyaan']; ?>
              <?php echo !empty($soallist['gambar_soal'])? '<br>'.$soallist['gambar_soal']['img']: ''; ?>
            </div>
            <ul class="choice" id="soal_<?php echo $soallist['id']; ?>">
              <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" class="A" rel="pilihan_a"> <?php echo $soallist['pilihan_a'];?></li>
              <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" class="B" rel="pilihan_b"> <?php echo $soallist['pilihan_b'];?></li>
              <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" class="C" rel="pilihan_c"> <?php echo $soallist['pilihan_c'];?></li>
              <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" class="D" rel="pilihan_d"> <?php echo $soallist['pilihan_d'];?></li>
              <?php if(trim($soallist['pilihan_e']) != ''): ?>
              <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" class="E" rel="pilihan_e"> <?php echo $soallist['pilihan_e'];?></li>
            <?php endif; ?>
            </ul>
        </li>
        <?php $i++; endforeach; ?>

        <?php endforeach;?>

      </ul>

      <!-- pagination halaman soal -->
     <!-- <div class="pagination pagination-centered" id="pagination"></div> -->

      <div class="action pull-right">
        <?php echo anchor('tryout/selesai/'.$id, lang('ujian:selesai'), array('class'=>'btn btn-success confirm-selesai')); ?></td>
      </div>

      </div>
    </div>
  </div>
  <!-- tampilan modul soal berakhir disini -->

  <script>
  function waktuHabis(){
    alert("Waktu pengerjaan habis. Terima kasih.");
    window.location = '<?php echo site_url('tryout/selesai/'.$paketSoal->id); ?>';
  }   
  function hampirHabis(periods){
    if($.countdown.periodsToSeconds(periods) == 60){
      $(this).css({color:"red"});
    }
  }

  $(function(){
    // siapkan countdown
    var waktu = <?php echo $alokasi - $selisih; ?>;
    // var waktu = 30;
    //var sisa_waktu = waktu - 30;
    $("#timer").countdown({
      until: waktu,
      compact:true,
      onExpiry:waktuHabis,
      onTick: hampirHabis
    }); 

    // pasang konfirmasi untuk button selesai
    $('.confirm-selesai').click(function(e){
      return confirm("Setelah Anda menekan tombol selesai sistem akan menghitung hasilnya dan tryout tidak akan dapat diulangi lagi. Anda yakin akan mengakhiri sessi tryout ini?");
    })
  });

  jQuery(function($){

    var jawaban = <?php echo json_encode($jawaban); ?>;
    // console.log(jawaban);
    for(var j = 0; j < jawaban.length; j++){
      $('#soal_'+jawaban[j].soal_id).find('input.'+jawaban[j].jawaban).prop('checked', true);
    }

    $('input[type=radio]').click(function(){
      $.ajax({
        //Alamat url harap disesuaikan dengan lokasi script pada komputer anda
        url      : "<?php echo site_url('tryout/simpan_jawaban'); ?>",
        type     : 'POST',
        data     : {jawaban:$(this).attr('rel'), soal:$(this).attr('name'), paket:$('h2.paketsoal').attr('id')}
      }).done(function(msg){
        console.log(msg);
        return true;
      })
    });

  });

  // var options = {
  //   currentPage: 1,
  //   totalPages: <?php echo $i/$perpage; ?>,
  //   alignment: 'center',
  //   numberOfPages: 10,
  //   pageUrl: function(type, page, current){
  //     return "#"+page;
  //   },
  //   onPageClicked: function(e,originalEvent,type,page){
  //     alert(page);
  //   }
  // };

  // $('#pagination').bootstrapPaginator(options);

  </script> 


