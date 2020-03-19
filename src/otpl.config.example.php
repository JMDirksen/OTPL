<?php
$email      = getenv('EMAIL')       ? getenv('EMAIL')       : 'admin@domain.com';
$expireDays = getenv('EXPIRE_DAYS') ? getenv('EXPIRE_DAYS') : 7;
$title      = getenv('PAGE_TITLE')  ? getenv('PAGE_TITLE')  : 'One Time Password Link';
$logo       = getenv('LOGO')        ? getenv('LOGO')        : 'logo.png';
$cssFile    = getenv('CSS')         ? getenv('CSS')         : 'otpl.css';
$jsonFile   = getenv('JSON')        ? getenv('JSON')        : 'otpl.json';
