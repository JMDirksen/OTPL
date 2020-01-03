<?php
$email      = getenv('EMAIL')       ?? 'admin@domain.com';
$expireDays = getenv('EXPIRE_DAYS') ?? 7;
$title      = getenv('PAGE_TITLE')  ?? 'One Time Password Link';
$logo       = getenv('LOGO')        ?? 'logo.png';
$cssFile    = getenv('CSS')         ?? 'otpl.css';
$jsonFile   = getenv('JSON')        ?? 'otpl.json';
