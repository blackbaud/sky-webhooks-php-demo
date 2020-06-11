<?
define('WEBHOOK_SECRET_KEY', 'replace-with-your-secret');
define('JSON_FILENAME', 'results.json');

function getJsonData() {
  $data = [];

  if (file_exists(JSON_FILENAME)) {
    $json = json_decode(file_get_contents(JSON_FILENAME));
    if ($json != null) {
      $data = $json;
    }
  }

  return $data;
}

// Handle subscription handshake
function handleOPTIONS() {
  $allowed_origin = 'eventgrid.azure.net';

  // Validate the handshake header is set
  if (
    isset($_SERVER['HTTP_WEBHOOK_REQUEST_ORIGIN']) &&
    $_SERVER['HTTP_WEBHOOK_REQUEST_ORIGIN'] == $allowed_origin
  ) {

    // Valid the request contains our unique key
    if (
      isset($_GET['key']) &&
      $_GET['key'] == WEBHOOK_SECRET_KEY
    ) {
      header('WebHook-Allowed-Origin: ' . $allowed_origin);
      header('WebHook-Allowed-Rate: 100');
      header('Allow: POST');
    }
  }
}

// Handle subscription event
function handlePOST() {
  // Validate the request contains our unique key
  if (
    isset($_GET['key']) &&
    $_GET['key'] == WEBHOOK_SECRET_KEY
  ) {

    // Read event data
    $event = json_decode(file_get_contents('php://input'));

    // Read our file
    $data = getJsonData();
    
    // Add our event
    array_push($data, $event);
    
    // Write the file back
    file_put_contents(JSON_FILENAME, json_encode($data, JSON_PRETTY_PRINT));

    // Not necessary, just for confirmation
    echo 'Event Successfully Handled!';
  }
}

// Debugging by showing previous results
function handleGET() {
  $data = array_reverse(getJsonData());
  $count = count($data);
  
  echo '<h2>'. sprintf(ngettext("%d Webhook", "%d Webhooks", $count), $count) . ' Received</h2>';
  echo '<p><i>Shown in descending chronological order.</i></p>';
  echo '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';
}

// Main Entry Point
switch ($_SERVER['REQUEST_METHOD']) {
  case 'OPTIONS':
    handleOPTIONS();
  break;

  case 'POST':
    handlePOST();
  break;

  case 'GET':
    handleGET();
  break;

  default:
    http_response_code(405);
  break;
}
?>