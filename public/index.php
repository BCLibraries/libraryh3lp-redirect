<?php

/**
 * Redirect users to appropriate LibraryH3lp queue
 *
 * BC uses three LibH3lp chat queues. In order of preference:
 *
 *     * cs-bostoncoll - staffed by Boston College librarians
 *     * ajcu-consortium - staffed by librarians at other AJCU schools
 *     * ajcu-chatstaff - staffed by LibH3lp librarians
 *
 * This tool determines which of these queues is active and redirects the user
 * to the best option.
 */

namespace BCLib\LibraryH3lp;

require_once __DIR__ . '/../vendor/autoload.php';

// Config values
const ERROR_MESSAGE_FILE = __DIR__ . '/error-message.html';
const SKIN_CODE = '29500';
const CACHE_KEY = 'current-libraryh3lp-queue';
const CACHE_TIMEOUT = 30; // in seconds

// Cache
$redis = new \Redis();
$redis->connect('127.0.0.1');

// If the chat URL is in cache, redirect and exit.
if ($redis->exists(CACHE_KEY)) {
    redirect($redis->get(CACHE_KEY));
    exit();
}

// Otherwise find the correct URL and redirect to it.
$resolver = new Resolver([
    new Queue('cs-bostoncoll', 'Boston College', SKIN_CODE, true),
    new Queue('ajcu-consortium', 'Boston College/AJCU Librarians', SKIN_CODE),
    new Queue('ajcu-chatstaff', 'ChatStaff Librarians', SKIN_CODE)
]);

try {
    $url = $resolver->resolve();
} catch (NoAvailableQueueException $e) {
    handleError($e);
    exit();
}

// Set cache and send the user on their way.
$redis->set(CACHE_KEY, $url, CACHE_TIMEOUT);
redirect($url);

/**
 * Redirect to a URL
 *
 * @param string $url
 */
function redirect(string $url)
{
    header('HTTP/1.1 303 See Other');
    header("Location: $url");
}

/**
 * Log the error, inform the user, and abort
 *
 * @param \Exception $e
 */
function handleError(\Exception $e)
{
    error_log($e->getMessage());
    header('HTTP/1.1 503 Service Unavailable');
    header('Retry-After: 600');
    include ERROR_MESSAGE_FILE;
}