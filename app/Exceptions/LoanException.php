<?php
namespace App\Exceptions;

use DomainException;

/**
 * DomainException yang membawa kode error mesin (dipakai oleh API).
 * Controller web yang menangkap DomainException tetap berfungsi seperti biasa.
 */
class LoanException extends DomainException
{
    public function __construct(public readonly string $errorCode, string $message)
    {
        parent::__construct($message);
    }
}
