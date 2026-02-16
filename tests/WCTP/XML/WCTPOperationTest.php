<?php

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\XML\WCTPOperation;


final class WCTPOperationTest extends TestCase
{
    public function testTokenOver16CharsThrowsAnException(): void
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Token must be between 1 - 16 characters in length' );
        $token  = str_pad('1', 17 );
        new class( $token ) extends WCTPOperation {
            public function xml(): \SimpleXMLElement { return new \SimpleXMLElement('<root/>'); }
        };
    }
}