<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>

<head>
	<title>S.P.E.K.T.A</title>
	<link rel="icon" href="<?= asset("img/spekta.png") ?>" type="image/x-icon" />
	<link href="<?= asset('codebase/dhtmlx.css') ?>" rel="stylesheet">
	<link href="<?= asset('codebase/dhtmlxscheduler_material.css') ?>" rel="stylesheet">
	<link href="<?= asset('codebase/dhtmlxgantt.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/style.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/pace.css') ?>" rel="stylesheet">
	<script type="text/javascript" src="<?= asset('js/head.min.js') ?>"></script>
	<script type="text/javascript" src="<?= asset('js/pace.js') ?>"></script>
	<script type="text/javascript" src="<?= asset('js/spin.js') ?>"></script>
</head>

<body>
	<div id="mainCanvas" class=".anim_gradient">
        <div class="login_form">
			<form id="realForm" action="index.php?c=AuthController&m=login" method="POST" target="submit_ifr">
				<div id="Container"></div>
			</form>
		</div>
		<iframe style="width:0%;height:0%;display:none;" name="submit_ifr" class="submit_iframe"></iframe>
    </div>
</body>

<script type="text/javascript">
	var js_jquery = "<?= asset('js/jquery.min.js') ?>";
	var js_load = "<?= asset('js/load.js') ?>";
	var js_custom = "<?= asset('js/custom.js') ?>";
	var js_auth = "<?= asset('js/auth.js') ?>";
	var js_dhtmlx = "<?= asset('codebase/dhtmlx.js') ?>";
	var js_dhtmlx_export = "<?= asset('codebase/dhtmlxgrid_export.js') ?>";
	var js_download = "<?= asset('js/download.min.js') ?>";
	var js_dhtmlx_scheduler = "<?= asset('codebase/dhtmlxscheduler.js') ?>";
	var js_dhtmlxgantt = "<?= asset('codebase/dhtmlxgantt.js') ?>";
	var js_highchart = "<?= asset('codebase/highcharts.js') ?>";
	var js_highchart_exporting = "<?= asset('codebase/exporting.js') ?>";
	var js_highcharts_more = "<?= asset('codebase/highcharts-more.js') ?>";

	var js_dfm = "<?= asset('codebase/dhtmlxFormMessage.js') ?>";

	var userLogged, appMenus = [],
		appAccordions = [],
		appTrees = [];

	var isLogin = "<?= $this->auth->isLogin() ?>";
	var BASE_URL = "https://spekta.id/";
	
	toDay = "<?= toIndoDateDay(date('Y-m-d')) ?>";
	globalDate = "<?= date('Y-m-d') ?>";

	if (isLogin) {
		localStorage.setItem("isLogin", true);

		userLogged = {
			userId: "<?= $this->auth->userId ?>",
			username: "<?= $this->auth->username ?>",
			roleId: "<?= $this->auth->roleId ?>",
			role: "<?= $this->auth->role ?>",
			empNip: "<?= $this->auth->empNip ?>",
			empId: "<?= $this->auth->empId ?>",
			empName: "<?= $this->auth->empName ?>",
			deptId: "<?= $this->auth->deptId ?>",
			department: "<?= $this->auth->department ?>",
			subId: "<?= $this->auth->subId ?>",
			subDepartment: "<?= $this->auth->subDepartment ?>",
			rankId: "<?= $this->auth->rankId ?>",
			rank: "<?= $this->auth->rank ?>",
			divId: "<?= $this->auth->divId ?>",
			division: "<?= $this->auth->division ?>",
			empLoc: "<?= $this->auth->empLoc ?>",
			locName: "<?= $this->auth->locName ?>",
			picOvertime: "<?= $this->auth->picOvertime ?>",
			pltDepartment: "<?= $this->auth->pltDepartment ?>",
			pltDeptId: "<?= $this->auth->pltDeptId ?>",
			pltSubDepartment: "<?= $this->auth->pltSubDepartment ?>",
			pltSubId: "<?= $this->auth->pltSubId ?>",
			pltDivision: "<?= $this->auth->pltDivision ?>",
			pltDivId: "<?= $this->auth->pltDivId ?>",
			pltRankId: "<?= $this->auth->pltRankId ?>",
		}

		if (localStorage.getItem("appMenus")) {
			appMenus = localStorage.getItem("appMenus");
		}

		if (localStorage.getItem("appAccordions")) {
			appAccordions = localStorage.getItem("appAccordions");
		}

		if (localStorage.getItem("appTrees")) {
			appTrees = localStorage.getItem("appTrees");
		}

		head.load(js_load, js_highchart, js_highchart_exporting, js_highcharts_more, js_dhtmlx_scheduler,
			js_dhtmlxgantt, js_dhtmlx,  js_jquery,
			function () {
				head.load(js_download, js_custom);
				$("#mainCanvas").removeClass("anim_gradient");
				$("#Container").remove();
				spinner = new Spinner().spin(document.body);
				loadExternalJS("layout");
				loadExternalJS("admin");
				loadPrivilageViewJS();
			});

		setInterval(() => {
			autoLogout();
		}, 15000);
	} else {
		head.load(js_load, js_highchart, js_highchart_exporting, js_highcharts_more, js_dhtmlx_scheduler,
			js_dhtmlxgantt, js_dhtmlx, js_dhtmlxgantt, js_jquery, js_auth,
			function () {
				$("#mainCanvas").addClass("anim_gradient");
				head.load(js_download, js_custom);
				loginFormUI();
			});
	}
</script>

<style>
	.anim_gradient {
		/* background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); */
		background: linear-gradient(-45deg, #ee7752, #063a78, #f39200, #23d5ab);
		background-size: 400% 400%;
		animation: gradient 15s ease infinite;
	}

	#Container {
		border-radius: 30px;
	}

	@keyframes gradient {
		0% {
			background-position: 0% 50%;
		}
		50% {
			background-position: 100% 50%;
		}
		100% {
			background-position: 0% 50%;
		}
	}
</style>

</html>