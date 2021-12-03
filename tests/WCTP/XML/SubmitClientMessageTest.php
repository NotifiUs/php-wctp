<?php

use Carbon\Carbon;

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\DeliveryPriority;
use NotifiUs\WCTP\XML\SubmitClientMessage;


final class SubmitClientMessageTest extends TestCase
{
    public function testPlayground(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
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

        //echo PHP_EOL; echo PHP_EOL;
        //echo print_r( $xml->asXML(), true ) ;
        //echo PHP_EOL;

        $this->assertEquals( true, true );
    }

    public function testFailIfMissingSenderID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'senderID parameter is required');

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent!' )

            ->xml();
    }

    public function testFailIfMissingRecipientID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'recipientID parameter is required');

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent!' )
            ->xml();
    }

    public function testFailIfMissingSubmitTimestamp(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'submitTimestamp parameter is required');

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->payload( 'Message to be sent!' )
            ->xml();
    }

    public function testFailIfMissingPayload(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'payload parameter is required');

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->xml();
    }

    public function testSubmitTimestampRejectsNonCarbonInstances(): void
    {
        $this->expectException( TypeError::class );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( 'everyoneLovesCarbon?' )
            ->payload( 'Message to be sent!' )
            ->xml();
    }

    public function testPayloadMustBeBetween1And65535Characters(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'payload must be between 1 - 65535 characters in length' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( str_pad('1', 65536) )
            ->xml();
    }

    public function testPayloadCanBe65535CharactersLong(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( str_pad('1', 65535) )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }

    public function testReturnsSimpleXMLElemenet(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to send!' )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }

    public function testPayloadIsProperlyEscapedBySimpleXMLElement(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }

    public function testCarbonInstanceWorksForDeliveryBefore(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryBefore' => Carbon::now()
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );

        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryBefore') ), 1 );
    }


    public function testCarbonInstanceMustBePassedForDeliveryBefore(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'deliveryBefore must be an instance of Carbon date/time library' );


        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryBefore' => 'deliveryBeforeString!'
            ])
            ->xml();

    }


    public function testCarbonInstanceWorksForDeliveryAfter(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryAfter' => Carbon::now()
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );

        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryAfter') ), 1 );
    }


    public function testCarbonInstanceMustBePassedForDeliveryAfter(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'deliveryAfter must be an instance of Carbon date/time library' );


        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryAfter' => 'deliveryAfterString!'
            ])
            ->xml();

    }

    public function testDeliveryPriorityMustBeSet(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'deliveryPriority must be one of HIGH, NORMAL, or LOW' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => 'INVALID'
            ])
            ->xml();


    }

    public function testDeliveryPriorityConstantLowWorksCorrectly(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => DeliveryPriority::LOW
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryPriority') ), 1 );
    }

    public function testDeliveryPriorityConstantNormalWorksCorrectly(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => DeliveryPriority::NORMAL
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryPriority') ), 1 );
    }

    public function testDeliveryPriorityConstantHighWorksCorrectly(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => DeliveryPriority::HIGH
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryPriority') ), 1 );
    }


    public function testAllowResponseMustBeBoolean(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'allowResponse must be a boolean' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
            ->messageControlOptions([
                'allowResponse' => 'true',
            ])
            ->xml();
    }

   public function testAllowTruncationMustBeBoolean(): void
   {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'allowTruncation must be a boolean' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
            ->messageControlOptions([
                //'allowResponse' => 'true',
                'allowTruncation' => 'false',
                //'notifyWhenDelivered' => true,
                //'notifyWhenQueued' => true,
                //'notifyWhenRead' => true,
                //'preformatted' => false,
            ])
            ->xml();
   }

    public function testNotifyWhenDeliveredMustBeBoolean(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'notifyWhenDelivered must be a boolean' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
            ->messageControlOptions([
                //'allowResponse' => 'true',
                //'allowTruncation' => 'false',
                'notifyWhenDelivered' => 'true',
                //'notifyWhenQueued' => true,
                //'notifyWhenRead' => true,
                //'preformatted' => false,
            ])
            ->xml();
    }

    public function testNotifyWhenQueuedMustBeBoolean(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'notifyWhenQueued must be a boolean' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
            ->messageControlOptions([
                //'allowResponse' => 'true',
                //'allowTruncation' => 'false',
                //'notifyWhenDelivered' => 'true',
                'notifyWhenQueued' => 'true',
                //'notifyWhenRead' => true,
                //'preformatted' => false,
            ])
            ->xml();
    }

    public function testNotifyWhenReadMustBeBoolean(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'notifyWhenRead must be a boolean' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
            ->messageControlOptions([
                //'allowResponse' => 'true',
                //'allowTruncation' => 'false',
                //'notifyWhenDelivered' => 'true',
                //'notifyWhenQueued' => 'true',
                'notifyWhenRead' => 'true',
                //'preformatted' => false,
            ])
            ->xml();
    }

    public function testPreformattedMustBeBoolean(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'preformatted must be a boolean' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
            ->messageControlOptions([
                //'allowResponse' => 'true',
                //'allowTruncation' => 'false',
                //'notifyWhenDelivered' => 'true',
                //'notifyWhenQueued' => 'true',
               // 'notifyWhenRead' => 'true',
                'preformatted' => 'false',
            ])
            ->xml();
    }

    public function testAddingMessageControlOptionsWorks(): void
    {
        $submitRequest = new SubmitClientMessage( 'token' );

        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
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

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryPriority') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@allowResponse') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@allowTruncation') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryAfter') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@deliveryBefore') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@notifyWhenDelivered') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@notifyWhenQueued') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@notifyWhenRead') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@sendResponsesToID') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-SubmitClientMessage/wctp-SubmitClientHeader/wctp-ClientMessageControl/@preformatted') ), 1 );
    }
    

    public function testSendResponsesToIdMustBeBetween1And32CharactersInLength(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'sendResponsesToID must be between 1 - 128 characters in length' );

        $submitRequest = new SubmitClientMessage( 'token' ) ;
        $xml = $submitRequest
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->miscInfo( 'miscInfo' )
            ->messageControlOptions([
                'sendResponsesToID' => str_pad('X', 129 ),
            ])
            ->xml();
    }
}