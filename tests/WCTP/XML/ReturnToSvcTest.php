<?php

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\XML\ReturnToSvc;

final class ReturnToSvcTest extends TestCase
{

    public function testPlayground(): void
    {
        $returnToSvc = new ReturnToSvc('token');
        $xml = $returnToSvc
            ->address( 'address')
            ->xml();

        echo PHP_EOL; echo PHP_EOL;
        echo print_r( $xml->asXML(), true ) ;
        echo PHP_EOL; echo PHP_EOL;

        $this->assertEquals( true, true );

    }

    public function testFailsIfMissingAddressMethod(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage('address parameter is required');
        $returnToSvc = new ReturnToSvc('token');
        $xml = $returnToSvc
            ->xml();

    }

    public function testFailsIAddressIsEmptyWhenPassed(): void
    {
        $this->expectException( ArgumentCountError::class );

        $returnToSvc = new ReturnToSvc('token');
        $xml = $returnToSvc
            ->address()
            ->xml();

    }

    public function testFailsIfAddressIsZeroLength(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'address must be between 1 - 128 characters in length' );

        $returnToSvc = new ReturnToSvc('token');
        $xml = $returnToSvc
            ->address('')
            ->xml();

    }

    public function testFailsIfAddressIsLongerThan128(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'address must be between 1 - 128 characters in length' );

        $returnToSvc = new ReturnToSvc('token');
        $xml = $returnToSvc
            ->address(str_pad('X', 129 ) )
            ->xml();

    }

    public function testAddressIsAddedCorrectlyToXML(): void
    {
        $returnToSvc = new ReturnToSvc('token');
        $xml = $returnToSvc
            ->address( 'address' )
            ->xml();

        $this->assertInstanceOf('SimpleXMLElement', $xml );
        $this->assertEquals( $xml->xpath('//wctp-Operation/wctp-ReturnToSvc/text()')[0] , 'address' );
    }

}