<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\XML\VersionQuery;

final class VersionQueryTest extends TestCase
{

    public function testPlayground(): void
    {
        $versionQuery = new VersionQuery('token');
        $xml = $versionQuery
            ->inquirer( 'inquirer')
            ->dateTime( Carbon::now() )
            ->xml();

        $this->assertEquals( true, true );

    }

    public function testPassingTokenAddsWctpTokenParamToXML(): void
    {
        $options = [
            'inquirer' => 'inquirer',
            'dateTime' => Carbon::now('UTC'),
        ];

        $token = 'token';

        $versionQuery = new VersionQuery( $token );
        $xml = $versionQuery
            ->inquirer( $options['inquirer'] )
            ->dateTime( $options['dateTime'] )
            ->xml();

        $versionQueryTemplate = new SimpleXMLElement(
            <<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd">
<wctp-Operation wctpVersion="WCTP-DTD-V1R3" wctpToken="{$token}">
<wctp-VersionQuery inquirer="{$options['inquirer']}" dateTime="{$options['dateTime']->timezone('UTC')->format('Y-m-d\TH:i:s' )}" />
</wctp-Operation>
EOT
        );

        // this assertation ignores differences in the <?xml tag...
        // i.e., <?xml version="1.0" and <?xml version="1.0" encode="UTF-8" will evaluate as true if all else is the same.
        $this->assertXmlStringEqualsXmlString( $versionQueryTemplate->asXML(), $xml->asXML(), true );
    }

    public function testTokenIsUnder16Characters(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Token must be between 1 - 16 characters in length' );

        $versionQuery = new VersionQuery( str_pad( '1', 17 ) );
        $xml = $versionQuery
            ->inquirer( 'inquirer' )
            ->dateTime( Carbon::now() )
            ->xml();

    }



    public function testInquirerIsUnder128Characters(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'inquirer must be between 1 - 128 characters in length' );

        $versionQuery = new VersionQuery();
        $xml = $versionQuery
            ->inquirer( str_pad( '1', 129 ) )
            ->dateTime( Carbon::now() )
            ->xml();
    }

   public function testReturnsWCTPOperationStructure(): void
   {
       $options = [
           'inquirer' => 'inquirer',
           'dateTime' => Carbon::now('UTC'),
       ];

       $versionQuery = new VersionQuery();
       $xml = $versionQuery
           ->inquirer( $options['inquirer'] )
           ->dateTime( $options['dateTime'] )
           ->xml();

       $versionQueryTemplate = new SimpleXMLElement(
           <<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd">
<wctp-Operation wctpVersion="WCTP-DTD-V1R3">
<wctp-VersionQuery inquirer="{$options['inquirer']}" dateTime="{$options['dateTime']->timezone('UTC')->format('Y-m-d\TH:i:s' )}" />
</wctp-Operation>
EOT
       );

       // this assertation ignores differences in the <?xml tag...
       // i.e., <?xml version="1.0" and <?xml version="1.0" encode="UTF-8" will evaluate as true if all else is the same.
       $this->assertXmlStringEqualsXmlString( $versionQueryTemplate->asXML(), $xml->asXML(), true );
   }

   public function testReturnsSimpleXMLElement(): void
   {
       $versionQuery = new VersionQuery();
       $xml = $versionQuery
           ->inquirer( 'inquirer' )
           ->dateTime( Carbon::now() )
           ->xml();

       $this->assertInstanceOf('SimpleXMLElement', $xml );
   }

   public function testFailsWhenMissingInquirerParameter(): void
   {
       $this->expectException( InvalidArgumentException::class );
       $this->expectExceptionMessage( 'inquirer parameter is required' );

       $versionQuery = new VersionQuery();
       $xml = $versionQuery
           ->dateTime( Carbon::now() )
           ->xml();
   }

   public function testDateTimeParameterCanBeEmpty(): void
   {
       $versionQuery = new VersionQuery();
       $xml = $versionQuery
           ->inquirer( 'inquirer' )
           ->xml();

       $this->assertInstanceOf('SimpleXMLElement', $xml );
   }

   public function testDateTimeFailsWithoutCarbonInstance(): void
   {
       $this->expectException( TypeError::class );

       $versionQuery = new VersionQuery();
       $xml = $versionQuery
           ->inquirer( 'inquirer' )
           ->dateTime( 'everyoneLovesCarbon?' )
           ->xml();
   }

}