<div>
	<div class="panel-body">
		<?php include $config->paths->content."user-actions/views/list-view/filter.php"; ?>
	</div>
	<div class="table-responsive">
		<?php include $config->paths->content."user-actions/views/$actionpanel->paneltype/list/tables/$actionpanel->actiontype.php"; ?>
	</div>
	<?= $paginator; ?>
</div>
