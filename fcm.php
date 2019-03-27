  <?php

// API access key from Google API's Console
define( 'API_ACCESS_KEY', 'AAAAL47RT2o:APA91bHOBH5R0chDURkfoQH7UZ_UtVwwA8g1xjqYzNhGO6yKvJMKKJAn-JBOLT4uWv_Pd-fLRnLofQUw53SeB3W3AwhOfuun3KCSxFx8IraJauxvT1GLV_dC3G5z3QW6JJ2ywlp0Hqpo' );
$registrationIds = array("fc9-RGNBp3I:APA91bEDb-FqV_56RluCI7tkU9Sz-yC7mo-DKbXPE7CBqGe98UMPXW5pfJQuk3VK7S7Z5EwnikrEmMrhVWK1bPUPDwY_TjA6hfOor9vp2BcGtMCk6ZjPnGNc17bzn9wZqwjNBq7-ejvr"); //$id is string not array


// prep the bundle
$notification = array
(
    'title'     => 'title',
    'body'      => 'body',
    'icon'      => 'logo',
    'sound'     => 'default',
    'tag'       => 'tag',
    'color'     => '#ffffff'

);

$data = array
(
    'message' => 'message body',
    'click_action' => "PUSH_INTENT"
);

$fields = array
(
    'registration_ids'  => $registrationIds,
    'notification'      => $notification,
    'data'              => $data,
    'priority'          => 'normal'

);

$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;

?>