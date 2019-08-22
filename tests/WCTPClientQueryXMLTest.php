<?php

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\XML\WCTPOperation;


final class WCTPClientQueryXMLTest extends TestCase
{
    public function testCanCreateWCTPClientQueryXML(): void
    {
        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => 'senderID',
            'recipientID' => 'recipientID',
            'trackingNumber' => 'trackingNumber',
        ]);

        echo PHP_EOL; echo PHP_EOL;
        echo print_r( $clientQuery->asXML(), true );
        echo PHP_EOL;echo PHP_EOL;
        $this->assertEquals( true, true );
    }


    public function testSenderIdIsUnder128Characters(): void
    {
        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => str_pad('1', 200),
            'recipientID' => 'nonempty',
            'trackingNumber' => '1234567890123456',
        ]);
    }

    public function testRecipientIdIsUnder128Characters(): void
    {
        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => 'nonempty',
            'recipientID' =>  str_pad('1', 200),
            'trackingNumber' => '1234567890123456',
        ]);
    }

    public function testTrackingNumberLengthMustBeBetween1And16(): void
    {
        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => 'nonempty',
            'recipientID' => 'nonempty',
            'trackingNumber' => '12345678901234567',
        ]);

    }

    public function testReturnsWCTPOperationStructure(): void
    {

        $options = [
            'senderID' => 'senderID',
            'recipientID' => 'recipientID',
            'trackingNumber' => 'trackingNumber',
        ];

        $clientQuery = WCTPOperation::ClientQuery( $options );

        $clientQueryTemplate = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><wctp-Operation wctpVersion=\"WCTP-DTD-V1R3\"><wctp-ClientQuery senderID=\"{$options['senderID']}\" recipientID=\"{$options['recipientID']}\" trackingNumber=\"{$options['trackingNumber']}\"/></wctp-Operation>");

        // this assertation ignores differences in the <?xml tag...
        // i.e., <?xml version="1.0" and <?xml version="1.0" encode="UTF-8" will evaluate as true if all is the same.
        $this->assertXmlStringEqualsXmlString( $clientQueryTemplate->asXML(), $clientQuery->asXML(), true );
    }

    public function testReturnsSimpleXMLElement(): void
    {
        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => 'senderID',
            'recipientID' => 'recipientID',
            'trackingNumber' => 'trackingNumber',
        ]);

        $this->assertInstanceOf('SimpleXMLElement', $clientQuery );
    }

    public function testSenderIdParameterCantBeEmpty(): void
    {

        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => '',
            'recipientID' => 'nonempty',
            'trackingNumber' => 'nonempty',
        ]);
    }

    public function testRecipientIdParameterCantBeEmpty(): void
    {

        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => 'nonempty',
            'recipientID' => '',
            'trackingNumber' => 'nonempty',
        ]);
    }

    public function testTrackingNumberParameterCantBeEmpty(): void
    {

        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => 'nonempty',
            'recipientID' => 'nonempty',
            'trackingNumber' => '',
        ]);
    }


    public function testFailsWhenMissingSenderIdParameter(): void
    {

        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery([
            'recipientID' => 'test',
            'trackingNumber' => 'test'
        ]);
    }

    public function testFailsWhenMissingRecipientIdParameter(): void
    {
        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery( [
            'senderID' => 'test',
            'trackingNumber' => 'test'
        ] );
    }

    public function testFailsWhenMissingTrackingNumberParameter(): void
    {
        $this->expectException( Exception::class );

        $clientQuery = WCTPOperation::ClientQuery(  [
            'recipientID' => 'test',
            'senderID' => 'test'
        ] );
    }

    /********************************************************
     *
     * I'm not sure what best practice is here...so leaving them for business rules analysis?
     *
     ********************************************************/

    public function testSenderIdMustBeWCTPAddress(): void
    {
        // Come back to this, I think the client should be responsible for being compliant?
        // In this way, we can support old/new encodings, and the only failure will be if XML can't be created from it
        // which I think is better than arbitrarily trying to create rules for all of the address types
        // this also allows some creative uses if you control both the client/server
        $this->assertEquals('ignore', 'ignore');
    }

    public function testRecipientIdMustBeWCTPAddress(): void
    {
        // Come back to this, I think the client should be responsible for being compliant?
        // In this way, we can support old/new encodings, and the only failure will be if XML can't be created from it
        // which I think is better than arbitrarily trying to create rules for all of the address types
        // this also allows some creative uses if you control both the client/server
        $this->assertEquals('ignore', 'ignore');
    }

    public function testTrackingNumberMustBeWCTPString(): void
    {
        // Come back to this, I think the client should be responsible for being compliant?
        // In this way, we can support old/new encodings, and the only failure will be if XML can't be created from it
        // which I think is better than arbitrarily trying to create rules for all of the address types
        // this also allows some creative uses if you control both the client/server
        $this->assertEquals('ignore', 'ignore');
    }

}