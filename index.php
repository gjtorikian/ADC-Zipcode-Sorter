<html>
<body>
<h1>ADC Sorter</h1>
<p>This web form will accept a CSV file containing zip codes, and append a new column with its ADC ranking.</p>
<p>For more information, including the source code, see <a href="https://github.com/gjtorikian/ADC-Zipcode-Sorter">the GitHub project</a>.</p>
<p>
<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Select the CSV file:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>
</p>
</body>
</html>