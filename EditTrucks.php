<?php
// dont need this but I like to include it so I don't forget about it
date_default_timezone_set('America/Toronto');
// add CSS to page
echo "<head>
        <link rel=\"stylesheet\" href=\"style.css\">
        </head>";

// import the txt file as a string
$trucks = file_get_contents(__DIR__ .'/Trucks.txt');
// turn it into an array
$trucks = explode(", ", $trucks);

// check if I want to add or remove
if ($_POST['type'] == "add")
{
    // get the string from the form in index.php and add the string to the array
    array_push($trucks, "1 -" . $_POST['truckName']);

    // change the arraay back to a string
    $trucks = implode(", ", $trucks);

    // update the txt file
    $fp = fopen(__DIR__ .'/Trucks.txt', 'w');
    fwrite($fp, $trucks);

    // give the user a confirmation and add a back button. I use this button because if the used hits back in the browser the textbox in the html file will not be updated
    echo "Truck " . $_POST['truckName'] . " has been added to Trucks.txt<br>";
    echo "<br><button onclick=\"window.location='http://******/scripts/WorkingCode/trucksNearYard/'\">Back </button>";
}

// check if I want to add or remove
if ($_POST['type'] == "remove")
{
    // check if the trucked entered in the form is not in the list
    if (!in_array("1 -" . $_POST['truckName'], $trucks))
    {
        // dont 
        echo "Truck " . $_POST['truckName'] . " is not on the list!";
    }
    else
    {
        $trucks = array_diff($trucks, ["1 -" . $_POST['truckName']]);
        $trucks = implode(", ", $trucks);

        $fp = fopen(__DIR__ .'/Trucks.txt', 'w');
        fwrite($fp, $trucks);

        echo "Truck " . $_POST['truckName'] . " has been removed from Trucks.txt<br>";
    }
    echo "<br><button onclick=\"window.location='http://******/scripts/WorkingCode/trucksNearYard/'\">Back </button>";
}

?>
