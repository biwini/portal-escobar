<?php 
	require __DIR__.'/../'.'/vendor/autoload.php';

	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;

	class pdf{
		private $Body;
		private $Ticket;
		private $SendInMail;
		private $Download;


		private $ValidateFields;

		function __construct($t, $Download, $sendInMail = false){

			$this->Body = '';
			$this->Ticket = $t;
			$this->Download = $Download;
			$this->SendInMail = $sendInMail;

			$this->ValidPdf = true;
		}

		public function GetPdf(){
			if(!$this->ValidPdf){
				return '';
			}
			ob_start();

			$html2pdf = new Html2Pdf('P','A4','de',true,"UTF-8",array(0, 0, 0, 0)); 
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$this->Body = '
					<page>
						<page_header>
							<style type=\'text/css\'>
								<!--
								
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
									border-width: 0.396875mm;
								}
								.border-dotted-b{
									border-bottom: dotted;
									border-width: 0.396875mm;
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
									margin-top: 0mm;
								}

								.no-margin{
									margin-top: 0mm;
									margin-bottom: 0mm;
									white-space: nowrap;
								}

								.margin-2{
									margin-top: 0.5291666667mm;
									margin-bottom: 0.5291666667mm;
								}

								.pd-left-10{
									padding-left: 2.64mm;
								}

								.small{
									font-size: 2.91mm;
								}
								.medium{
									font-size: 3.43mm;
								}
								-->
							</style>
						</page_header>
						<page_footer> 
						</page_footer> 


						<div style=\'position:absolute;left:0;margin-left:0mm;top:0mm;width:210.34mm;height:222.5mm;border-style:outset;overflow:hidden\'>
							<div style=\'position:absolute;left:0mm;top:0mm\'>
								<img src=\'pdf.jpg\' width=816 height=1314 />
							</div>
							<table cellpadding=0 cellspacing=0 style=\'width: 210.34mm;font: 4.23mm;position:absolute;\'>
								<tr>
									<td style=\'width= 14.81mm\'></td>
									<td style=\'width= 25.4mm\'></td>
									<td style=\'width= 35.9mm\'></td>
									<td style=\'width= 43.9mm\'></td>
									<td style=\'width= 22.7mm\'></td>
									<td style=\'width= 33.3mm\'></td>
									<td style=\'width= 39.6mm\'></td>
								</tr>
								<thead>
									<tr>
										<th colspan=\'6\' style=\'margin-left: 5.2mm;text-align: left;\'><p class=\'\' style=\'font-size: 8.6mm;line-height: 8.9mm;\'>Dirección General de Sistemas</p></th>
										<th colspan=\'1\'><img src=\'images/logo-escobar.png\' alt=\'\' style=\'width=46mm; height=12mm\'></th>
									</tr>
								</thead>
							</table>
							<table cellpadding=0 cellspacing=0 style=\'width: 595mm;font: 15mm;\'>
								<tbody>
									<tr>
										<td width=\'56\'></td>
										<td width=\'96\'></td>
										<td width=\'90\'></td>
										<td width=\'76\'></td>
										<td width=\'146\'></td>
										<td width=\'76\'></td>
										<td width=\'86\'></td>
										<td width=\'190\'></td>
									</tr>
									<tr style=\'height: 22mm; \'>
										<td><p class=\'no-margin\'>Fecha:</p></td>
										<td class=\'border-dotted-b\'><p class=\'no-margin\'>'.$this->Ticket[0]['SimpleDate'].'</p></td>
										<td><p class=\'no-margin\' style=\'padding-left: 16mm;\'>Dependencia:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Dependencia'].'</p></td>
										<td><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Equipo:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['Equipo'].'</p></td>
									</tr>
									<tr style=\'height: 13mm; padding-top:500mm; margin-top:500mm\'></tr>
									<tr style=\'height: 22mm; \'>
										<td colspan=\'1\'><p class=\'no-margin\'>Nombre:</p></td>
										<td colspan=\'4\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['UserName'].'</p></td>
										<td colspan=\'2\'><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Nº de patrimonio:</p></td>
										<td class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Patrimony'].'</p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
									<tr style=\'height: 22mm;\'>
										<td colspan=\'1\'><p class=\'no-margin\'>Legajo:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10 medium\'>'.$this->Ticket[0]['Legajo'].'</p></td>
										<td colspan=\'1\'><p class=\'no-margin pd-left-10\'>Teléfono:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Telefono'].'</p></td>
										<td colspan=\'2\'><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Nº de equipo:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['NroEquipo'].'</p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
									<tr style=\'height: 22mm;\'>
										<td colspan=\'1\'><p class=\'no-margin\'>Modelo:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10 medium\'>'.$this->Ticket[0]['Modelo'].'</p></td>
										<td colspan=\'1\'><p class=\'no-margin\' style=\'padding-left: 20mm;\'>Marca:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Marca'].'</p></td>
										<td colspan=\'2\'><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Nº de serie:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.''.'</p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
									<tr style=\'height: 30mm;\'>
										<td colspan=\'2\' class=\'\'> <p class=\'no-margin\'>Falla técnica: </p></td>
										<td colspan=\'6\' class=\'border-dotted-b\'><p class=\'no-margin\'>'.substr($this->Ticket[0]['TecnicFailure'], 0, 72).'</p></td>
									</tr>
									<tr style=\'height: 30mm !important;\'>
										<td colspan=\'8\' class=\'border-dotted-b\'><p class=\'no-margin\'>'.substr($this->Ticket[0]['TecnicFailure'], 72).'</p></td>
									</tr>
									<tr style=\'height: 4mm;\'></tr>
									<tr style=\'height: 22mm;\'>
										<td colspan=\'8\'><p class=\'\' style=\'font-size: 12mm; padding-top: 1mm;\'>Informamos que el área de sistemas , no se responsabiliza de la información contenida dentro del equipo , retiro y entrega del mismo. </p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
								</tbody>
							</table>
							<table cellpadding=0 cellspacing=0 style=\'width: 595mm;font: 15mm;\'>
								<tbody>
									<tr>
										<td width=\'68\'></td>
										<td width=\'175\'></td>
										<td width=\'21\'></td>
										<td width=\'21\'></td>
										<td width=\'94\'></td>
										<td width=\'91\'></td>
										<td width=\'21\'></td>
										<td width=\'21\'></td>
										<td width=\'55\'></td>
										<td width=\'40\'></td>
										<td width=\'100\'></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td ><p class=\'no-margin\'>TÉCNICO:</p></td>
										<td colspan=\'\' class=\'border-dotted-b text-align-center\'><p class=\'no-margin\'>'.$this->Ticket[0]['Encargado'].' </p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\'\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\'><p class=\'no-margin\'>Fecha de retiro:</p></td>
										<td class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['SimpleDateRetiro'].'</p></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td></td>
										<td colspan=\'\' class=\'\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\' \'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td ><p class=\'no-margin\'>Nombre:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['UserRetiro']['Name'].'</p></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td></td>
										<td colspan=\'\' class=\'border-dotted-b\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\' border-dotted-b\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td ><p class=\'no-margin\'>Apellido:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['UserRetiro']['Surname'].'</p></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td ></td>
										<td colspan=\'\' class=\'text-align-center\'><p class=\'no-margin small\'>Firma y aclaración:</p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\'text-align-center\'><p class=\'no-margin small\'>Firma responsable de entrega</p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td ><p class=\'no-margin\'>Firma:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin\'></p></td>
									</tr>
								</tbody>
							</table>
							
							<table cellpadding=0 cellspacing=0 style=\'width: 595mm;font: 16mm; margin-top: 35mm\'>
								<tr>
									<td width=\'56\'></td>
									<td width=\'96\'></td>
									<td width=\'136\'></td>
									<td width=\'166\'></td>
									<td width=\'86\'></td>
									<td width=\'126\'></td>
									<td width=\'150\'></td>
								</tr>
								<thead>
									<tr style=\'height: 65mm;\'>
										<th colspan=\'6\' style=\'text-align: left;\'><p class=\'no-margin\' style=\'line-height: 34mm;\'>Dirección General de Sistemas</p></th>
										<th colspan=\'1\'><img src=\'images/logo-escobar.png\' alt=\'\' width=\'210\' height=\'65\'></th>
									</tr>
								</thead>
							</table>
							<table cellpadding=0 cellspacing=0 style=\'width: 595mm;font: 15mm;\'>
								<tbody>
									<tr>
										<td width=\'56\'></td>
										<td width=\'96\'></td>
										<td width=\'90\'></td>
										<td width=\'76\'></td>
										<td width=\'146\'></td>
										<td width=\'76\'></td>
										<td width=\'86\'></td>
										<td width=\'190\'></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td><p class=\'no-margin\'>Fecha:</p></td>
										<td class=\'border-dotted-b\'><p class=\'no-margin\'>'.$this->Ticket[0]['SimpleDate'].'</p></td>
										<td><p class=\'no-margin\' style=\'padding-left: 16mm;\'>Dependencia:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Dependencia'].'</p></td>
										<td><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Equipo:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['Equipo'].'</p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
									<tr style=\'height: 22mm;\'>
										<td colspan=\'1\'><p class=\'no-margin\'>Nombre:</p></td>
										<td colspan=\'4\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['UserName'].'</p></td>
										<td colspan=\'2\'><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Nº de patrimonio:</p></td>
										<td class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Patrimony'].'</p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
									<tr style=\'height: 22mm;\'>
										<td colspan=\'1\'><p class=\'no-margin\'>Legajo:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10 medium\'>'.$this->Ticket[0]['Legajo'].'</p></td>
										<td colspan=\'1\'><p class=\'no-margin pd-left-10\'>Teléfono:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Telefono'].'</p></td>
										<td colspan=\'2\'><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Nº de equipo:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['NroEquipo'].'</p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
									<tr style=\'height: 22mm;\'>
										<td colspan=\'1\'><p class=\'no-margin\'>Modelo:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10 medium\'>'.$this->Ticket[0]['Modelo'].'</p></td>
										<td colspan=\'1\'><p class=\'no-margin\' style=\'padding-left: 20mm;\'>Marca:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.$this->Ticket[0]['Marca'].'</p></td>
										<td colspan=\'2\'><p class=\'no-margin\' style=\'padding-left: 31mm;\'>Nº de serie:</p></td>
										<td colspan=\'1\' class=\'border-dotted-b\'><p class=\'no-margin medium pd-left-10\'>'.''.'</p></td>
									</tr>
									<tr style=\'height: 13mm;\'></tr>
									<tr style=\'height: 30mm;\'>
										<td colspan=\'2\' class=\'\'> <p class=\'no-margin\'>Falla técnica: </p></td>
										<td colspan=\'6\' class=\'border-dotted-b\'>'.substr($this->Ticket[0]['TecnicFailure'], 0, 72).'</td>
									</tr>
									<tr style=\'height: 30mm;\'>
										<td colspan=\'8\' class=\'border-dotted-b\'>'.substr($this->Ticket[0]['TecnicFailure'], 72).'</td>
									</tr>
									<tr style=\'height: 4mm;\'></tr>
									<tr style=\'height: 22mm;\'>
										<td colspan=\'8\'><p class=\'\' style=\'font-size: 12mm; padding-top: 1mm;\'>Informamos que el área de sistemas , no se responsabiliza de la información contenida dentro del equipo , retiro y entrega del mismo. </p></td>
									</tr>
								</tbody>
							</table>

							<table cellpadding=0 cellspacing=0 style=\'width: 595mm;font: 15mm;\'>
								<tbody>
									<tr>
										<td width=\'68\'></td>
										<td width=\'175\'></td>
										<td width=\'21\'></td>
										<td width=\'21\'></td>
										<td width=\'94\'></td>
										<td width=\'91\'></td>
										<td width=\'21\'></td>
										<td width=\'21\'></td>
										<td width=\'55\'></td>
										<td width=\'40\'></td>
										<td width=\'100\'></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td ><p class=\'no-margin\'>TÉCNICO:</p></td>
										<td colspan=\'\' class=\'border-dotted-b text-align-center\'><p class=\'no-margin\'>'.$this->Ticket[0]['Encargado'].' </p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\'\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\'><p class=\'no-margin\'>Fecha de retiro:</p></td>
										<td class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['SimpleDateRetiro'].'</p></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td></td>
										<td colspan=\'\' class=\'\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\' \'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td ><p class=\'no-margin\'>Nombre:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['UserRetiro']['Name'].'</p></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td></td>
										<td colspan=\'\' class=\'border-dotted-b\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\' border-dotted-b\'><p></p></td>
										<td colspan=\'1\' class=\' border-r\'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td ><p class=\'no-margin\'>Apellido:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin pd-left-10\'>'.$this->Ticket[0]['UserRetiro']['Surname'].'</p></td>
									</tr>
									<tr style=\'height: 22mm;\'>
										<td ></td>
										<td colspan=\'\' class=\'text-align-center\'><p class=\'no-margin small\'>Firma y aclaración:</p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'2\' class=\'text-align-center\'><p class=\'no-margin small\'>Firma responsable de entrega</p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td colspan=\'1\' class=\' \'><p class=\'no-margin\'></p></td>
										<td ><p class=\'no-margin\'>Firma:</p></td>
										<td colspan=\'2\' class=\'border-dotted-b\'><p class=\'no-margin\'></p></td>
									</tr>
								</tbody>
							</table>
						</DIV>
					</page>';
		      //   		    <div style=\"margin-left:38mm;margin-right:45mm; margin-bottom:0mm; margin-top:35%;\">
				    // 	<img src=\"../../imagenes/PDFFooter.png\" style=\"margin-right:45mm;\">
				    // </div>
			$html2pdf->writeHTML($this->Body);

			ob_end_clean();

			
			return $html2pdf->output('Vale de combustible.pdf');
			
		}
		// private function ValidateFields(){
		// 	if($this->NaftaPP != 0 && !empty($this->NRemito) && !empty($this->Proveedor) && !empty($this->Body)){
		// 		return true;
		// 	}else{
		// 		return false;
		// 	}
		// }
	}

?>