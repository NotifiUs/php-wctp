<?php

use PHPUnit\Framework\TestCase;
use NotifiUs\WCTP\XML\WCTPOperation;


final class WCTPOperationTest extends TestCase
{
    public function testTokenOver16CharsThrowsAnException(): void
    {
        $this->expectException( InvalidArgumentException::class );

        $token  = str_pad('1', 17 );
        $mock = $this->getMockForAbstractClass( WCTPOperation::class, [ $token ] );

    }
}