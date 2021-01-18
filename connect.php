<?php

//turn bt on
echo `rfkill unblock bluetooth`;

$btDevicesList = str_replace(['Added devices:', '<br />'], ['', '%'], trim(nl2br(`bt-device -l`)));
$devices = array_values(array_filter(explode('%', $btDevicesList)));
$btDevices = [];

echo "Note: you need to pair your device manually before doing this...";
echo "Paired devices:\n";
  
foreach($devices as $i => $device) {
  preg_match('/[\w\s\-\'\"]+(?=\()/', $device, $matches);
  $btDevices[$i]['name'] = trim($matches[0]);
  preg_match('/(?<=\()[a-fA-F0-9:]+(?=\))/', $device, $matches);
  $btDevices[$i]['macAddress'] = $matches[0];
  
  //print to user
  echo "$i. {$btDevices[$i]['name']}\n";
}

echo "\n";
$input = readline("Type the number of the device from the list below");
$input2 = readline("Type '1' to connect it or '0' to disconnect it:");

if ($input2 == 1) {
  echo `bluetoothctl pair {$btDevices[$input]['macAddress']}`;
  echo `bluetoothctl connect {$btDevices[$input]['macAddress']}`;
} elseif ($input2 == 0) {
  echo `bluetoothctl disconnect {$btDevices[$input]['macAddress']}`;
}

echo "\n";
