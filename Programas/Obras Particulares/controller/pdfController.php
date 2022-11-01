<?php 
	require __DIR__.'/../'.'/vendor/autoload.php';

	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;

	class pdf{
		private $Body;
		private $tipoLiq;
		private $fecha;
		private $cliente;
		private $nomenclatura;
		private $zonificacion;
		private $contratoColegio;
		private $multas;
		private $descuento;
        private $total;
        private $smMunicipal;
		private $Download;

		private $ValidateFields;

		function __construct($Tipo, $Fecha, $Cliente, $Nomenclatura, $Zon, $Cg, $Multas, $Descuento, $Total, $smMunicipal, $Download){

			$this->Body = '';
			$this->tipoLiq = $Tipo;
			$this->Fecha = $Fecha;
			$this->Patente = $Cliente;
			$this->nomenclatura = $Nomenclatura;
			$this->zonificacion = $Zon;
			$this->contratoColegio = $Cg;
			$this->multas = $Multas;
			$this->descuento = $Descuento;
			$this->total = $Total;
            $this->smMunicipal = $smMunicipal;
            $this->Download = $Download;

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
                                span.cls_003{font-family:\"\",serif;font-size:18.3px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                div.cls_003{font-family:\"\",serif;font-size:18.3px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                span.cls_004{font-family:\"\",serif;font-size:8.7px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                div.cls_004{font-family:\"\",serif;font-size:8.7px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                span.cls_005{font-family:\"\",serif;font-size:10.4px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                div.cls_005{font-family:\"\",serif;font-size:10.4px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                span.cls_006{font-family:\"\",serif;font-size:8.2px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                div.cls_006{font-family:\"\",serif;font-size:8.2px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                span.cls_012{font-family:\"\",serif;font-size:14.7px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                div.cls_012{font-family:\"\",serif;font-size:14.7px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                span.cls_002{font-family:\"\",serif;font-size:5.8px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                div.cls_002{font-family:\"\",serif;font-size:5.8px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                span.cls_007{font-family:\"\",serif;font-size:11.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                div.cls_007{font-family:\"\",serif;font-size:11.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                span.cls_008{font-family:\"\",serif;font-size:8.2px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                div.cls_008{font-family:\"\",serif;font-size:8.2px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                span.cls_010{font-family:\"\",serif;font-size:8.7px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                div.cls_010{font-family:\"\",serif;font-size:8.7px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                span.cls_011{font-family:\"\",serif;font-size:7.2px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                div.cls_011{font-family:\"\",serif;font-size:7.2px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
                                span.cls_009{font-family:\"\",serif;font-size:7.2px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                div.cls_009{font-family:\"\",serif;font-size:7.2px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
                                -->
                            </style>
						</page_header>
						<page_footer> 
						</page_footer> 

						<div style=\"position:absolute;left:50%;margin-left:-306px;top:0px;width:612px;height:792px;overflow:hidden\">
                            <div style=\"position:absolute;left:0px;top:0px\">
                            <img src=\"img/background1.jpg\" width=612 height=792></div>
                            <div style=\"position:absolute;left:273.22px;top:60.74px\" class=\"cls_003\"><span class=\"cls_003\">MUNICIPALIDAD DE ESCOBAR</span></div>
                            <div style=\"position:absolute;left:291.96px;top:84.50px\" class=\"cls_004\"><span class=\"cls_004\">SECRETARIA DE PLANIFICACIÓN E INFRAESTRUCTURA</span></div>
                            <div style=\"position:absolute;left:320.28px;top:94.82px\" class=\"cls_004\"><span class=\"cls_004\">DIRECCIÓN DE OBRAS PARTICULARES</span></div>
                            <div style=\"position:absolute;left:200.71px;top:113.81px\" class=\"cls_005\"><span class=\"cls_005\">LIQUIDACIÓN DE DERECHOS DE CONSTRUCCIÓN (CAP. IX)</span></div>
                            <div style=\"position:absolute;left:177.67px;top:131.33px\" class=\"cls_006\"><span class=\"cls_006\">FECHA</span></div>
                            <div style=\"position:absolute;left:368.78px;top:132.05px\" class=\"cls_006\"><span class=\"cls_006\">11/6/2019</span></div>
                            <div style=\"position:absolute;left:152.93px;top:154.85px\" class=\"cls_006\"><span class=\"cls_006\">NOMBRE Y APELLIDO</span></div>
                            <div style=\"position:absolute;left:313.56px;top:150.77px\" class=\"cls_012\"><span class=\"cls_012\">CÁCERES N. / MUSSO C.</span></div>
                            <div style=\"position:absolute;left:269.38px;top:191.09px\" class=\"cls_004\"><span class=\"cls_004\">NOMENCLATURA CATASTRAL</span></div>
                            <div style=\"position:absolute;left:149.33px;top:206.23px\" class=\"cls_002\"><span class=\"cls_002\">CIRC.</span></div>
                            <div style=\"position:absolute;left:209.83px;top:205.75px\" class=\"cls_002\"><span class=\"cls_002\">SECCION</span></div>
                            <div style=\"position:absolute;left:272.98px;top:206.23px\" class=\"cls_002\"><span class=\"cls_002\">FRACCION</span></div>
                            <div style=\"position:absolute;left:338.76px;top:206.23px\" class=\"cls_002\"><span class=\"cls_002\">CHACRA</span></div>
                            <div style=\"position:absolute;left:439.37px;top:205.75px\" class=\"cls_002\"><span class=\"cls_002\">PARTIDA</span></div>
                            <div style=\"position:absolute;left:149.57px;top:215.35px\" class=\"cls_007\"><span class=\"cls_007\">XII</span></div>
                            <div style=\"position:absolute;left:204.79px;top:214.87px\" class=\"cls_007\"><span class=\"cls_007\">RURAL</span></div>
                            <div style=\"position:absolute;left:145.97px;top:231.67px\" class=\"cls_002\"><span class=\"cls_002\">QUINTA</span></div>
                            <div style=\"position:absolute;left:207.67px;top:231.19px\" class=\"cls_002\"><span class=\"cls_002\">MANZANA</span></div>
                            <div style=\"position:absolute;left:274.66px;top:231.67px\" class=\"cls_002\"><span class=\"cls_002\">PARCELA</span></div>
                            <div style=\"position:absolute;left:345.48px;top:231.67px\" class=\"cls_002\"><span class=\"cls_002\">UF</span></div>
                            <div style=\"position:absolute;left:419.93px;top:225.19px\" class=\"cls_012\"><span class=\"cls_012\">26001371</span></div>
                            <div style=\"position:absolute;left:263.38px;top:240.79px\" class=\"cls_007\"><span class=\"cls_007\">2952 GOL</span></div>
                            <div style=\"position:absolute;left:340.20px;top:240.79px\" class=\"cls_007\"><span class=\"cls_007\">371</span></div>
                            <div style=\"position:absolute;left:295.80px;top:266.71px\" class=\"cls_004\"><span class=\"cls_004\">ZONIFICACIÓN</span></div>
                            <div style=\"position:absolute;left:133.97px;top:279.91px\" class=\"cls_002\"><span class=\"cls_002\">RURAL/COMPLEM</span></div>
                            <div style=\"position:absolute;left:269.86px;top:279.91px\" class=\"cls_002\"><span class=\"cls_002\">RESIDENCIAL</span></div>
                            <div style=\"position:absolute;left:210.07px;top:283.75px\" class=\"cls_002\"><span class=\"cls_002\">URBANA</span></div>
                            <div style=\"position:absolute;left:328.92px;top:283.75px\" class=\"cls_002\"><span class=\"cls_002\">CLUB DE CAMPO</span></div>
                            <div style=\"position:absolute;left:138.05px;top:288.58px\" class=\"cls_002\"><span class=\"cls_002\">SEMIURB - IND</span></div>
                            <div style=\"position:absolute;left:267.46px;top:288.58px\" class=\"cls_002\"><span class=\"cls_002\">EXTRAURBANA</span></div>
                            <div style=\"position:absolute;left:444.89px;top:290.26px\" class=\"cls_002\"><span class=\"cls_002\">De7</span></div>
                            <div style=\"position:absolute;left:346.20px;top:297.70px\" class=\"cls_008\"><span class=\"cls_008\">X</span></div>
                            <div style=\"position:absolute;left:277.06px;top:321.70px\" class=\"cls_004\"><span class=\"cls_004\">CONTRATO DEL COLEGIO</span></div>
                            <div style=\"position:absolute;left:167.83px;top:336.58px\" class=\"cls_002\"><span class=\"cls_002\">MONTO DE OBRA</span></div>
                            <div style=\"position:absolute;left:275.62px;top:337.06px\" class=\"cls_002\"><span class=\"cls_002\">COEF. %</span></div>
                            <div style=\"position:absolute;left:337.08px;top:337.06px\" class=\"cls_002\"><span class=\"cls_002\">RECARGO</span></div>
                            <div style=\"position:absolute;left:442.01px;top:336.58px\" class=\"cls_002\"><span class=\"cls_002\">TOTAL</span></div>
                            <div style=\"position:absolute;left:143.81px;top:349.78px\" class=\"cls_002\"><span class=\"cls_002\">A/C OBRA</span></div>
                            <div style=\"position:absolute;left:203.11px;top:349.30px\" class=\"cls_002\"><span class=\"cls_002\">$ 7.518.896,00</span></div>
                            <div style=\"position:absolute;left:278.02px;top:349.78px\" class=\"cls_002\"><span class=\"cls_002\">1,00%</span></div>
                            <div style=\"position:absolute;left:345.00px;top:349.78px\" class=\"cls_002\"><span class=\"cls_002\">0%</span></div>
                            <div style=\"position:absolute;left:449.69px;top:349.78px\" class=\"cls_002\"><span class=\"cls_002\">75.188,96</span></div>
                            <div style=\"position:absolute;left:142.61px;top:362.50px\" class=\"cls_002\"><span class=\"cls_002\">A/C PILETA</span></div>
                            <div style=\"position:absolute;left:205.27px;top:362.02px\" class=\"cls_002\"><span class=\"cls_002\">$ 352.000,00</span></div>
                            <div style=\"position:absolute;left:278.02px;top:362.50px\" class=\"cls_002\"><span class=\"cls_002\">7,00%</span></div>
                            <div style=\"position:absolute;left:345.00px;top:362.50px\" class=\"cls_002\"><span class=\"cls_002\">0%</span></div>
                            <div style=\"position:absolute;left:449.69px;top:362.50px\" class=\"cls_002\"><span class=\"cls_002\">24.640,00</span></div>
                            <div style=\"position:absolute;left:213.19px;top:374.76px\" class=\"cls_002\"><span class=\"cls_002\">$ 5,00</span></div>
                            <div style=\"position:absolute;left:278.02px;top:375.24px\" class=\"cls_002\"><span class=\"cls_002\">5,00%</span></div>
                            <div style=\"position:absolute;left:345.00px;top:375.24px\" class=\"cls_002\"><span class=\"cls_002\">5%</span></div>
                            <div style=\"position:absolute;left:456.17px;top:375.24px\" class=\"cls_002\"><span class=\"cls_002\">0,26</span></div>
                            <div style=\"position:absolute;left:334.92px;top:389.16px\" class=\"cls_010\"><span class=\"cls_010\">SUBTOTAL (A)</span></div>
                            <div style=\"position:absolute;left:406.94px;top:390.60px\" class=\"cls_011\"><span class=\"cls_011\">$</span></div>
                            <div style=\"position:absolute;left:486.43px;top:390.60px\" class=\"cls_011\"><span class=\"cls_011\">99.829,22</span></div>
                            <div style=\"position:absolute;left:306.36px;top:415.08px\" class=\"cls_004\"><span class=\"cls_004\">MULTAS</span></div>
                            <div style=\"position:absolute;left:145.49px;top:430.44px\" class=\"cls_002\"><span class=\"cls_002\">MULTAS</span></div>
                            <div style=\"position:absolute;left:202.15px;top:430.44px\" class=\"cls_002\"><span class=\"cls_002\">( m2 )</span></div>
                            <div style=\"position:absolute;left:235.30px;top:430.44px\" class=\"cls_002\"><span class=\"cls_002\">CANT</span></div>
                            <div style=\"position:absolute;left:266.26px;top:430.44px\" class=\"cls_002\"><span class=\"cls_002\">S.M.MUNICIPAL</span></div>
                            <div style=\"position:absolute;left:333.24px;top:430.44px\" class=\"cls_002\"><span class=\"cls_002\">PORCENTAJE</span></div>
                            <div style=\"position:absolute;left:442.01px;top:429.96px\" class=\"cls_002\"><span class=\"cls_002\">TOTAL</span></div>
                            <div style=\"position:absolute;left:149.57px;top:443.16px\" class=\"cls_002\"><span class=\"cls_002\">F.O.S</span></div>
                            <div style=\"position:absolute;left:203.83px;top:443.16px\" class=\"cls_002\"><span class=\"cls_002\">5,00</span></div>
                            <div style=\"position:absolute;left:236.98px;top:443.16px\" class=\"cls_002\"><span class=\"cls_002\">1,00</span></div>
                            <div style=\"position:absolute;left:272.98px;top:443.16px\" class=\"cls_002\"><span class=\"cls_002\">$ 9.220,00</span></div>
                            <div style=\"position:absolute;left:339.96px;top:443.16px\" class=\"cls_002\"><span class=\"cls_002\">10,00%</span></div>
                            <div style=\"position:absolute;left:389.90px;top:442.20px\" class=\"cls_009\"><span class=\"cls_009\">$</span></div>
                            <div style=\"position:absolute;left:451.13px;top:443.16px\" class=\"cls_002\"><span class=\"cls_002\">4.610,00</span></div>
                            <div style=\"position:absolute;left:149.33px;top:455.90px\" class=\"cls_002\"><span class=\"cls_002\">F.O.T</span></div>
                            <div style=\"position:absolute;left:203.83px;top:455.90px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:236.98px;top:455.90px\" class=\"cls_002\"><span class=\"cls_002\">1,00</span></div>
                            <div style=\"position:absolute;left:272.98px;top:455.90px\" class=\"cls_002\"><span class=\"cls_002\">$ 9.220,00</span></div>
                            <div style=\"position:absolute;left:341.40px;top:455.90px\" class=\"cls_002\"><span class=\"cls_002\">0,00%</span></div>
                            <div style=\"position:absolute;left:389.90px;top:454.94px\" class=\"cls_009\"><span class=\"cls_009\">$</span></div>
                            <div style=\"position:absolute;left:456.17px;top:455.90px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:145.49px;top:468.62px\" class=\"cls_002\"><span class=\"cls_002\">RETIROS</span></div>
                            <div style=\"position:absolute;left:203.83px;top:468.62px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:236.98px;top:468.62px\" class=\"cls_002\"><span class=\"cls_002\">1,00</span></div>
                            <div style=\"position:absolute;left:272.98px;top:468.62px\" class=\"cls_002\"><span class=\"cls_002\">$ 9.220,00</span></div>
                            <div style=\"position:absolute;left:341.40px;top:468.62px\" class=\"cls_002\"><span class=\"cls_002\">0,00%</span></div>
                            <div style=\"position:absolute;left:389.90px;top:467.66px\" class=\"cls_009\"><span class=\"cls_009\">$</span></div>
                            <div style=\"position:absolute;left:456.17px;top:468.62px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:143.09px;top:481.34px\" class=\"cls_002\"><span class=\"cls_002\">DENSIDAD</span></div>
                            <div style=\"position:absolute;left:203.83px;top:481.34px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:236.98px;top:481.34px\" class=\"cls_002\"><span class=\"cls_002\">1,00</span></div>
                            <div style=\"position:absolute;left:272.98px;top:481.34px\" class=\"cls_002\"><span class=\"cls_002\">$ 9.220,00</span></div>
                            <div style=\"position:absolute;left:341.40px;top:481.34px\" class=\"cls_002\"><span class=\"cls_002\">0,00%</span></div>
                            <div style=\"position:absolute;left:389.90px;top:480.38px\" class=\"cls_009\"><span class=\"cls_009\">$</span></div>
                            <div style=\"position:absolute;left:456.17px;top:481.34px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:139.97px;top:494.06px\" class=\"cls_002\"><span class=\"cls_002\">DTO.1281/14</span></div>
                            <div style=\"position:absolute;left:203.83px;top:494.06px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:236.98px;top:494.06px\" class=\"cls_002\"><span class=\"cls_002\">1,00</span></div>
                            <div style=\"position:absolute;left:272.98px;top:494.06px\" class=\"cls_002\"><span class=\"cls_002\">$ 9.220,00</span></div>
                            <div style=\"position:absolute;left:341.40px;top:494.06px\" class=\"cls_002\"><span class=\"cls_002\">0,00%</span></div>
                            <div style=\"position:absolute;left:389.90px;top:493.10px\" class=\"cls_009\"><span class=\"cls_009\">$</span></div>
                            <div style=\"position:absolute;left:456.17px;top:494.06px\" class=\"cls_002\"><span class=\"cls_002\">0,00</span></div>
                            <div style=\"position:absolute;left:335.16px;top:507.98px\" class=\"cls_010\"><span class=\"cls_010\">SUBTOTAL (B)</span></div>
                            <div style=\"position:absolute;left:406.22px;top:509.42px\" class=\"cls_011\"><span class=\"cls_011\">$</span></div>
                            <div style=\"position:absolute;left:490.03px;top:509.42px\" class=\"cls_011\"><span class=\"cls_011\">4.610,00</span></div>
                            <div style=\"position:absolute;left:166.39px;top:536.57px\" class=\"cls_002\"><span class=\"cls_002\">SUBTOTAL (A) + (B)</span></div>
                            <div style=\"position:absolute;left:270.58px;top:537.05px\" class=\"cls_002\"><span class=\"cls_002\">DESCUENTO</span></div>
                            <div style=\"position:absolute;left:333.00px;top:536.57px\" class=\"cls_010\"><span class=\"cls_010\">TOTAL A</span></div>
                            <div style=\"position:absolute;left:406.94px;top:541.85px\" class=\"cls_010\"><span class=\"cls_010\">$</span></div>
                            <div style=\"position:absolute;left:480.17px;top:541.85px\" class=\"cls_010\"><span class=\"cls_010\">93.995,30</span></div>
                            <div style=\"position:absolute;left:126.29px;top:550.25px\" class=\"cls_002\"><span class=\"cls_002\">$</span></div>
                            <div style=\"position:absolute;left:224.74px;top:550.25px\" class=\"cls_002\"><span class=\"cls_002\">104.439,22</span></div>
                            <div style=\"position:absolute;left:276.58px;top:550.73px\" class=\"cls_002\"><span class=\"cls_002\">10,00%</span></div>
                            <div style=\"position:absolute;left:332.28px;top:547.85px\" class=\"cls_010\"><span class=\"cls_010\">ABONAR</span></div>
                            <div style=\"position:absolute;left:314.28px;top:576.41px\" class=\"cls_011\"><span class=\"cls_011\">2019</span></div>
                            <div style=\"position:absolute;left:137.81px;top:595.37px\" class=\"cls_002\"><span class=\"cls_002\">En el computo metrico de las edificaciones quedaran incluidos los espesores de muro, los aleros, galerias y las respectivas construcciones complementarias.</span></div>
                            <div style=\"position:absolute;left:132.05px;top:613.13px\" class=\"cls_002\"><span class=\"cls_002\">Los Derechos de Construccion se liquidaran en forma provisoria a la presentacion de los planos, debiendo realizarse su pago conjuntamente con la iniciación del</span></div>
                            <div style=\"position:absolute;left:307.08px;top:620.83px\" class=\"cls_002\"><span class=\"cls_002\">Expediente.</span></div>
                            <div style=\"position:absolute;left:259.30px;top:633.07px\" class=\"cls_002\"><span class=\"cls_002\">La liquidación será ratificada previo a la aprobación.</span></div>
                            <div style=\"position:absolute;left:124.85px;top:717.58px\" class=\"cls_011\"><span class=\"cls_011\">FIRMA DEL LIQUIDADOR (1)</span></div>
                            <div style=\"position:absolute;left:433.85px;top:717.58px\" class=\"cls_011\"><span class=\"cls_011\">FIRMA DEL LIQUIDADOR (2)</span></div>
                            </div>
					</page>";
		      //   		    <div style=\"margin-left:38px;margin-right:45px; margin-bottom:0px; margin-top:35%;\">
				    // 	<img src=\"../../imagenes/PDFFooter.png\" style=\"margin-right:45px;\">
				    // </div>
			$html2pdf->writeHTML($this->Body);

			ob_end_clean();
            return $this->Body;
			if($this->Download){
				return $html2pdf->output('LIQUIDACION '.$this->tipoLiq.'.pdf', 'D');
			}else{
				return $html2pdf->output('LIQUIDACION '.$this->tipoLiq.'.pdf');
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