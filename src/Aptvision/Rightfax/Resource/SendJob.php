<?php
declare(strict_types=1);

namespace Aptvision\Rightfax\Resource;

class SendJob
{
    private $sendJobId;

    public function __construct(string $sendJobId)
    {
        $this->sendJobId = $sendJobId;
    }

    public function getSendJobId(): string
    {
        return $this->sendJobId;
    }
}
