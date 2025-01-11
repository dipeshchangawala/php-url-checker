<?php

require 'vendor/autoload.php';

use Kalkani\PhpUrlStatusChecker\UrlStatusChecker;

$checker = new UrlStatusChecker();

// $url = 'https://qprinstitute-staging.kalkani.co.in/index.php?p=admin/login'; // to test 301 redirection

// $result = $checker->checkStatus($url);

// print_r($result);


// // ----------------------------------------------------------

// $result = $checker->isValidUrl('https://qprinstitute-staging.kalkani.co.in/pdfs/Forever_Decision.pdf');

// print_r($result ? 'true' : 'false');

// echo PHP_EOL;

// // ----------------------------------------------------------


// $result = $checker->getRedirectChain('https://qprinstitute-staging.kalkani.co.in/index.php?p=admin/login');

// print_r($result);

// echo PHP_EOL;


// // ----------------------------------------------------------

// $result = $checker->isSecureUrl('http://qprinstitute1-staging.kalkani.co.in/index.php?p=admin/login');

// print_r($result ? 'true' : 'false');

// echo PHP_EOL;


// // ----------------------------------------------------------

// $result = $checker->getResponseHeaders('https://qprinstitute-staging.kalkani.co.in/index.php?p=admin/login');

// print_r($result);

// echo PHP_EOL;


// // ----------------------------------------------------------

// $result = $checker->getPageTitle('https://qprinstitute-staging.kalkani.co.in');

// print_r($result);

// echo PHP_EOL;

// // ----------------------------------------------------------

// $result = $checker->isReachable('https://qprinstitute-staging.kalkani.co.in', 10);

// print_r($result);

// echo PHP_EOL;

// //  ----------------------------------------------------------

// $result = $checker->getContentType('https://dev2-api.mazetec.io/api/v1/auth/user');

// print_r($result);

// echo PHP_EOL;


// //  ----------------------------------------------------------

$result = $checker->getCanonicalUrl('https://dev2.mazetec.io/maze');

print_r($result);

echo PHP_EOL;

