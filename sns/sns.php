<?php
require 'aws/aws-autoloader.php';
$sdk = new Aws\Sns\SnsClient([
    'region'  => 'us-west-2',
    'version' => 'latest',
    'credentials' => ['key' => 'AKIAJ74RUIPLH56M242A', 'secret' => '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']
  ]);
  
  
$result = $sdk->publish(['TopicArn' => 'arn:aws:sns:us-west-2:956449821269:autobooster_integration', 'Message' => '{"vendor_id":2, "api_details": {"url": "api.sell4.us", "consumer_key": "SAD2143qwa", "Token": "2131s@QWE"}, "integration_type": "magento"}', 'Subject' => 'SQS Message send']);

print_r( $result );
?>