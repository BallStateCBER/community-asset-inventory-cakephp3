<?php
/**
 * @var PHPExcel $spreadsheet,
 * @var string $writerType
 */
try {
    $objWriter = \PHPExcel_IOFactory::createWriter($spreadsheet, $writerType);
} catch (PHPExcel_Reader_Exception $e) {
    throw new \Cake\Http\Exception\InternalErrorException('Error reading spreadsheet: ' . $e->getMessage());
}
try {
    $objWriter->save('php://output');
} catch (PHPExcel_Writer_Exception $e) {
    throw new \Cake\Http\Exception\InternalErrorException('Error generating spreadsheet: ' . $e->getMessage());
}
