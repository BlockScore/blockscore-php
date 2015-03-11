<?php

namespace BlockScore\Util;

class Exception extends \Exception
{
    public $type;
    public $message;
    public $response_code;

    public function __construct($message, $type, $response_code = null)
    {
        $this->message = $message;
        $this->type = $type;
        $this->response_code = $response_code;
    }

    public function getFormattedMessage()
    {
        if ($this->type == null) {
            return "Error: {$this->message}";
        }
        return "Error ({$this->type}): {$this->message}";
    }
}