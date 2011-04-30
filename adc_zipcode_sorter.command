#!/usr/bin/perl
use strict;
use Cwd qw(abs_path);

my $path = abs_path($0);
my $script_dir = substr($path, 0, rindex($path, "/"));

print "Drag the location of the CSV you want sorted: ";
my $file = <STDIN>;
chomp($file);

open (CSV, "<$file") or die $!;
my @csv = <CSV>;
close (CSV);

my $zipCodePos = -1;
my $numOfPoundsPos = -1;

my %linePosToZipCode = ();
my %linePosToPounds = ();

my @adcList = setADCList();
my %adcMappings = setADCMappings(@adcList);

my @headers = split(/,/, $csv[$0]);
for (my $h = 0; $h < @headers; $h++)
{
	if ($headers[$h] =~ m/Zip Code/)
	{
    	$zipCodePos = $h; # find out which column zip code is in
	}
	if ($headers[$h] =~ m/Pounds/)
	{
    	$numOfPoundsPos = $h; # find out which column pounds identifier is in
	}
}

my $i = 0;
for ($i = 1; $i < @csv; $i++) 
{
    my @tokens = split(/,/, $csv[$i]);
	my $actualZipCodePos = $zipCodePos;
	my $actualNumOfPoundsPos = $numOfPoundsPos;
	
	if (length(@tokens) < length(@headers)) # that means there's some null column info
	{
		my $columnDiff = length(@headers) - length(@tokens);
		$actualZipCodePos = $zipCodePos + $columnDiff;
		
		if ($actualNumOfPoundsPos >= 0)
		{
			$actualNumOfPoundsPos = $numOfPoundsPos + $columnDiff;
		}
	}
	
	my $zipCode = $tokens[$actualZipCodePos];
	my $numOfPounds = -1;
	
	if ($actualNumOfPoundsPos >= 0)
	{ 
		$numOfPounds = $tokens[$actualNumOfPoundsPos];
	}
	
	if (length($zipCode) < 5)
	{
		$zipCode = "0" . $zipCode;
	}
	
	$zipCode =~ s/\"//; # remove quotes around numbers like "05667-0048"
	
    $linePosToZipCode {$i} = $zipCode; # store the zip code of every row
	$linePosToPounds {$i} = $numOfPounds; # store the pound info of every row
}

my $outputCSV = $script_dir . "/" . "adc_sorted_file.csv";

open (CSV_OUT, ">$outputCSV") or die $!;

$csv[0] =~ s/\n$//;
print CSV_OUT $csv[0] . ",\"SortOrder\"" . "\n";

for (my $j = 1; $j < $i; $j++)
{
	my $zipCodeLine = $linePosToZipCode {$j};
	my $poundsLine = $linePosToPounds {$j};
	
	if ($poundsLine eq '' || $poundsLine <= 0)
	{
		$poundsLine = 1;
	}
	
	if ($zipCodeLine =~ m/^0/) # if it starts with a zero, wrap it in quotes
	{
		my $truncatedZipCodeLine = substr($zipCodeLine, 1, length($zipCodeLine));
		$csv[$j] =~ s/$truncatedZipCodeLine/\"$zipCodeLine\"/;
	}
	
	do
	{
		$csv[$j] =~ s/\n$//;
		print CSV_OUT $csv[$j] . "," . $adcMappings{substr($zipCodeLine, 0, 3)} ."\n";
	} while (--$poundsLine);
}

# maybe one day I will sort the CSV myself...
close (CSV_OUT); 

print "\n\nAll done! Press ENTER to exit...";
<STDIN>;

sub setADCMappings()
{
    my (@adcArray) = @_;
    my %mappings = ();
    
    my $sortOrder = 0;
    for my $adcs (@adcArray)
    {
        my @tokens = split(/,/, $adcs);
        foreach my $token (@tokens) {
			$token =~ s/^\s+//; #remove leading spaces
			$token =~ s/\s+$//; #remove trailing spaces
			
            if ($token =~ m/-/) # So, it's a range of numbers
            {
                my @range = split(/-/, $token);
                my $startRange = $range[0];
                my $endRange = $range[1];
                
                while ($startRange <= $endRange)
                {
                    $mappings{padKey($startRange)} = padSortOrder($sortOrder);
                    $startRange++;
                }
            }
            else # Just a lone number
            {
                $mappings{padKey($token)} = padSortOrder($sortOrder);
            }
            
        }

        $sortOrder++;
    }

	return %mappings;
}

sub padKey()
{
	my ($key) = @_;
	
	while ($key < 100 && length($key) < 3)
	{
		$key = "0" . $key;
	}
	
	return $key;
}

sub padSortOrder()
{
	my ($so) = @_;
	
	if ($so < 10)
	{
		$so = "00" . $so;
	}
	elsif ($so < 99)
	{
		$so = "0" . $so;
	}
	
	return $so;
}
sub setADCList()
{
	my $adc_file = $script_dir . "/" . "adc_listings.txt";

	open (ADC, "<$adc_file") or die $!;
	my @adcs = <ADC>;
	close (ADC);
    
    return @adcs;
}