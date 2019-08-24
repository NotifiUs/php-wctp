<?php

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\XML\ClientQuery;


final class ClientQueryTest extends TestCase
{
    public function testShowClientQuerySimpleXMLElement(): void
    {


        $clientQuery = new ClientQuery( 'token' );

        $xml = $clientQuery
            ->senderID( 'senderID' )
            ->recipientID( 'recipientID' )
            ->trackingNumber( 'trackingNumber' )
            ->xml();

        echo PHP_EOL; echo PHP_EOL;

        echo print_r( $xml, true );

        echo PHP_EOL; echo PHP_EOL;

        $this->assertEquals( true, true );
    }

    public function testPassingTokenAddsWctpTokenParamToXML(): void
    {
        $options = [
            'senderID' => 'senderID',
            'recipientID' => 'recipientID',
            'trackingNumber' => 'trackingNumber',
        ];

        $token = 'token';

        $clientQuery = new ClientQuery( $token );
        $xml = $clientQuery
            ->senderID( $options['senderID'] )
            ->recipientID( $options['recipientID'] )
            ->trackingNumber( $options['trackingNumber'] )
            ->xml();

        $clientQueryTemplate = new SimpleXMLElement(
            <<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd">
<wctp-Operation wctpVersion="WCTP-DTD-V1R3" wctpToken="{$token}">
<wctp-ClientQuery senderID="{$options['senderID']}" recipientID="{$options['recipientID']}" trackingNumber="{$options['trackingNumber']}" />
</wctp-Operation>
EOT
        );

        // this assertation ignores differences in the <?xml tag...
        // i.e., <?xml version="1.0" and <?xml version="1.0" encode="UTF-8" will evaluate as true if all else is the same.
        $this->assertXmlStringEqualsXmlString( $clientQueryTemplate->asXML(), $xml->asXML(), true );
    }

    public function testTokenIsUnder16Characters(): void
    {
        $this->expectException( InvalidArgumentException::class );

        $clientQuery = new ClientQuery( str_pad( '1', 17 ) ) ;
        $xml = $clientQuery
            ->senderID('senderID' )
            ->recipientID( 'recipientID' )
            ->trackingNumber( 'trackingNumber' )
            ->xml();
    }



    public function testSenderIdIsUnder128Characters(): void
    {
        $this->expectException( InvalidArgumentException::class );

        $clientQuery = new ClientQuery();
        $xml = $clientQuery
            ->senderID( str_pad('1', 200) )
            ->recipientID( 'recipientID' )
            ->trackingNumber( 'trackingNumber' )
            ->xml();
    }

   public function testRecipientIdIsUnder128Characters(): void
   {
       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID( 'senderID' )
           ->recipientID( str_pad('1', 200) )
           ->trackingNumber( 'trackingNumber' )
           ->xml();
   }

   public function testTrackingNumberLengthMustBeBetween1And16(): void
   {
       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID( 'senderID' )
           ->recipientID( 'recipientID' )
           ->trackingNumber( '12345678901234567' )
           ->xml();

   }

   public function testReturnsWCTPOperationStructure(): void
   {

       $options = [
           'senderID' => 'senderID',
           'recipientID' => 'recipientID',
           'trackingNumber' => 'trackingNumber',
       ];

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID( $options['senderID'] )
           ->recipientID( $options['recipientID'] )
           ->trackingNumber( $options['trackingNumber'] )
           ->xml();

       $clientQueryTemplate = new SimpleXMLElement(
<<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd">
<wctp-Operation wctpVersion="WCTP-DTD-V1R3">
<wctp-ClientQuery senderID="{$options['senderID']}" recipientID="{$options['recipientID']}" trackingNumber="{$options['trackingNumber']}" />
</wctp-Operation>
EOT
       );

       // this assertation ignores differences in the <?xml tag...
       // i.e., <?xml version="1.0" and <?xml version="1.0" encode="UTF-8" will evaluate as true if all else is the same.
       $this->assertXmlStringEqualsXmlString( $clientQueryTemplate->asXML(), $xml->asXML(), true );
   }

   public function testReturnsSimpleXMLElement(): void
   {
       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID( 'senderID' )
           ->recipientID( 'recipientID' )
           ->trackingNumber( 'trackingNumber' )
           ->xml();

       $this->assertInstanceOf('SimpleXMLElement', $xml );
   }



   public function testSenderIdParameterCantBeEmpty(): void
   {

       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID( '' )
           ->recipientID( 'recipientID' )
           ->trackingNumber( 'trackingNumber' )
           ->xml();
   }

   public function testRecipientIdParameterCantBeEmpty(): void
   {

       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID( 'senderID' )
           ->recipientID(  '' )
           ->trackingNumber( 'trackingNumber' )
           ->xml();
   }

   public function testTrackingNumberParameterCantBeEmpty(): void
   {

       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID( 'senderID' )
           ->recipientID(  'recipientID' )
           ->trackingNumber( '' )
           ->xml();
   }

   public function testFailsWhenMissingSenderIdParameter(): void
   {

       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->recipientID(  'recipientID' )
           ->trackingNumber( 'trackingNumber' )
           ->xml();
   }

   public function testFailsWhenMissingRecipientIdParameter(): void
   {
       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID(  'senderID' )
           ->trackingNumber( 'trackingNumber' )
           ->xml();
   }

   public function testFailsWhenMissingTrackingNumberParameter(): void
   {
       $this->expectException( InvalidArgumentException::class );

       $clientQuery = new ClientQuery();
       $xml = $clientQuery
           ->senderID(  'senderID' )
           ->recipientID(  'recipientID' )
           ->xml();
   }

}