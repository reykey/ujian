	<?php if (!empty($entries)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th>No</th>
					<th><?php echo lang('to:name'); ?></th>
					<th><?php echo lang('to:status_pengerjaan'); ?></th>
					<th><?php echo lang('to:nilai'); ?></th>
					<th><?php echo lang('to:paket'); ?></th>
					<th><?php echo lang('to:jam_mulai'); ?></th>
					<th><?php echo lang('to:jam_selesai'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
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
					<td><?php echo $item["paket_id"]["judul"]; ?></td>
					<td><?php echo ($item["jam_mulai"]) ? date("d F Y H:i:s", $item["jam_mulai"]) : "-"; ?></td>
					<td><?php echo ($item["jam_selesai"]) ? date("d F Y H:i:s", $item["jam_selesai"]): "-"; ?></td>
					<!-- <td class="actions">
						<?php echo anchor('so/admin_orderto/edit/'.$item["id"], lang('simple_order:edit'), array('class'=>'button', 'title'=>lang('simple_order:edit'))); ?>
					</td> -->
				</tr>
				<?php $i++; endforeach; else: ?> <div class="no_data"><?php echo lang('simple_order:no_items'); ?></div> <?php  endif; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="no_data"><?php echo lang('simple_order:no_items'); ?></div>
	<?php endif;?>