<?php

namespace App\Message;

class ImportMEPMessage
{
    private string $mep;

    public function __construct(object $mep)
    {
        $this->mep = $mep;
    }

    public function getMep(): string
    {
        return $this->mep;
    }
}