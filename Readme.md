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


## General Information

## Dates

We rely on the `nesbot/carbon` library for dates throughout our library. 


### Add WCTP token to wctp-Operation

For all XML WCTP methods below, you can optionally, pass in a `wctpToken` to the constructor:

```php
$clientQuery = new ClientQuery( 'token' );
```

This will add the XML attribute `wctpToken="token"` to the `<wctp-Operation>` element.


### Return Type
The `$xml` variable will be a *SimpleXMLElement* Object. You can get the XML as a string by calling `$xml->asXML()`

### Relaxed parameter requirements

While we follow the WCTP recommendations for parameters and lengths, we don't enforce allowed characters. 
Anything that is not XML compliant will be automatically escaped, so keep that in mind. 
This should provide an additional level of flexibility and modernize the now ~15 year-old protocol. 


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


### wctp-VersionQuery

Create an XML representation of the wctp-VersionQuery operation. 


```php
use NotifiUs\WCTP\XML\VersionQuery;

$versionQuery = new VersionQuery();
$xml = $versionQuery
    ->inquirer( 'inquirer' )
    ->dateTime( Carbon::now() )
    ->xml();
```

You can also leave off optional parameters like this:

```php
//dateTime is an optional parameter
$xml = $versionQuery
    ->inquirer( 'inquirer' )
    ->xml();
```

## Testing

After cloning the repository, you can run the test suite like this:

```console
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
```

