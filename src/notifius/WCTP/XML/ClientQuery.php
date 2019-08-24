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
            $this->validate();

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
                throw new InvalidArgumentException( $errors[0]->message );
            }
            else
            {
                if( ! is_null( $this->token ) )
                {
                    $xml->addAttribute('wctpToken', $this->token );
                }
            }

            return  $xml;
        }

        private function validate()
        {
            $msg = '';

            if( ! isset( $this->senderID ) ){ $msg = 'senderID parameter is required'; }
            if( ! isset( $this->recipientID ) ){ $msg = 'recipientID parameter is required'; }
            if( ! isset( $this->trackingNumber ) ){ $msg = 'trackingNumber parameter is required'; }

            if( strlen( $this->senderID ) < 1 || strlen( $this->senderID ) > 128 ){ $msg = 'senderID must be between 1 - 128 characters in length'; }
            if( strlen( $this->recipientID ) < 1 || strlen( $this->recipientID ) > 128 ){ $msg = 'recipientID must be between 1 - 128 characters in length'; }
            if( strlen( $this->trackingNumber ) < 1 || strlen( $this->trackingNumber ) > 16 ){ $msg = 'trackingNumber must be between 1 - 16 characters in length'; }

            if( strlen( $msg ) )
            {
                throw new InvalidArgumentException( $msg );
            }
        }
    }