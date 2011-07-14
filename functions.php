<?php

function padKey($key)
{
	while ($key < 100 && strlen($key) < 3)
	{
		$key = "0" . $key;
	}

	return $key;
}

function padSortOrder($so)
{
	if ($so < 10)
	{
		$so = "00" . $so;
	}
	elseif ($so < 99)
	{
		$so = "0" . $so;
	}

	return $so;
}

function setADCList()
{
	$adc_file = getcwd() . "/" . "adc_listings.txt";

	return file($adc_file);
}

function setADCMappings($adcArray)
{
    $mappings;
    $sortOrder = 0;

    foreach ($adcArray as &$adcs)
    {
        $tokens = split(",", $adcs);
        foreach ($tokens as &$token) {
			$token = trim($token); 

            if (strpos($token, "-") > 0) # So, it's a range of numbers
            {
                $range = split("-", $token);
                $startRange = $range[0];
                $endRange = $range[1];
                
                while ($startRange <= $endRange)
                {
                    $mappings[padKey($startRange)] = padSortOrder($sortOrder);
                    $startRange++;
                }
            }
            else # Just a lone number
            {
                $mappings[padKey($token)] = padSortOrder($sortOrder);
            }
            
        }

        $sortOrder++;
    }

	return $mappings;
}

function string_begins_with($string, $search)
{
    return (strncmp($string, $search, strlen($search)) == 0);
}
?>