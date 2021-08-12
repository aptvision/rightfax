<?php
declare(strict_types=1);

namespace Aptvision\Rightfax\Resource;

class Recipient
{
    private $name;

    private $faxNumber;

    public function __construct(string $name, string $faxNumber)
    {
        $this->name = $name;
        $this->faxNumber = $faxNumber;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFaxNumber()
    {
        return $this->faxNumber;
    }


}
