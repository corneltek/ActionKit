<?php
mb_internal_encoding('UTF-8');
require 'main.php';
require 'PHPExcel/PHPExcel.php';
require 'PHPExcel/PHPExcel/IOFactory.php';

$excel = new PHPExcel();
$excel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Name')
            ->setCellValue('B1', 'Address')
			->setCellValue('C1', 'Email');

foreach( range(1,30) as $row ) {
	$excel->getActiveSheet()
			->setCellValue('A' . $row, 'Pedro ' . $row )
            ->setCellValue('B' . $row, 'My Address ' . $row)
            ->setCellValue('C' . $row, "pedro.$row@corneltek.com")
            ;
}



// Rename sheet
echo date('H:i:s') . " Rename sheet\n";
$excel->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);


// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

#  echo date('H:i:s') . " Write to Excel5 format\n";
#  $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
#  $objWriter->save(str_replace('.php', '.xls', __FILE__));
