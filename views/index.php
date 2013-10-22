<!-- tampilan modul soal mulai dari sini -->
          <div class="content">
            <h2>Try Out Online STAN</h2>
          </div>
          
          <div class="content bg-white">
            <div class="row-fluid">
              <div class="span12">

                <!-- ini mulai soal ditampilkan -->
                 <?php if (!empty($pengguna)): ?>

                <ul class="quiz">
                  <li class="instruction">
                    <h4>Sinonim</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore, dolore repellat animi culpa dicta ut veritatis accusantium nulla corrupti mollitia aut optio ratione incidunt voluptate reiciendis voluptas maiores aspernatur suscipit.</p>
                  </li>
                  <li>

                    <?php //dump($pengguna);?>
                    <?php //dump($nilai);?>

                    <?php $i=1; foreach( $pengguna['entries'] as $item ): ?>
                      <?php
                        $tglSekarang = date('Y-m-d H:i:s');
                        $tglMulai = date('Y-m-d H:i:s', $item['paket_id']['tanggal_buka']);
                        $tglSelesai = date('Y-m-d H:i:s',$item['paket_id']['tanggal_tutup']);
                        if($tglSekarang >= $tglMulai && $tglSekarang <= $tglSelesai):
                      ?>

                        <td><span class="number label label-success"><?php echo $i; ?></span></td>
                        <td><?php echo $item['paket_id']['tanggal_buka']; ?></td>
                        <td class="action">
                          <?php echo anchor('ujian/prepare/'.$item["paket_id"]['id'], lang('ujian:mulai'), array('class'=>'button')); ?></td>
                      <?php elseif ($tglSekarang <= $tglMulai): ?>
                        <td>belum mulai</td>
                      <?php elseif ($tglSekarang >= $tglSelesai): ?>
                        <td>Expired</td>
                      <?php endif; ?>
                    <?php $i++; endforeach; ?>

                    
                    <div class="question">
                      Mulai
                    </div>
                    
                  </li>

                  <li>
                    <h4>Aturan Main :</h4>
                    <p></p>
                  </li>
                </ul>

                <?php else: ?>
                <div class="no_data"><?php echo lang('ujian:no_items'); ?></div>
                <?php endif;?>

              </div>
            </div>
          </div>
          <!-- tampilan modul soal berakhir disini -->