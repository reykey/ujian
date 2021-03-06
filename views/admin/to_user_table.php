	<?php if (!empty($entries)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th>No</th>
					<th><?php echo lang('to:name'); ?></th>
					<th><?php echo lang('to:status_pengerjaan'); ?></th>
					<th><?php echo lang('to:nilai'); ?></th>
					<th><?php echo lang('to:status_ujian'); ?></th>
					<th><?php echo lang('to:paket'); ?></th>
					<th><?php echo lang('to:jam_mulai'); ?></th>
					<th><?php echo lang('to:jam_selesai'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php //dump($entries); ?>
				<?php if(!empty($entries["entries"])): $i=1; foreach( $entries["entries"] as $item ): ?>
				<tr id="item_<?php echo $item["id"]; ?>">
					<td><?php echo $i; ?></td>
					<td><?php echo $item["user_id"]['first_name'].' '.$item["user_id"]['last_name']; ?></td>
					<td><?php echo $item["status_pengerjaan"]["value"]; ?></td>
					<td><?php echo $item["nilai"]; ?></td>
					<td><?php echo $item["status_ujian"]['value']; ?></td>
					<td><?php echo $item["paket_id"]["judul_paket"]; ?></td>
					<td><?php echo ($item["jam_mulai"]) ? date("d F Y H:i:s", $item["jam_mulai"]) : "-"; ?></td>
					<td><?php echo ($item["jam_selesai"]) ? date("d F Y H:i:s", $item["jam_selesai"]): "-"; ?></td>
					<td class="actions">
						<?php echo anchor('admin/tryout/to_user/detail/'.$item["id"], lang('ujian:detail'), array('class'=>'button', 'title'=>lang('ujian:detail'))); ?>
						<?php echo anchor('admin/tryout/to_user/delete/'.$item["id"], lang('ujian:hapus'), array('class'=>'button confirm', 'title'=>lang('ujian:hapus'))); ?>
					</td>
				</tr>
				<?php $i++; endforeach; else: ?> <div class="no_data"><?php echo lang('ujian:no_items'); ?></div> <?php  endif; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="no_data"><?php echo lang('ujian:no_items'); ?></div>
	<?php endif;?>