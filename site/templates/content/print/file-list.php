<h2>
	<?= $page->title; ?>
</h2>
<table class="table table-sm">
	<tr class="detail document-header">
		<td colspan="2">Documents</td> <td colspan="2">Document Type</td> <td align="right">Date</td> <td align="right">Time</td>
		<td></td>
		<td></td>
	</tr>
	<?php if (!empty($orderdoc)) : ?>
		<?php $filename = $orderdoc['pathname']; ?>
		<tr class="detail">
			<td colspan="2"></td>
			<td colspan="2">
				<b><a href="<?= $config->documentstorage.$filename; ?>" title="Click to View Document" target="_blank" ><?= $orderdoc['title']; ?></a></b>
			</td>
			<td align="right">
				<?= Dplus\Base\DplusDateTime::format_date($orderdoc['createdate']); ?>
			</td>
			<td align="right">
				<?= Dplus\Base\DplusDateTime::format_dplustime($orderdoc['createtime'], 'Hi'); ?>
			</td>
			<td>
				<a href="<?= $emailurl->getUrl(); ?>" class="btn btn-primary load-into-modal hidden-print" data-modal="#ajax-modal">
					<i class="glyphicon glyphicon-envelope" aria-hidden="true"></i> Send as Email
				</a>
			</td>
			<td>
				<a href="<?= $config->documentstorage.$filename; ?>" target="_blank" class="btn btn-primary hidden-print">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> View PDF
				</a>
			</td>
		</tr>
	<?php endif; ?>
</table>
