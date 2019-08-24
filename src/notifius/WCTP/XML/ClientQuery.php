<?php

    namespace NotifiUs\WCTP\XML;

    use SimpleXMLElement;
    use InvalidArgumentException;

    class ClientQuery extends WCTPOperation
    {
        protected $senderID;
        protected $recipientID;
        protected $trackingNumber;

        public function senderID( $senderID )
        {
            $this->senderID = $senderID;
            return $this;
        }

        public function recipientID( $recipientID )
        {
            $this->recipientID = $recipientID;
            return $this;
        }

        public function trackingNumber( $trackingNumber )
        {
            $this->trackingNumber = $trackingNumber;
            return $this;
        }


        public function xml( )
        {
            if( ! isset( $this->senderID ) ){ throw new InvalidArgumentException('senderID parameter is required'); }
            if( ! isset( $this->recipientID ) ){ throw new InvalidArgumentException('recipientID parameter is required'); }
            if( ! isset( $this->trackingNumber ) ){ throw new InvalidArgumentException('trackingNumber parameter is required'); }

            if( strlen( $this->senderID ) < 1 || strlen( $this->senderID ) > 128 ){ throw new InvalidArgumentException('senderID must be between 1 - 128 characters in length'); }
            if( strlen( $this->recipientID ) < 1 || strlen( $this->recipientID ) > 128 ){ throw new InvalidArgumentException('recipientID must be between 1 - 128 characters in length'); }
            if( strlen( $this->trackingNumber ) < 1 || strlen( $this->trackingNumber ) > 16 ){ throw new InvalidArgumentException('trackingNumber must be between 1 - 16 characters in length'); }

            if( is_null( $this->token ) )
            {
                $token_xml = '';
            }
            else
            {
                $token_xml = " wctpToken=\"{$this->token}\" ";
            }

            libxml_use_internal_errors(true );

            $xml = new SimpleXMLElement(
<<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd">
<wctp-Operation wctpVersion="WCTP-DTD-V1R3" {$token_xml}>
<wctp-ClientQuery senderID="{$this->senderID}" recipientID="{$this->recipientID}" trackingNumber="{$this->trackingNumber}" />
</wctp-Operation>
EOT
            );

            if( $xml === false ){
                $errors = libxml_get_errors();
                // Make them fix the first error until all errors are gone
                throw new InvalidArgumentException( $errors[0]->message );
            }

            return  $xml;
        }
    }