<html>
<body>
<?php
include("functions.php");

$allowedExtensions = array("csv"); 
foreach ($_FILES as $file) { 
	if ($file['tmp_name'] > '') { 
    	if (!in_array(end(explode(".", 
           strtolower($file['name']))), 
           $allowedExtensions)) { 
      		die($file['name'].' is an invalid file type!<br/><br/>'. 
       			'<a href="javascript:history.go(-1);">'. 
       			'&lt;&lt Go Back and try again.</a>'); 
     		} 
	} 
}

if ($_FILES["file"]["error"] > 0)
{
	echo "Error: " . $_FILES["file"]["error"] . "<br />";
}
else
{
	$target_path = getcwd() . "/" . basename( $_FILES['file']['name']); 

	if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
    	$csv = file($_FILES["file"]["name"]);
		
		$zipCodePos = -1;
		$numOfPoundsPos = -1;

		$linePosToZipCode;
		$linePosToPounds;
		
		$adcList = setADCList();
		$adcMappings = setADCMappings($adcList);

		$headers = split(",", $csv[0]);
		for ($h = 0, $size = sizeof($headers); $h < $size; $h++)
		{
			if (strpos($headers[$h], "Zip Code") > 0)
			{
		    	$zipCodePos = $h; # find out which column zip code is in
			}
			if (strpos($headers[$h], "Pounds") > 0)
			{
		    	$numOfPoundsPos = $h; # find out which column pounds identifier is in
			}
		}
		
		$i = 0;
		for ($i = 1, $size = sizeof($csv); $i < $size; $i++) 
		{
		    $tokens = split(",", $csv[$i]);
			$actualZipCodePos = $zipCodePos;
			$actualNumOfPoundsPos = $numOfPoundsPos;

			if (strlen($tokens) < strlen($headers)) # that means there's some null column info
			{
				$columnDiff = strlen($headers) - strlen($tokens);
				$actualZipCodePos = $zipCodePos + $columnDiff;

				if ($actualNumOfPoundsPos >= 0)
				{
					$actualNumOfPoundsPos = $numOfPoundsPos + $columnDiff;
				}
			}

			$zipCode = $tokens[$actualZipCodePos];
			$numOfPounds = -1;

			if ($actualNumOfPoundsPos >= 0)
			{ 
				$numOfPounds = $tokens[$actualNumOfPoundsPos];
			}
			
			if (strlen($zipCode) < 6)
			{
				$zipCode = "0" . $zipCode;
			}
			
			$zipCode = str_replace("\"", "", $zipCode); # remove quotes around numbers like "05667-0048"
	
		    $linePosToZipCode[$i] = $zipCode; # store the zip code of every row
			$linePosToPounds [$i] = $numOfPounds; # store the pound info of every row
		}
		
		$outputCSV = getcwd() . "/" . "adc_sorted_file.csv";
		$CSV_OUT = fopen($outputCSV, 'w');

		$csv[0] = rtrim($csv[0], "\n");
		
		fwrite($CSV_OUT, $csv[0] . ",\"SortOrder\"" . "\n");

		for ($j = 1; $j < $i; $j++)
		{
			$zipCodeLine = $linePosToZipCode[$j];
			$poundsLine = $linePosToPounds[$j];

			if (strcmp($poundsLine, '') == 0 || $poundsLine <= 0)
			{
				$poundsLine = 1;
			}

			if (string_begins_with($zipCodeLine, "0")) # if it starts with a zero, wrap it in quotes
			{
				$truncatedZipCodeLine = substr($zipCodeLine, 1, strlen($zipCodeLine));
				
				$csv[$j] = str_replace($truncatedZipCodeLine, "\"" . trim($zipCodeLine) . "\"", $csv[$j]);
			}

			do
			{
				$csv[$j] = rtrim($csv[$j], "\n");
				fwrite($CSV_OUT, $csv[$j] . "," . $adcMappings[substr($zipCodeLine, 0, 3)] ."\n");
			} while (--$poundsLine);
		}

		# maybe one day I will sort the CSV myself...
		fclose($CSV_OUT);
		
   }
} 
?>


<h1>All Done!</h1>
<h2>You can just <a href="adc_sorted_file.csv">click here</a> to retrieve the new sheet with the ADC sorting column.</h2>
</body>
</html>