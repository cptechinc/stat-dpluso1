<?php
	use Dplus\FileServices\PDFMaker;
	use Dplus\Dpluso\OrderDisplays\SalesOrderDisplay;
	use Dplus\Dpluso\OrderDisplays\QuoteDisplay;

	$salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
	$sessionID = $input->get->referenceID ? $input->get->text('referenceID') : session_id();
	$emailurl = new Purl\Url($config->pages->ajaxload."email/email-file-form/");
	$emailurl->query->set('referenceID', $sessionID);
	$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

	switch ($page->name) { //$page->name is what we are printing
		case 'order':
			$ordn = $input->get->text('ordn');
			$orderdisplay = new SalesOrderDisplay($sessionID, $page->fullURL, '#ajax-modal', $ordn);
			$order = SalesOrderHistory::is_saleshistory($ordn) ? SalesOrderHistory::load($ordn) : $orderdisplay->get_order();
			$page->title = 'Documents for Order #' . $ordn;
			$redir = new Purl\Url($pages->get('template=router,name=orders')->child('template=redir')->httpUrl);
			$redir->query->set('action', 'get-order-documents');
			$redir->query->set('ordn', $ordn);
			$redir->query->set('sessionID', session_id());
			$emailurl->query->set('ordn', $ordn);

			$http = new WireHttp();
			$response = $http->get($redir->getUrl());
			$orderdoc = get_orderdoc(session_id(), $ordn);
			$emailurl->query->set('file', $orderdoc['pathname']);
			$page->body = $config->paths->content."print/file-list.php";
			break;
		case 'quote':
			$qnbr = $input->get->text('qnbr');
			$page->title = 'Documents for Quote #' . $qnbr;
			$redir = new Purl\Url($pages->get('template=router,name=quotes')->child('template=redir')->httpUrl);
			$redir->query->set('action', 'print-quote');
			$redir->query->set('qnbr', $qnbr);
			$redir->query->set('sessionID', session_id());
			$emailurl->query->set('qnbr', $qnbr);
			
			$http = new WireHttp();
			$response = $http->get($redir->getUrl());
			$orderdoc = get_orderdoc(session_id(), $qnbr);
			$page->body = $config->paths->content."print/file-list.php";
			$emailurl->query->set('file', $orderdoc['pathname']);
			break;
	}

	include $config->paths->content.'common/include-print-page.php';
