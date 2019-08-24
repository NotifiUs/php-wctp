<?php

    namespace NotifiUs\WCTP\XML;

    use Carbon\Carbon;
    use SimpleXMLElement;
    use InvalidArgumentException;

    class VersionQuery extends WCTPOperation
    {
        protected $inquirer;
        protected $dateTime;

        public function inquirer( $inquirer )
        {
            $this->inquirer = $inquirer;
            return $this;
        }

        public function dateTime( Carbon $dateTime )
        {
            $this->dateTime = $dateTime;
            return $this;
        }

        public function xml(): SimpleXMLElement
        {
            $this->validate();
            libxml_use_internal_errors( true );

            $xml = new SimpleXMLElement( $this->xml_template );

            if( $xml === false )
            {
                $errors = libxml_get_errors();
                throw new InvalidArgumentException( $errors[0]->message );
            }

            if( ! is_null( $this->token ) )
            {
                $xml->addAttribute('wctpToken', $this->token );
            }

            $vq = $xml->addChild( 'wctp-VersionQuery' );
            $vq->addAttribute( 'inquirer', $this->inquirer );

            if( ! is_null( $this->dateTime ) )
            {
                $vq->addAttribute('dateTime', $this->dateTime->timezone('UTC')->format( 'Y-m-d\TH:i:s' ) );
            }

            return  $xml;

        }

        private function validate()
        {
            $msg = '';

            if( ! isset( $this->inquirer ) )
            {
                $msg = 'inquirer parameter is required';
            }
            elseif( strlen( $this->inquirer ) < 1 || strlen( $this->inquirer ) > 128 )
            {
                $msg = 'inquirer must be between 1 - 128 characters in length';
            }

            if( strlen( $msg ) )
            {
                throw new InvalidArgumentException( $msg );
            }

        }
    }