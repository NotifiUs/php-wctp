# php-wctp

A PHP library for creating and submitting XML WCTP requests and responses


## Getting Started

```php

    use NotifiUs\WCTP\XML\WCTPOperation;

    $options = [
        'senderID' => 'senderID',
        'recipientID' => 'recipientID',
        'trackingNumber' => 'trackingNumber',
    ];

    $clientQuery = WCTPOperation::ClientQuery( $options );
    
    //get XML string from SimpleXMLElement
    $xml = $clientQuery->asXML();


```


```php

    use NotifiUs\WCTP\WCTPOperation\ClientQuery;

    $clientQuery = new ClientQuery();
    $xml = $clientQuery
            ->senderID( 'senderID' )
            ->recipientID( 'recipientID' )
            ->trackingNumber( 'trackingNumber' )
            ->xml();         

 
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


