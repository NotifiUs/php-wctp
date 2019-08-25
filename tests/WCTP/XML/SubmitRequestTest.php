<?php

use Carbon\Carbon;

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\DeliveryPriority;
use NotifiUs\WCTP\XML\SubmitRequest;


final class SubmitRequestTest extends TestCase
{
    public function testPlayground(): void
    {
        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->transactionID( 'transactionID' )
            ->authorizationCode( 'authorizationCode' )
            ->miscInfo( 'miscInfo' )
            ->securityCode( 'securityCode' )
            ->messageControlOptions([
                'deliveryPriority' => DeliveryPriority::NORMAL,
                'allowResponse' => true,
                'allowTruncation' => false,
                'deliveryAfter' => Carbon::now()->addHours(1 ),
                'deliveryBefore' => Carbon::now()->addHours(2 ),
                'notifyWhenDelivered' => true,
                'notifyWhenQueued' => true,
                'notifyWhenRead' => true,
                'sendResponsesToID' => 'sendResponsesToID',
                'preformatted' => false,
            ])
            ->xml();

        echo PHP_EOL; echo PHP_EOL;
        echo print_r( $xml->asXML(), true ) ;
        echo PHP_EOL;

        $this->assertEquals( true, true );
    }

    public function testFailIfMissingSenderID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'senderID parameter is required');

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent!' )

            ->xml();
    }

    public function testFailIfMissingRecipientID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'recipientID parameter is required');

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->messageID( 'messageID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent!' )

            ->xml();
    }

    public function testFailIfMissingMessageID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'messageID parameter is required');

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent!' )
            ->xml();
    }

    public function testFailIfMissingSubmitTimestamp(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'submitTimestamp parameter is required');

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->payload( 'Message to be sent!' )
            ->xml();
    }

    public function testFailIfMissingPayload(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'payload parameter is required');

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->xml();
    }

    public function testSubmitTimestampRejectsNonCarbonInstances(): void
    {
        $this->expectException( TypeError::class );

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( 'everyoneLovesCarbon?' )
            ->payload( 'Message to be sent!' )
            ->xml();
    }

    public function testPayloadMustBeBetween1And65535Characters(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'payload must be between 1 - 65535 characters in length' );

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( str_pad('1', 65536) )
            ->xml();
    }

    public function testPayloadCanBe65535CharactersLong(): void
    {

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( str_pad('1', 65535) )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }

    public function testReturnsSimpleXMLElemenet(): void
    {

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to send!' )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }

    public function testPayloadIsProperlyEscapedBySimpleXMLElement(): void
    {

        $submitRequest = new SubmitRequest( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }



}