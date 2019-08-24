# php-wctp

A PHP library for creating and submitting XML WCTP requests and responses


## Getting Started

Coming soon: Install the library using composer: 

```console
composer require <comingsoon>/wctp
```

Example use of library: 

```php
use NotifiUs\WCTP\XML\ClientQuery;

$clientQuery = new ClientQuery();

$xml = $clientQuery
    ->senderID( 'senderID' )
    ->recipientID( 'recipientID' )
    ->trackingNumber( 'trackingNumber' )
    ->xml();
```


## XML Templating

| WCTP Method  | notifius/wctp   | Complete | 
|---------| --- | --- | 
|wctp-ClientQuery | NotifiUs\WCTP\XML\ClientQuery | &check; | 
|wctp-LookupSubscriber | NotifiUs\WCTP\XML\LookupSubscriber | &times; | 
|wctp-LookupResponse | NotifiUs\WCTP\XML\LookupResponse | &times; | 
|wctp-DeviceLocation | NotifiUs\WCTP\XML\DeviceLocation | &times; | 
|wctp-DeviceLocationResponse |NotifiUs\WCTP\XML\DeviceLocationResponse | &times; | 
|wctp-MessageReply |NotifiUs\WCTP\XML\MessageReply | &times; | 
|wctp-PollForMessages |NotifiUs\WCTP\XML\PollForMessages | &times; | 
|wctp-ReturnToSvc |NotifiUs\WCTP\XML\ReturnToSvc | &times; | 
|wctp-SendMsgMulti | NotifiUs\WCTP\XML\SendMsgMulti | &times; | 
|wctp-StatusInfo |NotifiUs\WCTP\XML\StatusInfo | &times; | 
|wctp-SubmitClientMessage | NotifiUs\WCTP\XML\SubmitClientMessage | &times; | 
|wctp-SubmitRequest | NotifiUs\WCTP\XML\SubmitRequest | &times; | 
|wctp-VersionQuery | NotifiUs\WCTP\XML\VersionQuery |  &check; | 


## WCTP XML Methods

### wctp-ClientQuery

Create an XML representation of the wctp-ClientQuery operation. 

```php
use NotifiUs\WCTP\XML\ClientQuery;

$clientQuery = new ClientQuery();

$xml = $clientQuery
    ->senderID( 'senderID' )
    ->recipientID( 'recipientID' )
    ->trackingNumber( 'trackingNumber' )
    ->xml();
```

Optionally, pass in a `wctpToken`:

```php
$clientQuery = new ClientQuery( 'token' );
```

This will add the XML attribute `wctpToken="token"` to the `<wctp-Operation>` element.

The `$xml` variable will be a *SimpleXMLElement* Object. You can get the XML as a string by calling `$xml->asXML()`

```php
print_r( $xml );

/*
SimpleXMLElement Object
(
    [@attributes] => Array
        (
            [wctpVersion] => WCTP-DTD-V1R3
        )

    [wctp-ClientQuery] => SimpleXMLElement Object
        (
            [@attributes] => Array
                (
                    [senderID] => senderID
                    [recipientID] => recipientID
                    [trackingNumber] => trackingNumber
                )

        )

)
*/
```


## Testing

After cloning the repository, you can run the test suite like this:

```console
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
```

