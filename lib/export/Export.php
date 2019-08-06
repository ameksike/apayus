<?php
	require_once dirname(__FILE__).'/lib/PHPExcel.php';
	require_once dirname(__FILE__).'/lib/PHPExcel/IOFactory.php';
	
	class Export {
		protected $phpexcel;
		protected $writer;
		protected $reader;
		public function __construct(){

		}
		
		public function toEcxel($data, $xlsx){
			$this->phpexcel = new PHPExcel();			
			$this->phpexcel->getProperties()->setCreator("Apayus.CS")
								 ->setLastModifiedBy("Apayus.CS")
								 ->setTitle("Office 2007 XLSX Document")
								 ->setSubject("Office 2007 XLSX Document")
								 ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 php")
								 ->setCategory("Reportes");
								 
			$this->reader = PHPExcel_IOFactory::createReader('Excel2007');
			$this->phpexcel = $this->reader->load($xlsx);
			
			$total = count($data); 
			foreach($data as $i=>$row)
			{
				$j = $i + 2;
				$this->phpexcel->getActiveSheet()->setCellValue('A' . $j, $i+1);
				$this->phpexcel->getActiveSheet()->setCellValue('B' . $j, $row["nombretitular"]);
				$this->phpexcel->getActiveSheet()->setCellValue('C' . $j, $row["telefonotitular"]);
				$this->phpexcel->getActiveSheet()->setCellValue('D' . $j, $row["emailtitular"]);
				$this->phpexcel->getActiveSheet()->setCellValue('E' . $j, $row["nombreconyuge"]);
				$this->phpexcel->getActiveSheet()->setCellValue('F' . $j, $row["fechareserva"]);
				$this->phpexcel->getActiveSheet()->setCellValue('G' . $j, $row["hora"]);
				$this->phpexcel->getActiveSheet()->setCellValue('H' . $j, $row["vencimiento"]);
				$this->phpexcel->getActiveSheet()->setCellValue('I' . $j, $row["direccion"]);
				$this->phpexcel->getActiveSheet()->setCellValue('J' . $j, $row["mesa"]);
				$this->phpexcel->getActiveSheet()->setCellValue('K' . $j, $row["date"]);
				
			}
			$this->writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
			$time = time();			
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel2007');
			header('Content-Disposition: attachment;filename="Informe'.$time.'.xlsx"');
			header('Cache-Control: max-age=1');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: no-cache'); // HTTP/1.0
			$this->writer->save('php://output');
			die;
		}
	}