# PHP URL Status Checker

**PHP URL Status Checker** is a lightweight and flexible PHP package designed to help developers easily verify the status of URLs, extract canonical URLs, validate URLs, and perform other useful URL-related operations.

## Features

- Check the HTTP status code of a given URL.
- Validate if a URL is well-formed and accessible.
- Extract the canonical URL from a webpage.
- Detect redirections and retrieve the `Location` header.
- Handle connection errors and timeouts gracefully.
- Extendable with more URL utilities for developers.

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP Client

## Installation

Install the package via Composer:

```bash
composer require dipeshchangawala/php-url-status-checker
```

## Usage

### Initialize the Package

```php
use Dipeshc\PhpUrlStatusChecker\UrlStatusChecker;

// Initialize the checker with optional Guzzle client settings
$checker = new UrlStatusChecker([
    'timeout' => 15 // Set timeout to 15 seconds (optional)
]);
```

### Check URL Status

```php
$url = 'https://example.com';
$result = $checker->checkStatus($url);

print_r($result);
```

#### Example Output

```php
Array
(
    [url] => https://example.com
    [status] => 200
    [location] =>
)
```

> If the URL redirects, the location field will contain the redirect URL.

### Validate a URL

```php
$url = 'https://example.com';
$isValid = $checker->isValidUrl($url);

if ($isValid) {
    echo "$url is a valid URL.";
} else {
    echo "$url is not valid.";
}
```

### Get the Canonical URL

```php
$url = 'https://example.com';
$canonicalUrl = $checker->getCanonicalUrl($url);

if ($canonicalUrl) {
    echo "The canonical URL for $url is: $canonicalUrl";
} else {
    echo "No canonical URL found for $url";
}
```

#### Example Output

```plaintext
The canonical URL for https://example.com is: https://www.example.com
```

### Detect Redirects

```php
$url = 'http://example.com';
$redirectLocation = $checker->getRedirectLocation($url);

if ($redirectLocation) {
    echo "The URL redirects to: $redirectLocation";
} else {
    echo "No redirection detected for $url";
}
```

#### Example Output

```plaintext
The URL redirects to: https://example.com
```

### Retrieve all HTTP headers from the given URL

```php
$url = 'https://example.com';
$headers = $checker->getHttpHeaders($url);

print_r($headers);
```

#### Example Output

```php
Array
(
    [Content-Type] => text/html; charset=UTF-8
    [Server] => Apache
    [Connection] => keep-alive
)
```

### Check if a URL is reachable and returns a 2xx or 3xx HTTP status code

```php
$url = 'https://example.com';
$isReachable = $checker->isUrlReachable($url);

if ($isReachable) {
    echo "$url is reachable.";
} else {
    echo "$url is not reachable.";
}
```

#### Example Output

```plaintext
https://example.com is reachable.
```

### Retrieve the entire chain of redirects for a given URL

```php
$url = 'http://example.com';
$redirectChain = $checker->getRedirectChain($url);

if (!empty($redirectChain)) {
    echo "Redirect chain for $url:\n";
    foreach ($redirectChain as $step) {
        echo "URL: {$step['url']}, Status: {$step['status']}\n";
    }
} else {
    echo "$url does not have a redirect chain.";
}
```

#### Example Output

```plaintext
Redirect chain for http://example.com:
URL: http://example.com, Status: 301
URL: https://example.com, Status: 200
```

### Check whether the given URL uses a secure `https` connection

```php
$url = 'https://example.com';
$isSecure = $checker->isSecureUrl($url);

if ($isSecure) {
    echo "$url is a secure URL.";
} else {
    echo "$url is not secure.";
}
```

#### Example Output

```plaintext
https://example.com is a secure URL.
```

### Get the page title for a given URL

```php
$url = 'https://example.com';
$title = $checker->getPageTitle($url);

echo $title;
```

### Fetch the Content-Type of a URL (e.g., text/html, application/json)

```php
$url = 'https://jsonplaceholder.typicode.com/todos/1';
$type = $checker->getContentType($url);

echo $type;
```

#### Example Output

```plaintext
application/json; charset=utf-8
```

### Validating the syntax of a URL using PHP’s filter_var

```php
$url = 'https://example.com';
$isValid = $checker->validateUrlFormat($url);
```

### Check the response time of a given URL (in second).

```php
$url = 'https://example.com';
$time = $checker->checkResponseTime($url);

echo $time; // in second(with floating point)
```

### Check for broken links on a given webpage by scanning all href attributes.

```php
$url = 'https://example.com';
$links = $checker->detectBrokenLinks($url);

print_r($links);
```

```plaintext
Array
(
    [0] => tel:1000077777
    [1] => https://example.com/tel:988
    [2] => https://example.com/pdfs/forever_decision.pdf
)
```

### Check the status of multiple URLs in a single call

```php
$urls = [
    'https://example.com',
    'https://example.com/call2',
    'https://example.com/call3',
];

$links = $checker->checkMultipleUrls($url);

print_r($links);
```

```plaintext
Array
(
    [0] => Array
        (
            [url] => "https://example.com"
            [status] => 200
            [location] =>
        )

    [1] => Array
        (
            [url] => "https://example.com/call2"
            [status] => 404
            [location] =>
        )

    [2] => Array
        (
            [url] => "https://example.com/call3"
            [status] => 404
            [location] =>
        )
)
```

## Methods Overview

| Method                           | Description                                                                   |
| -------------------------------- | ----------------------------------------------------------------------------- |
| `checkStatus($url)`              | Returns the status code and `Location` header if applicable                   |
| `isValidUrl($url)`               | Validates if the given URL is well-formed and accessible                      |
| `getCanonicalUrl($url)`          | Extracts the canonical URL from the webpage, if available                     |
| `getRedirectLocation($url)`      | Retrieves the Location header for redirects                                   |
| `getRedirectChain($url)`         | Retrieves the full redirect chain for a given URL                             |
| `isSecureUrl($url)`              | Check if a given URL is secure                                                |
| `getResponseHeaders($url)`       | Get the response headers for a given URL                                      |
| `getPageTitle($url)`             | Get the page title for a given URL                                            |
| `isReachable($url, $timeout)`    | Check if a given URL is reachable within a specified timeout period           |
| `getContentType($url)`           | Fetch the Content-Type of a URL                                               |
| `checkMultipleUrls(array $urls)` | Check the status of multiple URLs in a single call                            |
| `validateUrlFormat($url)`        | A lightweight function to validate the syntax of a URL using PHP’s filter_var |
| `checkResponseTime($url)`        | Check the response time of a given URL                                        |
| `detectBrokenLinks($url)`        | Check for broken links on a given webpage by scanning all href attributes     |

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

This `README.md` gives clear instructions to developers on how to use your package while showcasing its key features. Let me know if you'd like to include additional sections!
