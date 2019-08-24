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


        public function xml( )
        {
            $this->validate();

            libxml_use_internal_errors(true );

            if( ! is_null( $this->dateTime ) )
            {
                $dateTimeString = " dateTime=\"{$this->dateTime->format('Y-m-d\TH:i:s' )}\" ";
            }
            else
            {
                $dateTimeString = '';
            }

            $xml = new SimpleXMLElement(
<<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE wctp-Operation SYSTEM "http://www.wctp.org/release/wctp-dtd-v1r3.dtd">
<wctp-Operation wctpVersion="WCTP-DTD-V1R3">
<wctp-VersionQuery inquirer="{$this->inquirer}" {$dateTimeString} />
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