<?php
date_default_timezone_set('America/Toronto');
#region imports
require '********\Composer\vendor\autoload.php';
require '********\Composer\vendor\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require '********\Composer\vendor\PHPMailer-master\PHPMailer-master\src\Exception.php';
require '********\Composer\vendor\PHPMailer-master\PHPMailer-master\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
#endregion

#region truck class
class Truck
{
    #region variables
    private $truckName;
    private $lon;
    private $lat;
    #endregion

    function __construct ($truckName, $lon, $lat)
    {
        $this->setTruckName($truckName);
        $this->setLon($lon);
        $this->setLat($lat);
    }

    #region setters & getters
    public function setTruckName ($truckName)
        {$this->truckName = $truckName;}
    public function getTruckName ()
        {return $this->truckName;}

    public function setLon ($lon)
        {$this->lon = $lon;}
    public function getLon ()
        {return $this->lon;}
    
    public function setLat ($lat)
        {$this->lat = $lat;}
    public function getLat ()
        {return $this->lat;}
    #endregion

}
#endregion

#region Pulling truck data from API
$truckArray = array();
error_reporting(1);
$trucks = file_get_contents( __DIR__ .'/Trucks.txt');
$searchCriteria = "{\"vehicleName\": \"" . $trucks . "\"}";

$division = '********';
$apiKey = '********';
$url = '********' . $division . '********';
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
$headers[] = 'X-Apikey: ' . $apiKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $searchCriteria);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$jsonExec = curl_exec($ch);
if(curl_errno($ch))
{
	echo 'Error:' . curl_error($ch);
}

curl_close($ch);

$json = json_decode($jsonExec, true);
$length = count($json['Data'], 0);
echo $length . " problem trucks are online!\n";
#endregion

#region make truck object for each truck
if ($length > 0)
    {
        for ($i = 0; $i < $length; $i++)
            {
                $truckName = $json['Data'][$i]['vehicleName'];
                $lon = $json['Data'][$i]['lon'];
                $lat = $json['Data'][$i]['lat'];
                array_push($truckArray, new Truck($truckName, $lon, $lat));
            }
    }

    $count;
    $msg = "These trucks are near the yard:<br>";
#endregion

#region generate a message with the name of each truck near the yard
foreach($truckArray as $truck)
    {
        
        if (($truck->getLat() >= "43.71100" && $truck->getLat() <= "43.71400") &&
        (($truck->getLon() >= "-79.58600" && $truck->getLon() <= "-79.58100")))
            {
                $count++;
                $msg .= $count . " " . $truck->getTruckName() . "<br>";
            }
    }
$msg .= "<br><br>Email sent from PHP Script Running from ******** on ********";
#endregion

#region send the email
if (date("Hi")>"0800" && date("Hi")<"1745")
{
    if($count>0)
    sendEmail($msg);
}

function sendEmail($msg)
{
	$mail = new PHPMailer(true);
	$emailList = ['********'];

	try 
	{
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host = 'smtp.office365.com';
		$mail->SMTPAuth = true;
		$mail->Username = '********';
		$mail->Password = '********';
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = 587;
	
		$mail->setFrom('********', 'Trucks in Yard');
		$mail->addAddress('********');
        $mail->addAddress('********');
        $mail->addAddress('********');
	
		$mail->isHTML(true);
		$mail->Subject = 'Trucks in Yard';
		$mail->Body .= $msg;
		$mail->send();
		echo 'Message has been sent';
		} catch (Exception $e)
		{
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
}
echo $msg;
#endregion
?>