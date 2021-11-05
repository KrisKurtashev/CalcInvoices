<?php
include 'autoloader.inc.php';

if (isset($_POST["submit"]))
{
    //Grabbing the data
    $fileArray = $_FILES;
    $outputType = $_POST['outputType'];
    $vatNumber = $_POST['vatNumber'];
    $currencies = [];
    $counter = 0;
    foreach ($_POST as $key => $value)
    {
        if (strpos( $key, 'currName') !== false)
        {
            $counter++;
            $num = 'currRate' . $counter;
            $currencies += [ $value => $_POST[$num]];
        }

    }
    var_dump($_POST);
    // Instantiate SignupContr class
    $instance = new CalcContr($fileArray, $outputType, $vatNumber, $currencies);

    // Running error handlers
    $total = $instance->getTotalList($vatNumber);
    var_dump($total);

    // save in Session to transfer
    session_start();
    $_SESSION['total'] = $total;

    //Return to front page
    header("location: ../index.php?error=none");
}