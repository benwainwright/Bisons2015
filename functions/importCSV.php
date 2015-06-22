<?php
/**
 *
 * Parse a CSV file into an array containing the row headers as array keys in each row
 *
 * @param $csvFileLocation string the location of the CSV file to open
 *
 * @throws Exception if file fails to open
 *
 * @return array
 */
function importCSV($csvFileLocation) {

	if (false !== ($file = fopen($csvFileLocation, 'r'))) {

		$array = array();
		$rowHeaders = array();

		for($i=0; false !== ($csvFileLine = fgetcsv($file)); $i++) {

			if (0 === $i) {
				foreach($csvFileLine as $header) {
					$rowHeaders[] = $header;
				}
			}

			else {
				foreach($csvFileLine as $key => $data) {
					$array[$i-1][$rowHeaders[$key]] = $data;
				}
			}
		}
		return $array;
	}

	else {
		throw new Exception('Failed to open CSV file');
	}
}

