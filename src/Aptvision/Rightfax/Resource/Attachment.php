<?php
declare(strict_types=1);

namespace Aptvision\Rightfax\Resource;

class Attachment
{
    private $attachmentId;

    public function __construct(string $attachmentId)
    {
        $this->attachmentId = $attachmentId;
    }

    public function getAttachmentId()
    {
        return $this->attachmentId;
    }


}
