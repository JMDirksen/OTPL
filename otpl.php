<?php

  // Config
  $email = 'admin@domain.com';
  $title = 'One Time Password Link';
  $logo = '';
  $cssFile = 'otpl.css';
  $expireDays = 7;
  $jsonFile = 'otpl.json';

  // Init
  if(!file_exists($jsonFile)) file_put_contents($jsonFile,"[]");
  $content = null;
  if($logo) $content .= '<img src="' . $logo . '">';
  
  // Request Password
  if(isset($_GET['id'])) {
    $password = getPassword($_GET['id']);
    $width = strlen($password) + 2;
    if($password !== null) {
      removeRecord($_GET['id']);
      $content .= '<h2>Your Password</h2>';
      $content .= 'Make sure to store your password safely.<br/><br/>';
      $content .= '<textarea cols="' . $width . '" onfocus="this.select();">' . $password . '</textarea><br/><br/>';
      $content .= 'The password has been removed permanently, after leaving this page you won\'t be able to show the password again.<br/>';
    }
    else {
      $content .= '<h2>Password Unavailable</h2>';
      $content .= 'Your password is not available anymore.<br/>';
      $content .= 'The password links can only be used once and will expire after ' . $expireDays . ' days.<br/>';
      $content .= 'Place contact <a href="mailto:' . $email . '" target="_blank">' . $email . '</a> to request a new password.';
    }
  }

  // Generate Link Form
  else {
    $content .= '<h2>Generate Password Link</h2>';
    $content .= '<form method="post"><input type="text" name="password" placeholder="password" required> <input type="submit" value="Generate Link"></form>';
    if(isset($_POST['password'])) {
      $password = $_POST['password'];
      $id = generateRandomString();
      $expires = date("Y-m-d", strtotime("+$expireDays day"));
      addPassword($id, $password, $expires);
      $link = generateLink($id);
      $width = strlen($link) + 2;
      $content .= '<textarea cols="' . $width . '" onfocus="this.select();">' . $link . '</textarea>';
    }
  }

?>
<html>
  <head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $cssFile; ?>">
  </head>
  <body>
    <?php echo $content; ?>
  </body>
</html>
<?php

  function generateRandomString($length = 32) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  function addPassword($id, $password, $expires) {
    addRecord(array('id' => $id, 'password' => $password, 'expires' => $expires));
  }

  function loadDatabase() {
    global $jsonFile;
    $json = file_get_contents($jsonFile);
    $db = json_decode($json);
    //die(print_r($db));
    return $db;
  }
  
  function storeDatabase($db) {
    global $jsonFile;
    file_put_contents($jsonFile, json_encode($db));
  }
  
  function addRecord($array) {
    $db = loadDatabase();
    array_push($db, $array);
    storeDatabase($db);
  }

  function generateLink($id) {
    $proto = $_SERVER['HTTPS'] == "on" ? "https://" : "http://";
    return $proto . $_SERVER['HTTP_HOST'] . "/?id=" . $id;
  }

  function getPassword($id) {
    $r = getRecord($id);
    return $r ? $r->password : null;
  }

  function getRecord($id) {
    $db = loadDatabase();
    $record = null;
    foreach($db as $r) {
      if ($r->id == $id) {
          $record = $r;
          break;
      }
    }
    return $record;
  }

  function removeRecord($id) {
    $db = loadDatabase();
    $key = null;
    foreach($db as $k => $v) {
      if ($v->id == $id) {
          $key = $k;
          break;
      }
    }
    unset($db[$key]);
    storeDatabase($db);
  }
