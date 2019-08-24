<?php

    namespace NotifiUs\WCTP\XML;

    use InvalidArgumentException;

    abstract class WCTPOperation
    {
        protected $token;

        public function __construct( $token = null )
        {
            if( ! is_null( $token ) && strlen( $token ) > 16 ){ throw new InvalidArgumentException('Token must be between 1 - 16 characters in length'); }
            $this->token = $token;
        }

    }