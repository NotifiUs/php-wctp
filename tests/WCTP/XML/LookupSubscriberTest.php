<?php

use Carbon\Carbon;

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\XML\LookupSubscriber;


final class LookupSubscriberTest extends TestCase
{
    public function testPlayground(): void
    {
        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->senderID('senderID' )
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            //optional
            ->transactionID( 'transactionID' )
            ->authorizationCode( 'authorizationCode' )
            ->miscInfo( 'miscInfo' )
            ->securityCode( 'securityCode' )
            ->messageControlOptions([
                'sendResponsesToID' => 'sendResponsesToID',
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

        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            ->xml();
    }

    public function testFailIfMissingRecipientID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'recipientID parameter is required');

        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->messageID( 'messageID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->xml();
    }


    public function testFailIfMissingMessageID(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'messageID parameter is required');

        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->submitTimestamp( Carbon::now() )
            ->xml();
    }

    public function testFailIfMissingSubmitTimestamp(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'submitTimestamp parameter is required');

        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->xml();
    }

    public function testSubmitTimestampRejectsNonCarbonInstances(): void
    {
        $this->expectException( TypeError::class );

        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( 'everyoneLovesCarbon?' )
            ->xml();
    }

    public function testReturnsSimpleXMLElemenet(): void
    {
        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->recipientID( 'recipientID' )
            ->senderID( 'senderID' )
            ->messageID( 'messageID' )
            ->submitTimestamp( Carbon::now() )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
    }

    public function testAddingMessageControlOptionsWorks(): void
    {
        $lookupSubscriber = new LookupSubscriber( 'token' );

        $xml = $lookupSubscriber
            ->senderID('senderID' )
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
            //optional
            ->transactionID( 'transactionID' )
            ->authorizationCode( 'authorizationCode' )
            ->miscInfo( 'miscInfo' )
            ->securityCode( 'securityCode' )
            ->messageControlOptions([
                'sendResponsesToID' => 'sendResponsesToID',
            ])
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( count( $xml->xpath('//wctp-Operation/wctp-LookupSubscriber/wctp-LookupMessageControl/@sendResponsesToID') ), 1 );
    }

    public function testTransactionIdMustBeBetween1And32CharactersInLength(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'transactionID must be between 1 - 32 characters in length' );

        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->senderID('senderID' )
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
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

        $lookupSubscriber = new LookupSubscriber( 'token' ) ;
        $xml = $lookupSubscriber
            ->senderID('senderID' )
            ->messageID( 'messageID' )
            ->recipientID( 'recipientID' )
            ->submitTimestamp( Carbon::now() )
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