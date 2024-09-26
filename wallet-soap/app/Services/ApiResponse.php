<?php

namespace App\Services;

/**
 * StandardResponse
 *
 * Standard structure for SOAP service responses.
 */
class ApiResponse
{
    /** @var bool */
    public bool $success = false;

    /** @var string */
    public string $cod_error = '';

    /** @var string */
    public string $message_error = '';

    /** @var mixed */
    public mixed $data = [];

    /**
     * Returns the response structure.
     *
     * @param string $success
     * @param string $cod_error
     * @param string $message_error
     * @param array $data
     * @return $this
     *
     */
    public function response(
        string $success,
        string $cod_error,
        string $message_error,
        mixed  $data
    )
    {
        $this->success = $success;
        $this->cod_error = $cod_error;
        $this->message_error = $message_error;
        $this->data = $data;

        return $this;
    }
}