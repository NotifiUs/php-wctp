<?php

    namespace NotifiUs\WCTP\XML;

    use SimpleXMLElement;
    use InvalidArgumentException;

    abstract class WCTPOperation
    {
        protected $token;
        protected $xml_template;

        public function __construct( $token = null )
        {
            $this->token = $token;
            $this->xml_template = '<?xml version="1.0" encoding="UTF-8" ?><!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd"><wctp-Operation wctpVersion="WCTP-DTD-V1R3"></wctp-Operation>';

            if( ! is_null( $this->token ) && strlen( $this->token ) > 16 )
            {
                throw new InvalidArgumentException('Token must be between 1 - 16 characters in length');
            }
        }

        abstract public function xml(): SimpleXMLElement;

    }