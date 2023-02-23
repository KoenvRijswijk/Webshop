<?php
//Menu item level vs UserLevel
/*
define("lvl1", 1);  //00000001 cart
define("lvl2", 2);  //00000010 home, about, contact
define("lvl3", 4);  //00000100 login, register
define("lvl4", 8);  //00001000 logout, (change password)
define("lvl5", 250);//11111010 home, about, contact, logout, edit/admin panel

echo "level 1 is ".decbin(lvl1);     
echo "<BR>level 2 is ".decbin(lvl2); 
echo "<BR>level 3 is ".decbin(lvl3); //10100000
echo "<BR>level 4 is ".decbin(lvl4); //11111110
echo "<BR>level 5 is ".decbin(lvl5); //11111110


$item = 4;


$user = 59;
$userlevel = $user | lvl5;
if ($userlevel & $item) {
    echo '<BR><BR>user access cart?';
} else {
	echo "User has no acces";
}

echo (decbin($user) | decbin(lvl4) == lvl1);
*/

$array = [
    0 => ['id' => 1, 'name' => 'Agent 1', 'total' => 3],
    1 => ['id' => 2, 'name' => 'Agent 2', 'total' => 3],
    2 => ['id' => 3, 'name' => 'Agent 3', 'total' => 3],
    3 => ['id' => 1, 'name' => 'Agent 1', 'total' => 6],
];

$sumArray = [];

foreach ($array as $agentInfo) {

    // create new item in result array if pair 'id'+'name' not exists
    if (!isset($sumArray[$agentInfo['id']])) {
        $sumArray[$agentInfo['id']] = $agentInfo;
    } else {
        // apply sum to existing element otherwise
        $sumArray[$agentInfo['id']['total']] += $agentInfo['total'];
    }
}

// optional action to flush keys of array
$sumArray = array_values($sumArray);

print_r ($sumArray);