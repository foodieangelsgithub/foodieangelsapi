<?PHP
function sendMessage(){
   $content = array(
       "en" => 'This is english message',
       "es" => 'Este es el mensaje a enviar'
   );

   $headins = array(
       'es' => 'Título en español',
       'en' => 'Títle in english',
   );

   $fields = array(
       'app_id'   => "dc4ab90b-d613-4303-99b6-2b7d4011ef55",
       'include_player_ids' => array("d869cca1-38b6-40fe-bbed-305afed343bc","1d4ca827-dbf3-4c67-8fe7-ddedb4d9e085"),
       'data'     => array("foo" => "Enviando mensaje"),
       'contents' => $content,
      'headins'   => $headins
   );

   echo '<pre>';
   $fields = json_encode($fields, JSON_PRETTY_PRINT);
   print("\nJSON sent:\n");
   print($fields);

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
   curl_setopt($ch, CURLOPT_HEADER, FALSE);
   curl_setopt($ch, CURLOPT_POST, TRUE);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

   $response = curl_exec($ch);
   curl_close($ch);

   return $response;
}

$response = sendMessage();
$return["allresponses"] = $response;
$return = json_encode( $return);

print("\n\nJSON received:\n");
print($return);
print("\n");