<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class IncompleteCustomerAttributesException extends ValidationException
{
}
