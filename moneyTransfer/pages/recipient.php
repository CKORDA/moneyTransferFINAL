<?php
$countries = array(
    'AF' => 'Afghanistan',
    'AX' => 'Åland Islands',
    'AL' => 'Albania',
    'CN' => 'China',
    'FR' => 'France',
    'MC' => 'Morrocco',
    'SN' => 'Senegal',
    'US'=> 'United States',
    'UK' => 'United Kingdom',
    
    // Add all countries here
);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?="Recipients"?></title>
</head>
<body>
    <h2>Add a new recipient <h2>
    <form>
        <label for="country">Select a country:</label>
        <select id="country" name="country">
            <?php
            foreach ($countries as $code => $name) {
                echo "<option value='$code'>$name</option>";
            }
            ?>
        </select>
        <input type="submit" value="Submit"><br /> <br />
        User Name (other user)<br />
	<input type="full name" name="full name" required /><br /><br />
	Mobile number<br />
	<input type="Mobile number" name="mobile number" required /><br /><br />
	Retype mobile number<br />
	<input type="retype mobile number" name="retype mobile number" required /><br /><br />

        </form>
   
</body>
</html>