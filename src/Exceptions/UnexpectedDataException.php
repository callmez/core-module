<?php

namespace Modules\Core\Exceptions;

use Throwable;
use Exception;

class UnexpectedDataException extends Exception
{
    /**
     * @var mixed
     */
    public $data;

    /**
     * Create a new exception instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @param  \Symfony\Component\HttpFoundation\Response|null  $response
     * @param  string  $errorBag
     * @return void
     */
    public function __construct($data, $message = 'Unknown exception.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->data = $data;
    }

    /**
     * Create a new validation exception from a plain array of messages.
     *
     * @param  array  $messages
     * @return static
     */
    public static function withData($data, $message)
    {
        return new static($data, $message);
    }
}
