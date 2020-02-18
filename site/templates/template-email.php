<?php
	use Purl\Url;
	use Dplus\FileServices\DplusEmailer;
	use Dplus\FileServices\EmailContact;
	use Dplus\FileServices\PDFMaker;
	use Dplus\Dpluso\OrderDisplays\SalesOrderDisplay;
	use Dplus\Dpluso\OrderDisplays\QuoteDisplay;

	$sessionID = $input->get->referenceID ? $input->get->text('referenceID') : session_id();
	$emailer = new DplusEmailer();
	$emailer->set_fromlogmuser($user->loginid);

	if ($input->requestMethod() == "POST") {
		$emailto = EmailContact::create_fromarray(array('email' => $input->post->text('email'), 'name' => $input->post->text('emailname')));
		$emailer->set_subject($input->post->text('subject'));
		$emailer->add_emailto($emailto);
		$emailer->set_body($input->post->text('message'));
		$emailer->set_selfbcc(true);
	}

	$filename = $input->post->text('file');
	$file = $config->documentstoragedirectory.$filename;

	if (file_exists($file)) {
		$error = false;
		$notifytype = 'success';
		$icon = 'fa fa-paper-plane-o';
		$emailer->add_file($file);
		$emailsent = $emailer->send();

		if ($emailsent) {
			$msg = "Document was created and emailed";
		} else {
			$error = true;
			$notifytype = 'danger';
			$msg = "Email Failed to Send";
			$icon = "fa fa-exclamation-triangle";
		}

		$page->body = array(
			'response' => array (
				'error' => $error,
				'notifytype' => $notifytype,
				'message' => $msg,
				'icon' => $icon,
				'from' => $emailer->emailfrom->email
			)
		);
	} else {
		$page->body = array(
			'response' => array (
				'error' => true,
				'notifytype' => 'danger',
				'message' => "File not Found",
				'icon' => "fa fa-exclamation-triangle",
			)
		);
	}

	include $config->paths->content.'common/include-json-page.php';
