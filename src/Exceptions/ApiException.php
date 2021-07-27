<?php
/**
 * Created by PhpStorm.
 * User: Matt
 */

namespace Freshdesk\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;

/**
 * General Exception
 *
 * Thrown when the Freshdesk API returns an HTTP error code that isn't handled by other exceptions
 *
 * @package Exceptions
 * @author Matthew Clarkson <mpclarkson@gmail.com>
 */
class ApiException extends Exception
{

    /**
     * @internal
     * @param RequestException $e
     * @param string $message The optional error message string.
     * @return AccessDeniedException|ApiException|AuthenticationException|ConflictingStateException|
     * MethodNotAllowedException|NotFoundException|RateLimitExceededException|UnsupportedAcceptHeaderException|
     * UnsupportedContentTypeException|ValidationException
     */
     public static function create(RequestException $e, $message = null) {

         if($response = $e->getResponse()) {

             switch ($response->getStatusCode()) {
                 case 400:
                     return new ValidationException($e, $message);
                 case 401:
                     return new AuthenticationException($e, $message);
                 case 403:
                     return new AccessDeniedException($e, $message);
                 case 404:
                     return new NotFoundException($e, $message);
                 case 405:
                     return new MethodNotAllowedException($e, $message);
                 case 406:
                     return new UnsupportedAcceptHeaderException($e, $message);
                 case 409:
                     return new ConflictingStateException($e, $message);
                 case 415:
                     return new UnsupportedContentTypeException($e, $message);
                 case 429:
                     return new RateLimitExceededException($e, $message);
             }
         }

         return new ApiException($e, $message);
    }

    /**
     * @var RequestException
     * @internal
     */
    private $exception;

    /**
     * Returns the Request Exception
     *
     * A Guzzle Request Exception is returned
     *
     * @return RequestException
     */
    public function getRequestException()
    {
        return $this->exception;
    }

    /**
     * Exception constructor
     *
     * Constructs a new exception.
     *
     * @param RequestException $e
     * @param string $message The optional message string.
     * @internal
     */
    public function __construct(RequestException $e, $message = null)
    {
        if (!empty($message)) {
            $this->exception = new \Exception($message);
        } else {
            $this->exception = $e;
        }

        parent::__construct();
    }
}
