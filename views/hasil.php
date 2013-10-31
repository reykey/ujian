<!-- tampilan modul soal mulai dari sini -->
          <div class="content">
            <h2>Hasil Try Out Online</h2>
          </div>
          
          <div class="content bg-white">
            <div class="row-fluid">
              <div class="span12">

                <!-- ini mulai soal ditampilkan -->
                <ul class="quiz">
                  <li class="instruction">
                    
                    <p>Hasil</p>
                  </li>

                   
                  <li>
                    <span class="number label label-success">1</span>
                    <div class="question">
                      
                      <?php //echo $nilai_benar;//echo $soal['stat']; ?>
                      <?php //echo $nilai_salah;//echo $soal['stat']; ?>
                      <p>Benar : <?php echo $total_benar;?></p>
                      <p>Salah : <?php echo $total_salah;?></p>
                      <p>Kosong : <?php echo $total_kosong;?></p>
                      <p>Nilai Total : <?php echo $total;?></p>
                    </div>


                  </li>

                  <li>
                    <td class="action">
                          <?php //echo anchor('tryout/selesai/', lang('ujian:back'), array('class'=>'button')); ?></td>
                  </li>

                </ul>
              </div>
            </div>
          </div>
          <!-- tampilan modul soal berakhir disini -->