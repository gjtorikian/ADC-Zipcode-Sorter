# Introduction #

The United States Postal Office (USPS) offers huge discounts to bulk mailers that organize their mail, prior to having it sent through a post office. This is often done by grouping bundles of letters by the first three digits of the zip code. These digits refer to the area distribution center (ADC) as defined by the USPS. The USPS has [their guidelines posted online](http://pe.usps.com/text/dmm300/L004.htm).

Unfortunately, the arrangement of these ADC codes has no discernible pattern. There's no numerical or geographical sorting for any of the items.  For example, if I have mail to send to Long Island, NY, my grouping can be in any of the following slots:

> 005, 115, 117-119

And if I'm sending mail to Springfield, MA, my grouping occurs between these slots:

> 010 - 017

I work for a non-profit, and there was a need to sort the zip code information into the ADC arrangement, so that they could save on mailing costs. In addition, the USPS has rules that indicate if a package weighs a certain number of pounds, the equivalent number of labels must be added to the item. For example, if a package weighs three pounds, it needs three mailing labels.

This script assembles a CSV list that conforms to these rules.

# Prerequisites #

The script is written in Perl, and is intended for use on OS X. While I'm sure it'll work on other operating systems, I haven't tested it. It might need some slight modifications. You will also need the [Cwd library](http://search.cpan.org/~smueller/PathTools-3.33/Cwd.pm).

If you want to be able to double-click the script in Finder, you might need to do the following:

1. Right-click the script (or control-click it)
2. Select **Get Info**
3. Under the Permissions section, make sure to click **Execute** next to Owner, Group, and Everyone.

Otherwise, you can just run the script from Terminal.

As input, the script takes a CSV file. At minimum, it assumes you have a column with the words "Zip Code." If you want to work with multiple labels for items weighing more than a pound, you need to have a column with the word "Pounds." 


# Using the script #

The script relies on a file called _adc\_listings.txt_, which lists all the ADC numbers in the same ranking as the USPS (as of 04/17/11).

Run _adc\_zipcode\_sorter.command_. It'll ask for a CSV file. Drag a CSV file into the script, and hit enter.

You're done! The output file is named _adc\_sorted\_file.csv_. This CSV has a new row added to the end of the called SortOrder. When importing this CSV file into a database, spreadsheet, or label making program, you can choose to sort on the SortOrder column, and get an ADC-arranged list of mailing addresses.

# Test CSV #

A dummy csv, _test\_sheet.csv_, is proved for your fun and entertainment in trying out the script. It contains data that's missing, data that's hyphenated, and  data that falls within an ADC range.
