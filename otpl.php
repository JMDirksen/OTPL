<?php
  // Config
  $jsonFile = 'otpl.json';
  $expireDays = 7;

  // Init
  if(!file_exists($jsonFile)) file_put_contents($jsonFile,"[]");
  
  $generateForm = isset($_GET['generate']) ? true : false;
  $showPassword = isset($_GET['id']) ? true : false;

  if(isset($_POST['password'])) {
    $password = $_POST['password'];
    $id = generateRandomString();
    $expires = date("Y-m-d", strtotime("+$expireDays day"));
    addPassword($id, $password, $expires);
    $link = generateLink($id);
  }
  
  if($showPassword) {
    $password = getPassword($_GET['id']);
    if($password !== null) {
      removeRecord($_GET['id']);
    }
  }
  
?>

<html>
  <head>
    <title>Password</title>
  </head>
  <body>
    <h1>Password</h1>
    
    <?php if($generateForm) { ?>
      <form method="post"><input type="text" name="password"> <input type="submit" value="Generate Link"></form>
    <?php } if(isset($link)) { ?>
      <textarea cols="110" onfocus="this.select();"><?php echo $link; ?></textarea>
    <?php } ?>
    
    <?php if($showPassword) { ?>
      <textarea cols="110" onfocus="this.select();"><?php echo $password; ?></textarea>
    <?php } ?>
    
    
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

