<?php

require_once(__DIR__ ."/./Ec2ClientWrapper.php");

$CL = new Ec2ClientWrapper();

// Create new instance same with me.
$params = [
	"ImageId" => $CL->getMyAMIId(),
	"KeyName" => "xxxxx",	// Your keyName
	"SecurityGroup" => $CL->getMySecurityGroups(),
	"InstanceType" => $CL->getMyInstanceType(),
];
$CL->createInstanceByImage($params);

// Destroy me.
$params = ["InstanceId" => $CL->getMyInstanceId()];
$CL->terminateInstance($params);	





