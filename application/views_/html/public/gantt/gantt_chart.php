<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<head>
    <title>S.P.E.K.T.A</title>
    <link rel="icon" href="<?= asset("img/distorcy.png") ?>" type="image/x-icon" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<script src="<?= asset('codebase/dhtmlxgantt.js') ?>"></script>
	<link rel="stylesheet" href="<?= asset('codebase/dhtmlxgantt.css') ?>">
	<style>
		html, body {
			height: 100%;
			padding: 0px;
			margin: 0px;
			overflow: hidden;
		}
	</style>
</head>
<body>
	<div id="gantt_here" style='width:100%; height:100%;'></div>
    <?php 
        // dd($data['data']);
    ?>
	<script>
		gantt.config.readonly = true;
		gantt.init("gantt_here");
		gantt.parse({
			data: <?= json_encode($data['data']) ?>,
			links: <?= json_encode($data['links']) ?>
		});
	</script>
</body>