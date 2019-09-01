<?php

    namespace NotifiUs\WCTP\XML;

    use SimpleXMLElement;
    use InvalidArgumentException;

    class ReturnToSvc extends WCTPOperation
    {
        protected $address;

        public function address( $address )
        {
            $this->address = $address;
            return $this;
        }

        public function xml(): SimpleXMLElement
        {
            $this->validate();
            libxml_use_internal_errors( true );

            $xml = new SimpleXMLElement( $this->xml_template );

            if( ! is_null( $this->token ) )
            {
                $xml->addAttribute('wctpToken', $this->token );
            }

            $rts = $xml->addChild( 'wctp-ReturnToSvc' );
            $rts[0] = $this->address;

            if( $xml === false || $rts === false )
            {
                $errors = libxml_get_errors();
                throw new InvalidArgumentException( $errors[0]->message );
            }

            return  $xml;

        }

        private function validate()
        {
            $msg = '';

            if( ! isset( $this->address ) )
            {
                $msg = 'address parameter is required';
            }
            elseif( strlen( $this->address ) < 1 || strlen( $this->address ) > 128 )
            {
                $msg = 'address must be between 1 - 128 characters in length';
            }

            if( strlen( $msg ) )
            {
                throw new InvalidArgumentException( $msg );
            }

        }
    }