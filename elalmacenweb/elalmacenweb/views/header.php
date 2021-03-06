<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->helper('html');
$this->load->helper('url');

		$metaline1 = array('name' => 'description', 'content' => 'Sistema de reportes, datos y consultas a OASIS para ERP');
		$metaline2 = array('name' => 'keywords', 'content' => 'system, admin, catalogo, sistemas');
		$metaline3 = array('name' => 'Content-type', 'content' => 'text/html; charset='.config_item('charset'), 'type' => 'equiv');
		$metaline4 = array('name' => 'Cache-Control', 'content' => 'no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0', 'type' => 'equiv');
		$metaline5 = array('name' => 'Last-Modified', 'content' => gmdate("D, d M Y H:i:s") . ' GMT', 'type' => 'equiv');
		$metaline6 = array('name' => 'pragma', 'content' => 'no-cache', 'type' => 'equiv');
		$metalines = array('name' => 'Content-Security-Policy', 'content' => '');
		$meta = array( $metaline1, $metaline2, $metaline3, $metaline4, $metaline5, $metaline6 );

		$pathcss = base_url() . SYSDIR . '/styles/'; $typcs='text/css';
		$pathjsc = base_url() . SYSDIR . '/scripts/'; $typjs='text/javascript';

		$linkappcss = array('type'=>$typcs,'rel'=>'stylesheet','href' => $pathcss.'cibootstrap.css?'.time()); // script de css sin tener que especificar clases en cada tag
		$linkappcssjs = array('type'=>$typjs,'src' => $pathjsc.'cibootstrap.js?'.time()); // script de css sin tener que especificar clases en cada tag

		$linkdatepickerurl = array('type'=>$typjs,'src' => $pathjsc.'datetimepicker.js?'.time());
		$linkpickathingcss = array('type'=>$typcs,'rel'=>'stylesheet','href' => $pathcss.'pickathing.css?'.time()); // script de css para selects combos pero con inputs search
		$vanilladatatablescss = array('type'=>$typcs,'rel'=>'stylesheet','href' => $pathcss.'vanilla-dataTables.css?'.time()); // script de css para vanilla data tables

		$webcss = base_url() . 'assets/elyanerocss/'; $typcs='text/css';
		$webjsc = base_url() . 'assets/elyanerojs/'; $typjs='text/javascript';

		$linkwebcss = array('type'=>$typcs,'rel'=>'stylesheet','href' => $webcss.'elyanerocss.css?'.time()); // script de css sin tener que especificar clases en cada tag
		$linkwebcssjs = array('type'=>$typjs,'src' => $webjsc.'elyanerojs.js?'.time()); // script de css sin tener que especificar clases en cada tag

		$linksigcss = array('type'=>$typcs,'rel'=>'stylesheet','href' => $webcss.'signature-pad.css?'.time()); // script de css sin tener que especificar clases en cada tag
		//$linksigjs1 = array('type'=>$typjs,'src' => $webjsc.'signature_pad.umd.js?'.time()); // script de css sin tener que especificar clases en cada tag
		//$linksigjs2 = array('type'=>$typjs,'src' => $webjsc.'app.js?'.time()); // script de css sin tener que especificar clases en cada tag

	echo doctype('xhtml1-trans'), PHP_EOL,'<html xmlns="http://www.w3.org/1999/xhtml">', PHP_EOL;
	echo '<head>', PHP_EOL;
		echo meta($meta);
		echo link_tag($linkappcss);		// link css estilo apariencia sin especificar clases en cada tag
		echo script_tag($linkappcssjs);
		echo link_tag($linkpickathingcss);		// link css estilo apariencia para poder llenar los combos select con input search
		echo link_tag($vanilladatatablescss);		// link css estilo apariencia para poder llenar los combos select con input search
		echo script_tag($linkdatepickerurl);	// comportamiento de selector de fechas sin usar jquery, 1005 compatible con cualqueir navegador
		echo link_tag($linkwebcss);		// link css estilo apariencia sin especificar clases en cada tag
		echo script_tag($linkwebcssjs);
		echo script_tag($linksigcss); // link para firmas graficas via web
	echo '</head>', PHP_EOL;
	?>
	<body onload = 'checkAvailable()' > <!-- check browsers and denied the non-standard -->
		<div id="menu" class="menu ">
			<center>
				<?php if( isset($menu) ) echo $menu.PHP_EOL ?>
			</center>
		</div>
		<div id="menusub">
			<center>
				<?php if( isset($menusub) ) echo $menusub.PHP_EOL ?>
			</center>
		</div>
	<center>

