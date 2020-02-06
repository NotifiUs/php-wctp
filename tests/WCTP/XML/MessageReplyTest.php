<?php

use Carbon\Carbon;

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\DeliveryPriority;
use NotifiUs\WCTP\XML\MessageReply;


final class MessageReplyTest extends TestCase
{
    public function testPlayground(): void
    {
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        //echo PHP_EOL; echo PHP_EOL;
        //echo print_r( $xml->asXML(), true ) ;
        //echo PHP_EOL;

        $this->assertEquals( true, true );
    }

    public function testFailIfMissingSenderID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'senderID parameter is required');

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->xml();
    }

    public function testSubmitTimestampRejectsNonCarbonInstances(): void
    {
        $this->expectException( TypeError::class );

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( str_pad('1', 65536) )
            ->xml();
    }

    public function testPayloadCanBe65535CharactersLong(): void
    {
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }

    public function testCarbonInstanceWorksForDeliveryBefore(): void
    {
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryBefore' => Carbon::now()
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );

        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryBefore') ), 1 );
    }


    public function testCarbonInstanceMustBePassedForDeliveryBefore(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'deliveryBefore must be an instance of Carbon date/time library' );


        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryBefore' => 'deliveryBeforeString!'
            ])
            ->xml();

    }


    public function testCarbonInstanceWorksForDeliveryAfter(): void
    {
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryAfter' => Carbon::now()
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );

        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryAfter') ), 1 );
    }


    public function testCarbonInstanceMustBePassedForDeliveryAfter(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'deliveryAfter must be an instance of Carbon date/time library' );


        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => 'INVALID'
            ])
            ->xml();


    }

    public function testDeliveryPriorityConstantLowWorksCorrectly(): void
    {
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => DeliveryPriority::LOW
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryPriority') ), 1 );
    }

    public function testDeliveryPriorityConstantNormalWorksCorrectly(): void
    {
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => DeliveryPriority::NORMAL
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryPriority') ), 1 );
    }

    public function testDeliveryPriorityConstantHighWorksCorrectly(): void
    {
        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message & : to send!' )
            ->messageControlOptions([
                'deliveryPriority' => DeliveryPriority::HIGH
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryPriority') ), 1 );
    }


    public function testAllowResponseMustBeBoolean(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'allowResponse must be a boolean' );

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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
                'allowResponse' => 'true',
            ])
            ->xml();
    }

   public function testAllowTruncationMustBeBoolean(): void
   {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'allowTruncation must be a boolean' );

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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
        $messageReply = new MessageReply( 'token' );

        $xml = $messageReply
            ->responseToMessageID(5)
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

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryPriority') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@allowResponse') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@allowTruncation') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryAfter') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@deliveryBefore') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@notifyWhenDelivered') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@notifyWhenQueued') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@notifyWhenRead') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@sendResponsesToID') ), 1 );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-MessageReply/wctp-ResponseHeader/wctp-MessageControl/@preformatted') ), 1 );
    }


    public function testTransactionIdMustBeBetween1And32CharactersInLength(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'transactionID must be between 1 - 32 characters in length' );

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
            ->senderID('senderID' )
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->payload( 'Message to be sent! & more even! ' )
            //optional
            ->transactionID( str_pad('X', 33 ) )
            ->authorizationCode( 'authorizationCode' )
            ->miscInfo( 'miscInfo' )
            ->securityCode( 'securityCode' )
            ->xml();
    }

    public function testSendResponsesToIdMustBeBetween1And32CharactersInLength(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'sendResponsesToID must be between 1 - 128 characters in length' );

        $messageReply = new MessageReply( 'token' ) ;
        $xml = $messageReply
            ->responseToMessageID(5)
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
                'sendResponsesToID' => str_pad('X', 129 ),
            ])
            ->xml();
    }
}