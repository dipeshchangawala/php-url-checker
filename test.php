<?php

require 'vendor/autoload.php';

use Dipeshc\PhpUrlStatusChecker\UrlStatusChecker;

$checker = new UrlStatusChecker();

// $url = 'https://google.com'; // to test 301 redirection

// $result = $checker->checkStatus($url);

// print_r($result);


// // ----------------------------------------------------------

// $result = $checker->isValidUrl('https://example.com/pdfs/forever_decision.pdf');

// print_r($result ? 'true' : 'false');

// echo PHP_EOL;

// // ----------------------------------------------------------


// $result = $checker->getRedirectChain('https://google.com');

// print_r($result);

// echo PHP_EOL;


// // ----------------------------------------------------------

// $result = $checker->isSecureUrl('https://example-notexist.com');

// print_r($result ? 'true' : 'false');

// echo PHP_EOL;


// // ----------------------------------------------------------

// $result = $checker->getResponseHeaders('https://example.com');

// print_r($result);

// echo PHP_EOL;


// // ----------------------------------------------------------

// $result = $checker->getPageTitle('https://example.com');

// print($result);

// echo PHP_EOL;

// // ----------------------------------------------------------

// $result = $checker->isReachable('https://example.com', 10);

// print($result);

// echo PHP_EOL;

// //  ----------------------------------------------------------

// $result = $checker->getContentType('https://jsonplaceholder.typicode.com/todos/1');

// print($result);

// echo PHP_EOL;


// //  ----------------------------------------------------------

// $result = $checker->getCanonicalUrl('https://www.example.com');

// print($result);

// echo PHP_EOL;

// //  ----------------------------------------------------------

// $result = $checker->validateUrlFormat('https://www.example.com');

// print($result);

// echo PHP_EOL;

// //  ----------------------------------------------------------

// $result = $checker->checkResponseTime('https://example.com');

// print($result);

// echo PHP_EOL;

// //  ----------------------------------------------------------

$result = $checker->detectBrokenLinks('https://example.com');

print_r($result);

echo PHP_EOL;
