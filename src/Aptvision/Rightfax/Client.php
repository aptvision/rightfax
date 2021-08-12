<?php
declare(strict_types=1);

namespace Aptvision\Rightfax;

use Aptvision\Rightfax\Resource\Attachment;
use Aptvision\Rightfax\Resource\Recipient;
use Aptvision\Rightfax\Resource\SendJob;
use GuzzleHttp\Psr7\Stream;

class Client
{
    private $rightfaxApiRootUri;

    private $username;

    private $password;

    private $guzzle;

    public function __construct(
        string $rightfaxApiRootUri,
        string $username,
        string $password,
        \GuzzleHttp\Client $guzzleClient = null
    ) {
        $this->rightfaxApiRootUri = $rightfaxApiRootUri;

        $this->username = $username;
        $this->password = $password;

        if (!$guzzleClient) {
            $guzzleClient = new \GuzzleHttp\Client();
        }

        $this->guzzle = $guzzleClient;
    }

    public function postAttachment(Stream $stream)
    {
        $response = $this->guzzle->post(
            $this->rightfaxApiRootUri . '/Attachments',
            [
                'auth' => [$this->username, $this->password],
                'multipart' => [
                    [
                        'name' => 'FileContents',
                        'contents' => $stream,
                    ],
                ],
            ]
        );

        foreach($response->getHeader('Location') as $location)
        {
            $attachmentId = explode('/', $location);

            return new Attachment(array_pop($attachmentId));
        }

        throw new \Exception(
            'Unexpected error: rightfax api did not return a Location header in response to posting attachment'
        );
    }

    /**
     * @param Recipient[] $recipients
     * @param Attachment[] $attachments
     */
    public function sendFax(array $recipients, array $attachments)
    {
        // POST to /sendjobs with json containing document id

        $json = [
            'Recipients' => [],
            'AttachmentUrls' => [],
        ];

        foreach($recipients as $recipient)
        {
            $json['Recipients'][] = [
                'Name' => $recipient->getName(),
                'Destination' => $recipient->getFaxNumber(),
            ];
        }

        foreach($attachments as $attachment)
        {
            $json['AttachmentUrls'][] = [
                $this->rightfaxApiRootUri . '/Attachments/' . $attachment->getAttachmentId(),
            ];
        }

        $response = $this->guzzle->post(
            $this->rightfaxApiRootUri . '/SendJobs',
            [
                'auth' => [$this->username, $this->password],
                'json' => $json
            ]
        );

        $responseJson = json_decode($response->getBody()->getContents());

        return new SendJob($responseJson['Id']);
    }
}
