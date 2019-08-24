<?php

    namespace NotifiUs\WCTP\XML;

    use InvalidArgumentException;

    abstract class WCTPOperation
    {
        protected $token;

        public function __construct( $token = null )
        {
            $this->token = $token;

            if( ! is_null( $this->token ) && strlen( $this->token ) > 16 )
            {
                throw new InvalidArgumentException('Token must be between 1 - 16 characters in length');
            }
        }

        abstract public function xml();

    }