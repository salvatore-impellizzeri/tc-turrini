<?php
$this->disableAutoLayout();	
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=Customers_Export.csv');
echo "\xEF\xBB\xBF"; // UTF-8 BOM


$fp = fopen('php://output', 'wb');
$data = [];
array_push($data, $defaultLanguage['locale']);
foreach($otherLanguages as $otherLanguage) {
	array_push($data, $otherLanguage['locale']);
}
fputcsv($fp, $data);	

foreach($defaultLanguage['entries'] as $key => $entry) {
	$data = [];
	if(empty($entry->getMsgCtxt())) {
		array_push($data, $entry->getMsgStr());
		foreach($otherLanguages as $otherLanguage) {
			$otherEntry = $otherLanguage['entries'][$key];
			array_push($data, $otherEntry->getMsgStr());
		}
		fputcsv($fp, $data);
	}	
}	
fclose($fp);

?>