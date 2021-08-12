# Rightfax PHP library
This library allows you to easily send faxes through a rightfax server API

# Usage

```php

use Aptvision\Rightfax\Resource\Recipient;
use Aptvision\Rightfax\Client;
use GuzzleHttp\Psr7\Stream;

$rightfax = new Client(
    'http://my-rightfax-server/Rightfax/API/',
    'my-username',
    'my-password'
);

// Create a stream for the document any way that suits best
$document = new Stream(fopen('/path/to/document', 'r'));

// Post the document to the rightfax API
$attachment = $rightfax->postAttachment($document);

// Create a recipient
$recipient = new Recipient('John Smith', '1234567890');

// Send the fax!
// Note that multiple recipients or attachments are allowed
$sendJob = $rightfax->sendFax([$recipient], [$attachment]);
```
