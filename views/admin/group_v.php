<section class="title">
    <h4><?php echo anchor('admin/tryout', lang('ujian:paket')); ?> - <?php echo $paket->judul ; ?> </h4>
</section>

<section class="item">
    <div class="content">
        
    <div id="stream-table" class="streams">
            <?php if (!empty($entries)): ?>
        <table border="0" class="table-list" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th><?php echo lang('ujian:namag'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php $i=$nomoratas; foreach( $entries['entries'] as $item ): ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $item['judul']; ?></td>                    
                    <td class="actions">
                        <?php echo anchor('admin/tryout/soal/'.$item["paket_id"]["id"].'/'.$item["id"], lang('ujian:atur_soal'), array('class'=>'button', 'title'=>lang('ujian:lihatSoal'))); ?>
                        <?php echo anchor('admin/tryout/edit_group/'.$item["id"], lang('ujian:edit'), array('class'=>'button', 'title'=>lang('ujian:edit'))); ?>
                        <?php echo anchor('admin/tryout/delete_group/'.$item["id"], lang('global:delete'), array('class'=>'button confirm', 'title'=>lang('ujian:deleted'))); ?>
                    </td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>

        <?php echo $entries['pagination']; ?>
    <?php else: ?>
        <div class="no_data"><?php echo lang('ujian:no_items'); ?></div>
    <?php endif;?>
    </div>


</div>
<!-- </section> -->