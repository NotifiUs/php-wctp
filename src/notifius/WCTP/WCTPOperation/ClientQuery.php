<?php

    namespace NotifiUs\WCTP\WCTPOperation;

    use Exception;
    use SimpleXMLElement;

    class ClientQuery
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
            if( ! isset( $this->senderID ) ){ throw new Exception('senderID parameter is required'); }
            if( ! isset( $this->recipientID ) ){ throw new Exception('recipientID parameter is required'); }
            if( ! isset( $this->trackingNumber ) ){ throw new Exception('trackingNumber parameter is required'); }

            if( strlen( $this->senderID ) < 1 || strlen( $this->senderID ) > 128 ){ throw new Exception('senderID must be between 1 - 128 characters in length'); }
            if( strlen( $this->recipientID ) < 1 || strlen( $this->recipientID ) > 128 ){ throw new Exception('recipientID must be between 1 - 128 characters in length'); }
            if( strlen( $this->trackingNumber ) < 1 || strlen( $this->trackingNumber ) > 16 ){ throw new Exception('trackingNumber must be between 1 - 16 characters in length'); }

            libxml_use_internal_errors(true );

            $xml = new SimpleXMLElement(
<<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd">
<wctp-Operation wctpVersion="WCTP-DTD-V1R3">
<wctp-ClientQuery senderID="{$this->senderID}" recipientID="{$this->recipientID}" trackingNumber="{$this->trackingNumber}" />
</wctp-Operation>
EOT
            );

            if( $xml === false ){
                $errors = libxml_get_errors();
                throw new Exception( $errors[0]->message );
            }

            return  $xml;
        }
    }