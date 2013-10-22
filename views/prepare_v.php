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
                    
                    <p>1. Bla balksaoksak</p>
                  </li>

                   
                  <li>
                    <span class="number label label-success">1</span>
                    <div class="question">
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam, aperiam, molestias, fuga ex voluptates illo enim dignissimos animi ipsa molestiae perspiciatis reprehenderit exercitationem facilis officiis modi impedit sequi amet explicabo?
                    </div>
                    
                  </li>

                  <li>
                    <td class="action">
                          <?php echo anchor('ujian/index/', lang('ujian:back'), array('class'=>'button')); ?></td>
                    <td class="action">
                          <?php echo anchor('ujian/getMulai/'.$id, lang('ujian:mulai'), array('class'=>'button')); ?></td>
                  </li>

                </ul>
              </div>
            </div>
          </div>
          <!-- tampilan modul soal berakhir disini -->