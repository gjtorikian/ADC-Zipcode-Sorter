# Introduction #

The United States Postal Office (USPS) offers huge discounts to bulk mailers that organize their mail, prior to having it sent through a post office. This is often done by grouping bundles of letters by the first three digits of the zip code. These digits refer to the area distribution center (ADC) as defined by the USPS. The USPS has [their guidelines posted online](http://pe.usps.com/text/LabelingLists/L004.htm).

Unfortunately, the arrangement of these ADC codes has no discernible pattern. There's no numerical or geographical sorting for any of the items.  For example, if I have mail to send to Long Island, NY, my grouping can be in any of the following slots:

> 005, 115, 117-119

And if I'm sending mail to Springfield, MA, my grouping occurs between these slots:

> 010 - 017

I volunteer for a non-profit, and there was a need to sort the zip code information into the ADC arrangement, so that they could save on mailing costs. In addition, the USPS has rules that indicate if a package weighs a certain number of pounds, the equivalent number of labels must be added to the item. For example, if a package weighs three pounds, it needs three mailing labels.

This script assembles a CSV list that conforms to these rules.

# Prerequisites #

The script is written in PHP, and can be used on any basic web server. Just make sure the script is executable with `chmod`.

As input, the PHP script takes a CSV file. At minimum, it assumes you have a column with the words "Zip Code." If you want to work with multiple labels for items weighing more than a pound, you need to have a column with the word "Pounds." 


# Using the script #

The PHP code relies on a file on the server called _adc\_listings.txt_, which lists all the ADC numbers in the same ranking as the USPS (as of 04/17/11).

After you upload your CSV file, you'll get a link to download the output file, which is named _adc\_sorted\_file.csv_. This CSV has a new row added to the end of the called SortOrder. When importing this CSV file into a database, spreadsheet, or label making program, you can choose to sort on the SortOrder column, and get an ADC-arranged list of mailing addresses.

# Testing #

A dummy file, _test\_sheet.csv_, is proved for your fun and entertainment in trying out the script. It contains data that's missing, data that's hyphenated, data that's misspelled, and data that falls within an ADC range.
