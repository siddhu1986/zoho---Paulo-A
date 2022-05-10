<?php


/* DB Details */
$cn_host = 'localhost';
$cn_db   = 'aditecch_sid';
$cn_user = 'aditecch_sid';
$cn_pass = 'axvdC77hpW(6';
$cn_charset = 'utf8mb4';

/* PDO Init */
$dsn = "mysql:host=$cn_host;dbname=$cn_db;charset=$cn_charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];
global $pdo;
try
{
  $pdo = new PDO($dsn, $cn_user, $cn_pass, $options);
}
catch (\PDOException $e)
{
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}




/* Constants for creds */
/* Create SELF CLIENT : https://api-console.zoho.com/ (Add Client) */
define("CLIENT_ID", "1000.WBALVL8FBOS44226F2FW3DIK39I0SI");
define("CLIENT_SECRET", "fdd1060141b2b3b24050061767e481d75fe0838dfc");


/* Go to Self Client > Generate Code > [Scope: ZohoCRM.modules.leads.CREATE] > */
define("CODE", "1000.4be9804729ae2f26d9976367ace1c8e3.fb9c2ab4a6841385e57b53e1cbd243a9");


/* Function to getRefreshTokem from the above code (One-time) */
function getRefreshToken()
{
	global $pdo;
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://accounts.zoho.com/oauth/v2/token");
	curl_setopt($ch, CURLOPT_POST, 1);


	$qs = array();
	$qs[] = "grant_type=authorization_code";
	$qs[] = "client_id=" . CLIENT_ID;
	$qs[] = "client_secret=" . CLIENT_SECRET;
	$qs[] = "code=" . CODE;
	$qs = implode("&",$qs);

	curl_setopt($ch, CURLOPT_POSTFIELDS,$qs);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$out = curl_exec($ch);
	$out = json_decode($out);

  /* Save refresh and access tokens to DB */
	if(isset($out->refresh_token))
	{
    $dt = date("Y-m-d H:i:s");
		$query = "update pa_tokens set access_token='{$out->access_token}', refresh_token='{$out->refresh_token}', date_updated='{$dt}' where id='1'";
		$pdo->query($query);
	}

	curl_close ($ch);
}

/* Run First Time to update Refresh Token and Access Token in DB */
//getRefreshToken();
//die();


/* Get Access Token from Refresh Token */
function getAccessToken($refresh_token)
{
	global $pdo;
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://accounts.zoho.com/oauth/v2/token");
	curl_setopt($ch, CURLOPT_POST, 1);

	$qs = array();
	$qs[] = "grant_type=refresh_token";
	$qs[] = "client_id=" . CLIENT_ID;
	$qs[] = "client_secret=" . CLIENT_SECRET;
	$qs[] = "refresh_token=" . $refresh_token;
	$qs = implode("&",$qs);

	curl_setopt($ch, CURLOPT_POSTFIELDS,$qs);

	// Receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$out = curl_exec($ch);
	$out = json_decode($out);

  /* Update access token in DB */
  if(isset($out->access_token))
	{
		$query = "update pa_tokens set access_token='{$out->access_token}', date_updated='{$dt}' where id='1'";
		$pdo->query($query);
	}

	curl_close ($ch);
	return $out->access_token;
}




function addLead($lead,$getToken)
{
	global $pdo;
	$query = "select refresh_token,access_token from pa_tokens where id='1'";
	$stmt = $pdo->query($query);
	$row = $stmt->fetch(PDO::FETCH_OBJ);
	$token = $row->access_token;
	$rtoken = $row->refresh_token;


	if($getToken==true)
	{
    /* Get Access Token if $getToken==true */
		$token = getAccessToken($rtoken);
	}

	$ch = curl_init( "https://www.zohoapis.com/crm/v2/Leads" );
	# Setup request to send json via POST.
	$payload = array( "data" =>
  [
    array(
      "Last_Name" => $lead["last_name"],
      "Email" => $lead["email"]
    )
  ]);

	$payload = json_encode($payload);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	echo $token;
	curl_setopt( $ch, CURLOPT_HTTPHEADER, 
		array(
      "Content-Type:application/json",
			"Authorization:Zoho-oauthtoken {$token}"));
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	# Send request.
	$result = curl_exec($ch);
	$result = json_decode($result);
	
  if(!$getToken && isset($result->status) && $result->status=="error")
	{
    /* It current access token is invalid/expired, then fetch another token by using refresh token */
		addLead($lead,true);
    /* return from function */
		return;
	}
	curl_close($ch);
	# Print response.
	print_r($result);
}

//$lead = array("last_name"=>"Smith","email"=>"abc@baa.com");
extract($_REQUEST);
$lead = array("last_name"=>$last_name,"email"=>$email);
addLead($lead,false);


?>