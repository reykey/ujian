
<!-- tampilan modul soal mulai dari sini -->
<?php
//session_start();
// if(isset($_SESSION["mulai_waktu"])){
//   $telah_berlalu = time() - $_SESSION["mulai_waktu"];
//   }
// else {
//   $_SESSION["mulai_waktu"] = time();
//   $telah_berlalu = 0;
//   } 
?>
<!-- <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script> -->
<!-- <script type="text/javascript" src="js/jquery.countdown.js"></script> -->
<div id="tempat_timer">
<span id="timer">00 : 00 : 00</span>
</div>

<?php //dump($paketSoal);?>
<?php 
  $sess = $this->session->userdata('jam_mulai');
  $jam_mulai = $sess;
  $jam_sekarang = new DateTime('now');

  $selisih = $jam_sekarang->getTimestamp() - $jam_mulai;
  echo $jam_mulai."<br>".$jam_sekarang->getTimestamp()."<br>".$selisih; 
?>
<script type="text/javascript">
function waktuHabis(){
  alert("Waktu pengerjaan habis. Silakan pulang......");
  }   
function hampirHabis(periods){
  if($.countdown.periodsToSeconds(periods) == 60){
    $(this).css({color:"red"});
    }
  }
$(function(){
  var waktu = <?php echo $paketSoal->alokasi_waktu - $selisih; ?>; // 3 menit
  //var sisa_waktu = waktu - 30;
  var longWayOff = waktu;
  $("#timer").countdown({
    until: longWayOff,
    compact:true,
    onExpiry:waktuHabis,
    onTick: hampirHabis
    }); 
  })
</script>
<style>
#tempat_timer{
  margin:0 auto;
  text-align:center;
  }
#timer{
  border-radius:7px;
  border:2px solid gray;
  padding:7px;
  font-size:2em;
  font-weight:bolder;
  }
</style>          


          <div class="content">
            <?php //dump($group); ?>
            <h2 class="paketsoal" id="<?php echo $paketSoal->id; ?>"><?php echo lang('ujian:paket'); ?> - <?php echo 'judul' ; ?></h2>

          </div>
          
          <div class="content bg-white">
            <div class="row-fluid">
              <div class="span12">

                <!-- ini mulai soal ditampilkan -->
                <ul class="quiz">
                  <li class="instruction">
                     <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore, dolore repellat animi culpa dicta ut veritatis accusantium nulla corrupti mollitia aut optio ratione incidunt voluptate reiciendis voluptas maiores aspernatur suscipit.</p>
                  </li>

                  <?php foreach($group as $item):?>
                      <?php //dump($item['soal']); ?>
                      <h4><?php echo lang('ujian:group'); ?> - <?php echo $item['judul'] ; ?></h4>

                   <?php $i=1; foreach($item['soal'] as $soallist):?>  
                  <li>
                    <span class="number label label-success"><?php echo $i; ?></span>
                    <div class="question">
                     
                      <?php echo $soallist['pertanyaan']; ?>

                    </div>
                    <ul class="choice">
                        <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" rel="pilihan_a"><?php echo $soallist['pilihan_a'];?></li>
                        <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" rel="pilihan_b"><?php echo $soallist['pilihan_b'];?></li>
                        <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" rel="pilihan_c"><?php echo $soallist['pilihan_c'];?></li>
                        <li><input type="radio" name="jawaban_<?php echo $soallist['id'];?>" rel="pilihan_d"><?php echo $soallist['pilihan_d'];?></li>
                    </ul>
                    <?php $i++; endforeach; ?>
                  </li>

                  <?php endforeach;?>
                   
                </ul>

                <!-- pagination halaman soal -->
                <div class="pagination pagination-centered">
                  <!-- <ul>
                    <li><a href="#">Prev</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">Next</a></li>
                  </ul> -->
                </div>

                 <div class="">
                  <ul>
                    <li>
                    <td class="action">
                          <?php echo anchor('ujian/selesai/'.$paket_id, lang('ujian:selesai'), array('class'=>'button')); ?></td>
                  </li>
                  </ul>
                </div>

              </div>
            </div>
          </div>
          <!-- tampilan modul soal berakhir disini -->

          <script>
          jQuery(function($){
            // function errorPlacement(error, element){
            //   element.before(error);
            // }
              // var paket_id = $('#paket_id').val();
              // var user_id = $('#user_id').val();
              // var soal_id = $('#soal_id').val();
              // //var jawaban = $('#jawaban').val();
              // var jawaban = $('input[name=jawaban]:checked').val();
              
              $('input[type=radio]').click(function(){

                $.ajax({
                  //Alamat url harap disesuaikan dengan lokasi script pada komputer anda
                  url      : "<?php echo site_url('ujian/simpan_jawaban'); ?>",
                  type     : 'POST',
                  data     : {jawaban:$(this).attr('rel'), soal:$(this).attr('name'), paket:$('h2.paketsoal').attr('id')}
                }).done(function(msg){
                  console.log(msg);
                  return true;
                })
              });
          });

           </script> 
        
   