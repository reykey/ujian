<!-- tampilan modul soal mulai dari sini -->
          <div class="content">
            <?php dump($dapat); foreach($paket as $item):?>
                    <?php $this->groupSoal($item->id); ?>
            <h2><?php echo lang('ujian:paket'); ?> - <?php echo $item['judul'] ; ?></h2>
          
            <?php foreach($data['pengguna'] as $item):?>
                    <?php $this->data($item['index']); ?>
                    <h2><?php //echo lang('ujian:paket'); ?> - <?php //echo $item['judul'] ; ?></h2>
                    <?php endforeach;?>
            
          <?php endforeach;?>
          </div>



          
          <div class="content bg-white">
            <div class="row-fluid">
              <div class="span12">

                <!-- ini mulai soal ditampilkan -->
                <ul class="quiz">
                  <li class="instruction">
                    <h4>Sinonim</h4>
                    <?php dump($paketSoal); foreach($group['entries'] as $item):?>
                    <h4><?php echo lang('ujian:group'); ?> - <?php echo $item['judul'] ; ?></h4>
                    <?php endforeach;?>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore, dolore repellat animi culpa dicta ut veritatis accusantium nulla corrupti mollitia aut optio ratione incidunt voluptate reiciendis voluptas maiores aspernatur suscipit.</p>
                  </li>
                  <li>
                    <span class="number label label-success">1</span>
                    <div class="question">
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam, aperiam, molestias, fuga ex voluptates illo enim dignissimos animi ipsa molestiae perspiciatis reprehenderit exercitationem facilis officiis modi impedit sequi amet explicabo?
                    </div>
                    <ul class="choice">
                      <li><input type="radio" name="a"> Lorem ipsum</li>
                      <li><input type="radio" name="b"> Dolor sit</li>
                      <li><input type="radio" name="c"> Amet pisan</li>
                      <li><input type="radio" name="d"> Consectetur</li>
                    </ul>
                  </li>

                  <li>
                    <span class="number label label-success">2</span>
                    <div class="question">
                      <img src="img/art/ad1.jpg" alt=""><br>
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti praesentium culpa molestias voluptatum ratione blanditiis error adipisci neque. Odio sapiente quos minima quam hic explicabo dolor ea quae vero a.
                    </div>
                    <ul class="choice">
                      <li><input type="radio" name="a"> Amet pisan</li>
                      <li><input type="radio" name="b"> Dolor sit</li>
                      <li><input type="radio" name="c"> Lorem ipsum</li>
                      <li><input type="radio" name="d"> Consectetur</li>
                    </ul>
                  </li>
                </ul>

                <!-- pagination halaman soal -->
                <div class="pagination pagination-centered">
                  <ul>
                    <li><a href="#">Prev</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">Next</a></li>
                  </ul>
                </div>

              </div>
            </div>
          </div>
          <!-- tampilan modul soal berakhir disini -->