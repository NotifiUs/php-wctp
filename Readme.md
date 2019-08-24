# php-wctp

A PHP library for creating and submitting XML WCTP requests and responses


## Getting Started

```console
composer require notifius/wctp
```

```php
use NotifiUs\WCTP\XML\ClientQuery;

$clientQuery = new ClientQuery();

$xml = $clientQuery
    ->senderID( 'senderID' )
    ->recipientID( 'recipientID' )
    ->trackingNumber( 'trackingNumber' )
    ->xml();
```


## Requests and Responses

| Request | Server (receives) | Client (submits) |
|---------|-------------------|------------------|
|wctp-ClientQuery | Yes | No |
|wctp-LookupSubscriber | Yes | No |
|wctp-LookupResponse | No | Yes |
|wctp-DeviceLocation | Yes | No |
|wctp-DeviceLocationResponse | No | Yes |
|wctp-MessageReply | Yes | Yes |
|wctp-PollForMessages | Yes | No | 
|wctp-ReturnToSvc | Yes | No |
|wctp-SendMsgMulti | Yes | No |
|wctp-StatusInfo | Yes | Yes |
|wctp-SubmitClientMessage | Yes | No |
|wctp-SubmitRequest | Yes | Yes |
|wctp-VersionQuery | Yes | Yes  |


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


