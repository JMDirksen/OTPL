<?php

// Config
require('otpl.config.php');

// Init
if(!file_exists($jsonFile)) file_put_contents($jsonFile,"[]");
removeExpired($expireDays);
$content = null;
if($logo) $content .= '<img src="'.$logo.'">';

// Request password
if(isset($_GET['id'])) {
    if(isset($_GET['show'])) {

        $password = getPassword($_GET['id']);
        // Show password
        if($password !== null) {
            $width = strlen($password) + 2;
            removeRecord($_GET['id']);
            $content .= '<h2>Your Password</h2>'.
                'Make sure to store your password safely.<br/><br/>'.
                '<textarea cols="'.$width.'" onfocus="this.select();">'.
                $password.'</textarea><br/><br/>'.
                'The password has been removed permanently, '.
                'after leaving this page you won\'t be able to show the password again.'.
                '<br/>';
        }
        // Password unavailable
        else {
            $content .= '<h2>Password Unavailable</h2>'.
                'Your password is not available anymore.<br/>'.
                'The password links can only be used once and will expire after '.
                $expireDays.' days.<br/>'.
                'Place contact '.
                '<a href="mailto:'.$email.'" target="_blank">'.$email.'</a> '.
                'to request a new password.';
        }
    }
    else {
        $content .= '<h2>Your Password</h2>'.
            'The password can only be showed once.<br/>'.
            'Show password now?<br/><br/>'.
            '<button onClick="location.href=\'?id='.$_GET['id'].'&show\';">Show password</button><br/><br/>'.
            '<br/>';
    }
}

// Generate-link form
else {
    $content .= '<h2>Generate Password Link</h2>'.
        '<form method="post">'.
        '<input type="text" id="password" name="password" placeholder="password" onfocus="this.select();" required> '.
        '<input type="button" value="Random password" onClick="generatePassword(); document.getElementById(\'link\').value = \'\';"> '.
        '<input type="submit" value="Generate link"></form>';
    // Generate/show link
    if(isset($_POST['password'])) {
        $password = $_POST['password'];
        $id = generateRandomString();
        $expires = date("Y-m-d H:i", strtotime("+$expireDays day"));
        addPassword($id, $password, $expires);
        $link = generateLink($id);
        $width = strlen($link) + 2;
        $content .= '<textarea id="link" cols="'.$width.'" onfocus="this.select();">'.
        $link.'</textarea>';
    }
}

?>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $cssFile; ?>" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <script src="otpl.js"></script>
    </head>
    <body>
        <?php echo $content; ?>
    </body>
</html>
<?php

function removeExpired($expireDays) {
    $today = strtotime(date("Y-m-d H:i"));
    $db = loadDatabase();
    foreach($db as $record) {
        if (strtotime($record->expires) < $today) {
            removeRecord($record->id);
        }
    }
}

function generateRandomString($length = 32) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charsLength = strlen($chars);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, $charsLength - 1)];
    }
    return $randomString;
}

function addPassword($id, $password, $expires) {
    addRecord(array('id'=>$id, 'password'=>$password, 'expires'=>$expires));
}

function loadDatabase() {
    global $jsonFile;
    $json = file_get_contents($jsonFile);
    $db = json_decode($json);
    return $db;
}

function storeDatabase($db) {
    global $jsonFile;
    file_put_contents($jsonFile, json_encode(array_values($db)));
}

function generateLink($id) {
    $proto = $_SERVER['HTTPS'] == "on" ? "https://" : "http://";
    return $proto.$_SERVER['HTTP_HOST']."/?id=".$id;
}

function getPassword($id) {
    $r = getRecord($id);
    return $r ? $r->password : null;
}

function addRecord($array) {
    $db = loadDatabase();
    array_push($db, $array);
    storeDatabase($db);
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
