<?php

namespace Dipeshc\PhpUrlStatusChecker;

use Throwable;
use GuzzleHttp\Client;

class UrlStatusChecker
{
  protected $client;

  public function __construct($options = [])
  {
    // Initialize the Guzzle client
    $this->client = new Client(array_merge([
      'allow_redirects' => false, // Disable auto redirects
      'http_errors' => false, // Disable throwing exceptions on 4xx/5xx
      'timeout' => 10 // Set a timeout for the request
    ], $options));
  }

  public function checkStatus(string $url): array
  {
    try {
      // Make the request to the given URL
      $response = $this->client->get($url);

      $statusCode = $response->getStatusCode();
      $location = null;

      // Check for redirection status codes (3xx)
      if ($statusCode >= 300 && $statusCode < 400) {
        $location = $response->getHeaderLine('Location');
      }

      return [
        'url' => $url,
        'status' => $statusCode,
        'location' => $location
      ];
    } catch (Throwable $e) {
      // Handle errors like timeouts or connection problems
      return [
        'url' => $url,
        'status' => 'error',
        'message' => $e->getMessage()
      ];
    }
  }

  /**
   * Check if a given URL is valid and reachable.
   *
   * @param string $url
   * @return bool
   */
  public function isValidUrl(string $url): bool
  {
    try {
      $response = $this->client->get($url);

      $statusCode = $response->getStatusCode();

      // Consider 2xx and 3xx responses as valid
      return $statusCode >= 200 && $statusCode < 400;
    } catch (Throwable $e) {
      // If any exception occurs, the URL is not valid
      return false;
    }
  }

  /**
   * Retrieves the full redirect chain for a given URL
   *
   * @param string $url
   * @return array
   */
  public function getRedirectChain(string $url): array
  {
    $redirects = [];
    try {
      do {
        $response = $this->client->get($url);
        $statusCode = $response->getStatusCode();
        $redirects[] = ['url' => $url, 'status' => $statusCode];

        // Check for redirection
        $url = ($statusCode >= 300 && $statusCode < 400) ? $response->getHeaderLine('Location') : null;
      } while ($url);

      return $redirects;
    } catch (Throwable $e) {
      return [
        ['url' => $url, 'status' => 'error', 'message' => $e->getMessage()]
      ];
    }
  }

  /**
   * Check if a given URL is secure (HTTPS).
   *
   * @param string $url
   * @return bool
   */
  public function isSecureUrl(string $url): bool
  {
    return parse_url($url, PHP_URL_SCHEME) === 'https';
  }

  /**
   * Get the response headers for a given URL.
   *
   * @param string $url
   * @return array
   */
  public function getResponseHeaders(string $url): array
  {
    try {
      $response = $this->client->get($url);
      return $response->getHeaders();
    } catch (Throwable $e) {
      return ['error' => $e->getMessage()];
    }
  }

  /**
   * Get the page title for a given URL.
   *
   * @param string $url
   * @return string|null
   */
  public function getPageTitle(string $url): ?string
  {
    try {
      $response = $this->client->get($url);
      $html = $response->getBody()->getContents();

      if (preg_match('/<title>(.*?)<\/title>/', $html, $matches)) {
        return $matches[1];
      }
      return null;
    } catch (Throwable $e) {
      return null;
    }
  }

  /**
   * Check if a given URL is reachable within a specified timeout period.
   * This is similar to isValidUrl but allows developers to specify a custom timeout.
   *
   * @param string $url
   * @param int $timeout
   * @return bool
   */
  public function isReachable(string $url, int $timeout = 5): bool
  {
    try {
      $client = new Client(['timeout' => $timeout]);
      $response = $client->head($url);
      return $response->getStatusCode() < 400;
    } catch (Throwable $e) {
      return false;
    }
  }

  /**
   * Fetch the Content-Type of a URL (e.g., text/html, application/json)
   * 
   * @param string $url
   * @return string|null
   */
  public function getContentType(string $url): ?string
  {
    try {
      $response = $this->client->head($url);
      return $response->getHeaderLine('Content-Type');
    } catch (Throwable $e) {
      return null;
    }
  }

  /**
   * Check the status of multiple URLs in a single call.
   *
   * @param array $urls
   * @return array
   */
  public function checkMultipleUrls(array $urls): array
  {
    $results = [];
    foreach ($urls as $url) {
      $results[] = $this->checkStatus($url);
    }
    return $results;
  }

  /**
   * A lightweight function to validate the syntax of a URL using PHP’s filter_var
   * 
   * @param string $url
   * @return bool
   */
  public function validateUrlFormat(string $url): bool
  {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
  }

  /**
   * Get the canonical URL of a given URL.
   *
   * @param string $url
   * @return string|null
   */
  public function getCanonicalUrl(string $url): ?string
  {
    try {
      $response = $this->client->get($url);
      $html = $response->getBody()->getContents();

      if (preg_match('/<link rel=["\']canonical["\'] href=["\'](.*?)["\']/', $html, $matches)) {
        return $matches[1];
      }
      return null;
    } catch (Throwable $e) {
      return null;
    }
  }

  /**
   * Check the response time of a given URL.
   *
   * @param string $url
   * @return float
   */
  public function checkResponseTime(string $url): float
  {
    $startTime = microtime(true);

    try {
      $this->client->head($url);
    } catch (Throwable $e) {
      // Return a high value if the URL is unreachable
      return 9999.0;
    }

    return microtime(true) - $startTime;
  }

  /**
   * Check for broken links on a given webpage by scanning all href attributes.
   *
   * @param string $url
   * @return bool
   */
  public function detectBrokenLinks(string $url): array
  {
    $brokenLinks = [];
    try {
      $response = $this->client->get($url);
      $html = $response->getBody()->getContents();

      preg_match_all('/<a href=["\'](.*?)["\']/', $html, $matches);

      foreach ($matches[1] as $link) {
        $fullUrl = parse_url($link, PHP_URL_SCHEME) ? $link : rtrim($url, '/') . '/' . ltrim($link, '/');
        if (!$this->isValidUrl($fullUrl)) {
          $brokenLinks[] = $fullUrl;
        }
      }
    } catch (Throwable $e) {
      // Handle errors
    }
    return $brokenLinks;
  }
}
