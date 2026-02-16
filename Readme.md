# php-wctp

A PHP library for creating and submitting XML WCTP requests and responses.

## Getting Started

Add the library to your project using [composer](https://getcomposer.org): 

```console
composer require notifius/php-wctp
```

Example usage: 

```php
use NotifiUs\WCTP\XML\ClientQuery;

$clientQuery = new ClientQuery();

$xml = $clientQuery
    ->senderID( 'senderID' )
    ->recipientID( 'recipientID' )
    ->trackingNumber( 'trackingNumber' )
    ->xml();
```


## XML Request Method Templating

| WCTP Method  | notifius/wctp   | Status |
|---------| --- | --- | 
|wctp-ClientQuery | NotifiUs\WCTP\XML\ClientQuery | &check; |
|wctp-LookupSubscriber | NotifiUs\WCTP\XML\LookupSubscriber |  &check; |
|wctp-DeviceLocation | NotifiUs\WCTP\XML\DeviceLocation |  &check; |
|wctp-MessageReply |NotifiUs\WCTP\XML\MessageReply |  &check; |
|wctp-PollForMessages |NotifiUs\WCTP\XML\PollForMessages |  &times; |
|wctp-ReturnToSvc |NotifiUs\WCTP\XML\ReturnToSvc |  &check; |
|wctp-SendMsgMulti | NotifiUs\WCTP\XML\SendMsgMulti |  &times; |
|wctp-StatusInfo |NotifiUs\WCTP\XML\StatusInfo |  &times; |
|wctp-SubmitClientMessage | NotifiUs\WCTP\XML\SubmitClientMessage | &check; |
|wctp-SubmitRequest | NotifiUs\WCTP\XML\SubmitRequest | &check; |
|wctp-VersionQuery | NotifiUs\WCTP\XML\VersionQuery | &check; |

## Return Type

The `$xml` variable will be a *SimpleXMLElement* Object. You can get the XML as a string by calling `$xml->asXML()`

## Relaxed parameter requirements

While we follow the WCTP recommendations for parameters and lengths, we don't enforce allowed characters.
Anything that is not XML compliant will be automatically escaped, so keep that in mind.
This should provide an additional level of flexibility (through conventions) and modernize the now ~15 year-old protocol.

## Add WCTP token to wctp-Operation

For all XML WCTP methods below, you can optionally pass in a `wctpToken` to the constructor:

```php
$clientQuery = new ClientQuery( 'token' );
```

This will add the XML attribute `wctpToken="token"` to the `<wctp-Operation>` element.


## WCTP XML Methods

### wctp-MessageReply

Create an XML representation of the wctp-MessageReply operation. 

```php
use Carbon\Carbon;
use NotifiUs\WCTP\XML\MessageReply;

$messageReply = new MessageReply();

$xml = $messageReply
    ->messageID( 321 )
    ->senderID( 'senderID' )
    ->recipientID( 'recipientID' )
    ->responseToMessageID( 123 )
    ->submitTimestamp( Carbon::now() )
    ->payload( 'Reply to a message' )
    ->xml();

print_r( $xml );

```


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

## License

The php-wctp library is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT). 

## Testing

After cloning the repository and running `composer install`, you can run the test suite:

```console
vendor/bin/phpunit
```

### Static Analysis

Run PHPStan to check for type and logic errors:

```console
vendor/bin/phpstan analyse
```

## Security Vulnerabilities

If you discover a security vulnerability, please email [support@calltheory.com](mailto:support@calltheory.com). 

All security vulnerabilities will be promptly addressed.

