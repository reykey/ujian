<section class="title">
	<!-- We'll use $this->method to switch between faq.create & faq.edit -->
	<h4><a href="<?php echo site_url('admin/tryout/to_user'); ?>">Tryout User</a> &raquo; Detail</h4>
</section>

<section class="item">
	<div class="content">
        <fieldset class="one_half">
            <legend>Detail Peserta</legend>

            <dt>Nama Peserta</dt>
            <dd><?php echo $to_user->first_name.' '.$to_user->last_name; ?></dd>
            <dt>Alamat Email</dt>
            <dd><?php echo $to_user->email; ?></dd>
            <dt>Kode Akses</dt>
            <dd><?php echo $detail->generated_key; ?></dd>
            <br>
            
            <dt>No. Telepon</dt>
            <dd><?php echo $to_user->phone; ?></dd>
            <dt>Asal Sekolah</dt>
            <dd><?php echo $to_user->sekolah; ?></dd>
            <dt>Alamat Sekolah</dt>
            <dd><?php echo $to_user->alamat_sekolah; ?></dd>
            <dt>Provinsi</dt>
            <dd><?php echo $to_user->provinsi; ?></dd>
        </fieldset>
        
        <fieldset class="two_half">
            <legend>Detail Tryout</legend>

            <dt>Paket Tryout</dt>
            <dd><?php echo $detail->judul; ?></dd>
            <dt>Alokasi Waktu</dt>
            <dd><?php echo $detail->alokasi_waktu; ?> menit</dd>
            <dt>Tanggal Buka</dt>
            <dd><?php echo date("d F Y H:i:s", strtotime($detail->tanggal_buka)); ?></dd>
            <dt>Tanggal Tutup</dt>
            <dd><?php echo date("d F Y H:i:s", strtotime($detail->tanggal_tutup)); ?></dd>
            <dt>Status Paket Tryout</dt>
            <dd>
                <?php 
                    $waktudibuka = diff_times(time(), $detail->tanggal_buka); 
                    if($waktudibuka['mark'] == 'positive'){ // kalo waktu sekarang lebih besar daritgl buka
                        $waktuditutup = diff_times(time(), $detail->tanggal_tutup);
                        if($waktuditutup['mark'] == "positive")
                            echo "Ditutup";
                        else
                            echo "Dibuka";
                    } else
                        echo "Belum Dibuka";
                ?>
            </dd>
        </fieldset>

        <fieldset class="two_half">
            <legend>Hasil Tryout</legend>

            <dt>Status Pengerjaan</dt>
            <dd><?php echo $detail->status_pengerjaan; ?></dd>
            <dt>Nilai</dt>
            <dd><?php echo (int) $detail->nilai; ?></dd>
        </fieldset>
    </div>
</section>

<style>
	dt {line-height: 16px; font-weight: bold;}
	dd {line-height: 16px; margin-bottom: 10px;}
</style>