<?php

  // Config
  $jsonFile = 'otpl.json';
  $expireDays = 7;
  $title = 'One Time Password Link';
  $logo = 'logo.png';

  // Init
  if(!file_exists($jsonFile)) file_put_contents($jsonFile,"[]");
  $content = null;
  
  // Generate Link Form
  if (isset($_GET['generate'])) {
    $content .= '<h1>Generate Password Link</h1>';
    $content .= '<form method="post"><input type="text" name="password"> <input type="submit" value="Generate Link"></form>';
    if(isset($_POST['password'])) {
      $password = $_POST['password'];
      $id = generateRandomString();
      $expires = date("Y-m-d", strtotime("+$expireDays day"));
      addPassword($id, $password, $expires);
      $link = generateLink($id);
      $content .= '<textarea cols="110" onfocus="this.select();">' . $link . '</textarea>';
    }
  }
  
  // Request Password
  if(isset($_GET['id'])) {
    $password = getPassword($_GET['id']);
    if($password !== null) {
      removeRecord($_GET['id']);
      $content .= '<h1>Your Password</h1>';
      $content .= 'Make sure to store your password safely.<br/><br/>';
      $content .= '<textarea cols="110" onfocus="this.select();">' . $password . '</textarea><br/><br/>';
      $content .= 'The password has been removed permanently, after leaving this page you won\'t be able to show the password again.<br/>';
    }
    else {
      $content .= '<h1>Password Unavailable</h1>';
      $content .= 'Your password is not available anymore. The password links can only be used once.<br/>Did you not use the link before? Then probably someone viewed the password before you, place contact us to request a new password.';
    }
  }
  
?>
<html>
  <head>
    <title><?php echo $title; ?></title>
  </head>
  <body>
    <img src="<?php echo $logo; ?>">
    <?php echo $content; ?>
  </body>
</html>
<?php

  function generateRandomString($length = 64) {
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

  function addRecord($array) {
    global $jsonFile;
    $json = file_get_contents($jsonFile);
    $db = json_decode($json);
    array_push($db, $array);
    file_put_contents($jsonFile, json_encode($db));
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
    global $jsonFile;
    $json = file_get_contents($jsonFile);
    $db = json_decode($json);
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
    global $jsonFile;
    $json = file_get_contents($jsonFile);
    $db = json_decode($json, true);
    $key = null;
    foreach($db as $k => $v) {
      if ($v['id'] == $id) {
          $key = $k;
          break;
      }
    }
    unset($db[$key]);
    file_put_contents($jsonFile, json_encode($db));
  }

