<?php 
	require __DIR__.'/../'.'/vendor/autoload.php';

	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;

	class pdf{
		private $Body;
		private $NRemito;
		private $Proveedor;
		private $Patente;
		private $Oc;
		private $Nafta;
		private $NaftaPP;
		private $Gas;
		private $GasPE;
		private $FechaRemito;
		private $AnioRemito;
		private $SendInMail;


		private $ValidateFields;

		function __construct($NRemito, $Proveedor, $Patente, $Oc, $Nafta, $NaftaPP, $Gas, $GasPE, $FechaRemito, $Download, $sendInMail = false){
			date($FechaRemito);

			$this->Body = '';
			$this->NRemito = $NRemito;
			$this->Proveedor = $Proveedor;
			$this->Patente = $Patente;
			$this->Oc = $Oc;
			$this->Nafta = $Nafta;
			$this->NaftaPP = $NaftaPP;
			$this->Gas = $Gas;
			$this->GasPE = $GasPE;
			$this->FechaRemito = date('d/m');
			$this->AnioRemito = date('Y');
			$this->Download = $Download;
			$this->SendInMail = $sendInMail;

			$this->ValidPdf = true;
		}

		public function GetPdf(){
			if(!$this->ValidPdf){
				return '';
			}
			ob_start();

			$html2pdf = new Html2Pdf('P','A4','de',true,"UTF-8",array(16, 16, 16, 16)); 
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$this->Body = "
					<page>
						<page_header>
							<style type=\"text/css\">
								<!--
								span.cls_003{font-family:\"times\",serif;font-size:22.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
								div.cls_003{font-family:\"times\",serif;font-size:22.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
								span.cls_005{font-family:\"times\",serif;font-size:14.0px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
								div.cls_005{font-family:\"times\",serif;font-size:14.0px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
								span.cls_006{font-family:\"times\",serif;font-size:24.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
								div.cls_006{font-family:\"times\",serif;font-size:24.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
								.space{margin-left:30px;margin-top:5px;}
								-->
							</style>
						</page_header>
						<page_footer> 
						</page_footer> 

						<div style=\"position:absolute;left:50%;margin-left:-297px;top:0px;width:595px;height:841px;border-style:outset;overflow:hidden\">
							<div style=\"position:absolute;left:0px;top:0px\">
							<img src=\"http://192.168.122.180/portal-escobar/Programas/combustible/img/background1.jpg\" width=595 height=841 ></div>
							<div style=\"position:absolute;left:143.81px;top:75.90px\" class=\"cls_003\"><span class=\"cls_003\">MUNICIPALIDAD DE ESCOBAR</span></div>
							<div style=\"position:absolute;left:194.71px;top:103.74px\" class=\"cls_005\"><span class=\"cls_005\">Provincia de Buenos Aires</span></div>
							<div style=\"position:absolute;left:456.91px;top:106.17px\" class=\"cls_006\"><span class=\"cls_006\">Nº ".$this->NRemito."</span></div>
							<div style=\"position:absolute;left:19.92px;top:153.69px\" class=\"cls_007\"><span class=\"cls_007\">Proveedor <span class=\"space\">".$this->Proveedor."</span></span></div>
							<div style=\"position:absolute;left:19.20px;top:208.43px\" class=\"cls_007\"><span class=\"cls_007\">Vehículo <span class=\"space\">".$this->Patente."</span></span></div>
							<div style=\"position:absolute;left:19.20px;top:243.23px\" class=\"cls_007\"><span class=\"cls_007\">O/C Nº <span class=\"space\">".$this->Oc."</span></span></div>
							<div style=\"position:absolute;left:19.20px;top:277.79px\" class=\"cls_007\"><span class=\"cls_007\">Vale por Lts <span class=\"space\">".$this->Nafta."</span></span></div>
							<div style=\"position:absolute;left:260.74px;top:277.79px\" class=\"cls_007\"><span class=\"cls_007\">de Nafta</span></div>
							<div style=\"position:absolute;left:19.20px;top:297.98px\" class=\"cls_007\"><span class=\"cls_007\">Vale por Lts <span class=\"space\">".$this->NaftaPP."</span></span></div>
							<div style=\"position:absolute;left:260.74px;top:297.98px\" class=\"cls_007\"><span class=\"cls_007\">de Nafta - V Power / Premium</span></div>
							<div style=\"position:absolute;left:19.20px;top:317.90px\" class=\"cls_007\"><span class=\"cls_007\">Vale por Lts <span class=\"space\">".$this->Gas."</span></span></div>
							<div style=\"position:absolute;left:260.74px;top:317.90px\" class=\"cls_007\"><span class=\"cls_007\">de Gas - Oil</span></div>
							<div style=\"position:absolute;left:19.20px;top:338.06px\" class=\"cls_007\"><span class=\"cls_007\">Vale por Lts <span class=\"space\">".$this->GasPE."</span></span></div>
							<div style=\"position:absolute;left:260.74px;top:338.06px\" class=\"cls_007\"><span class=\"cls_007\">de Gas - Oil - V Power / Euro Diesel</span></div>
							<div style=\"position:absolute;left:19.20px;top:372.62px\" class=\"cls_007\"><span class=\"cls_007\">Belén de Escobar <span class=\"space\">".$this->FechaRemito."</span></span></div>
							<div style=\"position:absolute;left:261.94px;top:372.62px\" class=\"cls_007\"><span class=\"cls_007\">de <span class=\"space\">".$this->AnioRemito."</span></span></div>
							<div style=\"position:absolute;left:490.03px;top:453.76px\" class=\"cls_007\"><span class=\"cls_007\">Firma</span></div>
						</div>
					</page>";
		      //   		    <div style=\"margin-left:38px;margin-right:45px; margin-bottom:0px; margin-top:35%;\">
				    // 	<img src=\"../../imagenes/PDFFooter.png\" style=\"margin-right:45px;\">
				    // </div>
			$html2pdf->writeHTML($this->Body);

			ob_end_clean();

			if($this->Download){
				return $html2pdf->output('Vale de combustible Nº '.$this->NRemito.'.pdf', 'D');
			}else{
				return ($this->SendInMail) ? $html2pdf->output('Vale de combustible Nº '.$this->NRemito.'.pdf', 'S') : $html2pdf->output('Vale de combustible Nº '.$this->NRemito.'.pdf');
			}
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