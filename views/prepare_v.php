<!-- tampilan modul soal mulai dari sini -->
          <div class="content">
            <h2>Try Out Online STAN</h2>
          </div>
          
          <div class="content bg-white">
            <div class="row-fluid">
              <div class="span12">

                <!-- ini mulai soal ditampilkan -->
                <ul class="quiz">
                  <li class="instruction">
                    
                    <p>Bacalah dengan seksama</p>
                  </li>

                   
                  <li>
                    <span class="number label label-success">1</span>
                    <div class="question">
                      Sebelum kamu mulai, pastikan kamu memiliki waktu luang sebanyak 150 menit
                    </div>

                    <span class="number label label-success">2</span>
                    <div class="question">
                      Dalam mengerjakan tryout ini, usahakan kamu standby selama 150 menit agar simulasi ujian ini berhasil
                    </div>

                    <span class="number label label-success">3</span>
                    <div class="question">
                      Jika kamu tidak memiliki waktu 150 menit full tanpa jeda, jangan dulu mengerjakan tryout ini.
                    </div>
                    
                    <span class="number label label-success">4</span>
                    <div class="question">
                      Jangan menyontek dalam bentuk apapun dan jangan pula menggunakan alat bantu hitung.
                    </div>

                    <span class="number label label-success">5</span>
                    <div class="question">
                      Ingat, keseriusan kamu dalam mengerjakan tryout ini akan sangat berpengaruh terhadap kesuksesan kamu dalam mengerjakan ujian yang sebenarnya nanti.
                    </div>

                  </li>

                  <li>
                    <td class="action">
                          <?php echo anchor('tryout/index/', lang('ujian:back'), array('class'=>'button')); ?></td>
                    <td class="action">
                          <?php echo anchor('tryout/getMulai/'.$id, lang('ujian:mulai'), array('class'=>'button')); ?></td>
                  </li>

                </ul>
              </div>
            </div>
          </div>
          <!-- tampilan modul soal berakhir disini -->