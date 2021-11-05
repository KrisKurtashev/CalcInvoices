<?php 
	declare(strict_types = 1); 
	include 'includes/autoloader.inc.php';

	session_start();

	if (isset($_SESSION['total'])){

	    $string = implode('</p><p>', $_SESSION['total']);
	    $totalString = '<p>' . $string . '</p>>';
    } else {
        $totalString = '';
    }

    session_unset();
    session_destroy();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title></title>

    <link rel="stylesheet" href="style.css">
    <script src="javascript.js"></script>

</head>
<body>
    <div class="section-forms">
        <form action="includes/calc.inc.php" enctype="multipart/form-data" style="border:1px solid #ccc" method="post">
            <div class="container">
                <h1>Calculate your notes</h1>
                <hr>

                <label for="fileCsv"><b>Upload csv file</b></label>
                <input type="file" id="fileCsv" name="fileCsv">

                <h2>List Currencies</h2>
                <div class="listCurr" id="listCurr">
                    <div class="currency">
                        <label for="currName1">Currency Name:</label>
                        <input type="text" id="currName1" name="currName1" class="currName"><br><br>
                        <label for="currRate1">Exchange rate:</label>
                        <input type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.001" id="currRate1" name="currRate1"><br><br>
                    </div>
                </div>
                <a class="" id="addCurrency" href="#">add btn</a>



                <label for="outputType"><b>Output currency</b></label>
                <input type="text" placeholder="Enter output currency" name="outputType" value="GBP" required>

                <label for="vatNumber"><b>Return only for single Customer</b></label>
                <input type="number" placeholder="Customer VAT number" name="vatNumber">

                <div class="clearfix">
                    <button type="submit" name="submit" class="signupbtn">Submit</button>
                </div>
            </div>

            <div>
                <?php
                    if (!empty($totalString))
                    {
                        echo '<h1> Final Result: </h1>' .  $totalString;
                    }
                ?>

            </div>
        </form>
    </div>
</body>
</html>