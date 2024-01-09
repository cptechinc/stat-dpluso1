<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#task" aria-controls="task" role="tab" data-toggle="tab">Task</a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="task">
			<form action="<?= $config->pages->useractions."add/"; ?>" method="POST" id="new-action-form" data-refresh="#actions-panel" data-modal="#ajax-modal" onKeyPress="return disable_enterkey(event)">
				<input type="hidden" name="action" value="reschedule-task">
				<input type="hidden" name="customerlink" value="<?= $task->customerlink; ?>">
				<input type="hidden" name="shiptolink" value="<?= $task->shiptolink; ?>">
				<input type="hidden" name="contactlink" value="<?= $task->contactlink; ?>">
				<input type="hidden" name="salesorderlink" value="<?= $task->salesorderlink; ?>">
				<input type="hidden" name="quotelink" value="<?= $task->quotelink; ?>">
				<input type="hidden" name="actionlink" value="<?= $task->id; ?>">
				<div class="response"></div>
				<table class="table table-bordered table-striped">
					<tr>  <td class="control-label">Task Date:</td> <td><?= date(UserAction::$datedisplayformat); ?></td> </tr>
					<tr>
						<td class="control-label">Assigned To:</td>
						<td>
							<?= $editactiondisplay->generate_selectsalesperson($task->assignedto); ?>
						</td>
					</tr>
					<?php include $config->paths->content."common/show-linked-table-rows.php"; ?>
					<tr>
						<td class="control-label">Due Date</td>
						<td>
							<div class="input-group date" style="width: 180px;">
								<?php $name = 'duedate'; $value = ''; ?>
								<?php include $config->paths->content."common/date-picker.php"; ?>
							</div>
						</td>
					</tr>
					<tr>
						<td class="control-label">Task Type <br><small>(Click to choose)</small></td>
						<td>
							<?= $editactiondisplay->generate_selectsubtype($task); ?>
						</td>
					</tr>
					<tr>
						<td class="control-label">Title</td>
						<td>
							<input type="text" name="title" class="form-control" value="<?= $task->title; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="control-label">
							<label for="" class="control-label">Notes</label>
							<textarea name="textbody" id="note" cols="30" rows="10" class="form-control note required"><?= $task->textbody; ?> </textarea> <br>
						</td>
					</tr>
					<tr>
						<td>
							<button type="submit" class="btn btn-success">
								<i class="glyphicon glyphicon-floppy-disk"></i> Save
							</button>
						</td>
						<td class="text-right">
							<button type="button" class="btn btn-warning" data-dismiss="modal">
								<i class="glyphicon glyphicon-remove"></i> Close
							</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
