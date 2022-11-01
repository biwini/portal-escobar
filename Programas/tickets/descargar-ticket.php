<?php 

	require 'controller/ticketController.php';

	$Ticket = new ticket();

	$t = $Ticket->getTicketByCode($_GET['ticket']);
	 
?>

<!DOCTYPE HTML >
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<TITLE>planilla sistemas</TITLE>
<META name="generator" content="BCL easyConverter SDK 5.0.210">
<STYLE type="text/css">

body {margin-top: 0px;margin-left: 0px;}

#page_1 {position:relative; overflow: hidden;margin: 0px 0px 12px 0px;padding: 0px;border: none;width: 816px;}
#page_1 #id1_1 {border:none;margin: 35px 0px 0px 48px;padding: 0px;border:none;width: 768px;overflow: hidden;}
#page_1 #id1_1 #id1_1_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 600px;overflow: hidden;}
#page_1 #id1_1 #id1_1_2 {float:left;border:none;margin: 3px 0px 0px 0px;padding: 0px;border:none;width: 168px;overflow: hidden;}
#page_1 #id1_2 {border:none;margin: 13px 0px 0px 48px;padding: 0px;border:none;width: 768px;overflow: hidden;}
#page_1 #id1_3 {border:none;margin: 16px 0px 0px 49px;padding: 0px;border:none;width: 721px;overflow: hidden;}
#page_1 #id1_3 #id1_3_1 {float:left;border:none;margin: 3px 0px 0px 0px;padding: 0px;border:none;width: 476px;overflow: hidden;}
#page_1 #id1_3 #id1_3_2 {float:left;border:none;margin: 0px 0px 0px 42px;padding: 0px;border:none;width: 203px;overflow: hidden;}
#page_1 #id1_4 {border:none;margin: 54px 0px 0px 48px;padding: 0px;border:none;width: 768px;overflow: hidden;}
#page_1 #id1_4 #id1_4_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 600px;overflow: hidden;}
#page_1 #id1_4 #id1_4_2 {float:left;border:none;margin: 4px 0px 0px 0px;padding: 0px;border:none;width: 168px;overflow: hidden;}
#page_1 #id1_5 {border:none;margin: 27px 0px 0px 48px;padding: 0px;border:none;width: 768px;overflow: hidden;}
#page_1 #id1_6 {border:none;margin: 17px 0px 0px 49px;padding: 0px;border:none;width: 721px;overflow: hidden;}
#page_1 #id1_6 #id1_6_1 {float:left;border:none;margin: 2px 0px 0px 0px;padding: 0px;border:none;width: 476px;overflow: hidden;}
#page_1 #id1_6 #id1_6_2 {float:left;border:none;margin: 0px 0px 0px 42px;padding: 0px;border:none;width: 203px;overflow: hidden;}
#page_1 #id1_7 {border:none;margin: 54px 0px 0px 48px;padding: 0px;border:none;width: 768px;overflow: hidden;}
#page_1 #id1_7 #id1_7_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 600px;overflow: hidden;}
#page_1 #id1_7 #id1_7_2 {float:left;border:none;margin: 3px 0px 0px 0px;padding: 0px;border:none;width: 168px;overflow: hidden;}
#page_1 #id1_8 {border:none;margin: 26px 0px 0px 48px;padding: 0px;border:none;width: 768px;overflow: hidden;}
#page_1 #id1_9 {border:none;margin: 16px 0px 0px 49px;padding: 0px;border:none;width: 721px;overflow: hidden;}
#page_1 #id1_9 #id1_9_1 {float:left;border:none;margin: 3px 0px 0px 0px;padding: 0px;border:none;width: 495px;overflow: hidden;}
#page_1 #id1_9 #id1_9_2 {float:left;border:none;margin: 0px 0px 0px 23px;padding: 0px;border:none;width: 203px;overflow: hidden;}

#page_1 #p1dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:816px;height:1314px;}
#page_1 #p1dimg1 #p1img1 {width:816px;height:1314px;}




.dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}


/*
      www.OnlineWebFonts.Com 
      You must credit the author Copy this link on your web 
      <div>Font made from <a href="http://www.onlinewebfonts.com">oNline Web Fonts</a>is licensed by CC BY 3.0</div>
      OR
      <a href="http://www.onlinewebfonts.com">oNline Web Fonts</a>
*/
@font-face {font-family: "DIN Next LT Pro Bold"; src: url("//db.onlinewebfonts.com/t/3a88649e176a40a6d80b395ca0ae430d.eot"); src: url("//db.onlinewebfonts.com/t/3a88649e176a40a6d80b395ca0ae430d.eot?#iefix") format("embedded-opentype"), url("//db.onlinewebfonts.com/t/3a88649e176a40a6d80b395ca0ae430d.woff2") format("woff2"), url("//db.onlinewebfonts.com/t/3a88649e176a40a6d80b395ca0ae430d.woff") format("woff"), url("//db.onlinewebfonts.com/t/3a88649e176a40a6d80b395ca0ae430d.ttf") format("truetype"), url("//db.onlinewebfonts.com/t/3a88649e176a40a6d80b395ca0ae430d.svg#DIN Next LT Pro Bold") format("svg"); }
@font-face {font-family: "DINNextLTPro-Regular"; src: url("//db.onlinewebfonts.com/t/93a467f70a3e7b27a9b52a686f351dbe.eot"); src: url("//db.onlinewebfonts.com/t/93a467f70a3e7b27a9b52a686f351dbe.eot?#iefix") format("embedded-opentype"), url("//db.onlinewebfonts.com/t/93a467f70a3e7b27a9b52a686f351dbe.woff2") format("woff2"), url("//db.onlinewebfonts.com/t/93a467f70a3e7b27a9b52a686f351dbe.woff") format("woff"), url("//db.onlinewebfonts.com/t/93a467f70a3e7b27a9b52a686f351dbe.ttf") format("truetype"), url("//db.onlinewebfonts.com/t/93a467f70a3e7b27a9b52a686f351dbe.svg#DINNextLTPro-Regular") format("svg"); }
.border-l{
	border-left: groove;
}
.border-r{
	border-right: groove;
}
.border-t{
	border-top: groove;
}
.border-b{
	border-bottom: groove;
}

.border-dotted-r{
	border-right: dotted;
    border-width: 1.5px;
}
.border-dotted-b{
	border-bottom: dotted;
    border-width: 1.5px;
}

.align-b{
	vertical-align: bottom;
}

.text-align-center{
	text-align: center;
}

.text-align-right{
	text-align: right;
}

.m-top-0{
    margin-top: 0px;
}

.no-margin{
    margin-top: 0px;
    margin-bottom: 0px;
    white-space: nowrap;
}

.margin-2{
	margin-top: 2px;
    margin-bottom: 2px;
}

.pd-left-10{
	padding-left: 10px;
}

.small{
	font-size: 11px;
}
.medium{
	font-size: 13px;
}

@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</STYLE>

</HEAD>

<BODY>
<DIV id="page_1">
<DIV id="p1dimg1">
<IMG src="images/pdf.jpg" id="p1img1"></DIV>


<DIV id="id1_2">
	<?php for ($i=1; $i <= 2; $i++) { ?>

	<table cellpadding=0 cellspacing=0 style="width: 719px;font: 16px 'DIN Next LT Pro Bold';">
		<tr>
			<td width="56"></td>
			<td width="96"></td>
			<td width="136"></td>
			<td width="166"></td>
			<td width="86"></td>
			<td width="126"></td>
			<td width="150"></td>
		</tr>
		<thead>
			<tr style="height: 65px;">
				<th colspan="6" style="text-align: left;"><p class="no-margin" style="font: bold 29px 'Arial';line-height: 34px;">Dirección General de Sistemas</p></th>
				<th colspan="1"><img src="images/logo-escobar.png" alt="" width="210" height="65"></th>
			</tr>
		</thead>
	</table>
	<table cellpadding=0 cellspacing=0 style="width: 719px;font: 15px 'DINNextLTPro-Regular';">
		<tbody>
			<tr>
				<td width="56"></td>
				<td width="96"></td>
				<td width="90"></td>
				<td width="76"></td>
				<td width="146"></td>
				<td width="76"></td>
				<td width="86"></td>
				<td width="190"></td>
			</tr>
			<tr style="height: 22px;">
				<td><p class="no-margin">Fecha:</p></td>
				<td class="border-dotted-b"><p class="no-margin"><?php echo $t[0]['SimpleDate']; ?></p></td>
				<td><p class="no-margin" style="padding-left: 10px;">Dependencia:</p></td>
				<td colspan="2" class="border-dotted-b" style="height: 31px"><p class="no-margin medium pd-left-10"><?php echo substr($t[0]['Dependencia'], 0, 30).'<br>'.substr($t[0]['Dependencia'], 30); ?></p></td>
				<td><p class="no-margin" style="padding-left: 21px;">Equipo:</p></td>
				<td colspan="2" class="border-dotted-b"><p class="no-margin pd-left-10"><?php echo $t[0]['Equipo']['TypeName']; ?></p></td>
			</tr>
			<tr style="height: 13px;"></tr>
			<tr style="height: 22px;">
				<td colspan="1"><p class="no-margin">Nombre:</p></td>
				<td colspan="4" class="border-dotted-b"><p class="no-margin medium pd-left-10"><?php echo $t[0]['UserName']; ?></p></td>
				<td colspan="2"><p class="no-margin" style="padding-left: 21px;">Nº de patrimonio:</p></td>
				<td class="border-dotted-b"><p class="no-margin medium pd-left-10"><?php echo $t[0]['Equipo']['Patrimony']; ?></p></td>
			</tr>
			<tr style="height: 13px;"></tr>
			<tr style="height: 22px;">
				<td colspan="1"><p class="no-margin">Legajo:</p></td>
				<td colspan="2" class="border-dotted-b"><p class="no-margin pd-left-10 medium"><?php echo $t[0]['Legajo']; ?></p></td>
				<td colspan="1"><p class="no-margin pd-left-10">Teléfono:</p></td>
				<td colspan="1" class="border-dotted-b"><p class="no-margin medium pd-left-10"><?php echo $t[0]['Telefono']; ?></p></td>
				<td colspan="2"><p class="no-margin" style="padding-left: 21px;">Nº de equipo:</p></td>
				<td colspan="1" class="border-dotted-b"><p class="no-margin medium pd-left-10"><?php echo $t[0]['Equipo']['Intern']; ?></p></td>
			</tr>
			<tr style="height: 13px;"></tr>
			<tr style="height: 22px;">
				<td colspan="1"><p class="no-margin">Modelo:</p></td>
				<td colspan="2" class="border-dotted-b"><p class="no-margin pd-left-10 medium"><?php echo $t[0]['Equipo']['Brand']; ?></p></td>
				<td colspan="1"><p class="no-margin" style="padding-left: 20px;">Marca:</p></td>
				<td colspan="1" class="border-dotted-b"><p class="no-margin medium pd-left-10"><?php echo $t[0]['Equipo']['Model']; ?></p></td>
				<td colspan="2"><p class="no-margin" style="padding-left: 21px;">Nº de serie:</p></td>
				<td colspan="1" class="border-dotted-b"><p class="no-margin medium pd-left-10"><?php echo ''; ?></p></td>
			</tr>
			<tr style="height: 13px;"></tr>
			<tr style="height: 30px;">
				<td colspan="2" class=""> <p class="no-margin">Falla técnica: </p></td>
				<td colspan="6" class="border-dotted-b"><?php echo substr($t[0]['TecnicFailure'], 0, 72); ?></td>
			</tr>
			<tr style="height: 30px;">
				<td colspan="8" class="border-dotted-b"><?php echo substr($t[0]['TecnicFailure'], 72); ?></td>
			</tr>
			<tr style="height: 4px;"></tr>
			<tr style="height: 22px;">
				<td colspan="8"><p class="" style="font-size: 12px; font-family: DIN Next LT Pro Bold; padding-top: 1px;">Informamos que el área de sistemas , no se responsabiliza de la información contenida dentro del equipo , retiro y entrega del mismo. </p></td>
			</tr>
			<tr style="height: 13px;"></tr>
		</tbody>
	</table>
	<table cellpadding=0 cellspacing=0 style="width: 719px;font: 15px 'DINNextLTPro-Regular';">
		<tbody>
			<tr>
				<td width="68"></td>
				<td width="175"></td>
				<td width="21"></td>
				<td width="21"></td>
				<td width="94"></td>
				<td width="91"></td>
				<td width="21"></td>
				<td width="21"></td>
				<td width="55"></td>
				<td width="40"></td>
				<td width="100"></td>
			</tr>
			<tr style="height: 22px;">
				<td ><p class="no-margin">TÉCNICO:</p></td>
				<td colspan="" class="border-dotted-b text-align-center"><p class="no-margin"><?php echo $t[0]['Encargado']; ?> </p></td>
				<td colspan="1" class=" border-r"><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td colspan="2" class=""><p></p></td>
				<td colspan="1" class=" border-r"><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td colspan="2"><p class="no-margin">Fecha de retiro:</p></td>
				<td class="border-dotted-b"><p class="no-margin pd-left-10"><?php echo $t[0]['SimpleDateRetiro']; ?></p></td>
			</tr>
			<tr style="height: 22px;">
				<td></td>
				<td colspan="" class=""><p></p></td>
				<td colspan="1" class=" border-r"><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td colspan="2" class=" "><p></p></td>
				<td colspan="1" class=" border-r"><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td ><p class="no-margin">Nombre:</p></td>
				<td colspan="2" class="border-dotted-b"><p class="no-margin pd-left-10"><?php echo $t[0]['UserRetiro']['Name']; ?></p></td>
			</tr>
			<tr style="height: 22px;">
				<td></td>
				<td colspan="" class="border-dotted-b"><p></p></td>
				<td colspan="1" class=" border-r"><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td colspan="2" class=" border-dotted-b"><p></p></td>
				<td colspan="1" class=" border-r"><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td ><p class="no-margin">Apellido:</p></td>
				<td colspan="2" class="border-dotted-b"><p class="no-margin pd-left-10"><?php echo $t[0]['UserRetiro']['Surname']; ?></p></td>
			</tr>
			<tr style="height: 22px;">
				<td ></td>
				<td colspan="" class="text-align-center"><p class="no-margin small">Firma y aclaración:</p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td colspan="2" class="text-align-center"><p class="no-margin small">Firma responsable de entrega</p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td colspan="1" class=" "><p class="no-margin"></p></td>
				<td ><p class="no-margin">Firma:</p></td>
				<td colspan="2" class="border-dotted-b"><p class="no-margin"></p></td>
			</tr>
			
		</tbody>
	</table>
	<div style="width:100%; border-top: dotted; border-width: thin; margin-top: 7px; padding-bottom: 13px;"></div>
	<?php } ?>
		<button type="button" class="no-print" onclick="window.print();">Imprimir</button>
</DIV>
</DIV>
</DIV>
<script type="text/javascript">
	window.onload = function() { window.print();}
	// window.print();
// window.onfocus=function(){ window.close();}
</script>
</BODY>
</HTML>
