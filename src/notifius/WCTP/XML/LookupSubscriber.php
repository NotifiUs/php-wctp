<?php

    namespace NotifiUs\WCTP\XML;

    use Carbon\Carbon;
    use SimpleXMLElement;
    use InvalidArgumentException;

    class LookupSubscriber extends WCTPOperation
    {
        // Required
        protected $submitTimestamp;
        protected $senderID;
        protected $recipientID;
        protected $messageID;

        // Optional
        protected $miscInfo;
        protected $securityCode;
        protected $transactionID;
        protected $authorizationCode;

        // Message Control Options, all optional
        protected $sendResponsesToID;

        public function submitTimestamp( Carbon $submitTimestamp )
        {
            $this->submitTimestamp = $submitTimestamp;
            return $this;
        }

        public function senderID( $senderID )
        {
            $this->senderID = $senderID;
            return $this;
        }

        public function messageID( $messageID )
        {
            $this->messageID = $messageID;
            return $this;
        }

        public function recipientID( $recipientID )
        {
            $this->recipientID = $recipientID;
            return $this;
        }

        public function miscInfo( $miscInfo )
        {
            $this->miscInfo = $miscInfo;
            return $this;
        }

        public function securityCode( $securityCode )
        {
            $this->securityCode = $securityCode;
            return $this;
        }

        public function authorizationCode( $authorizationCode )
        {
            $this->authorizationCode = $authorizationCode;
            return $this;
        }

        public function transactionID( $transactionID )
        {
            $this->transactionID = $transactionID;
            return $this;
        }

        public function messageControlOptions( array $options )
        {
            $this->sendResponsesToID = $options['sendResponsesToID'] ?? null;

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

            $lookupSubscriber = $xml->addChild( 'wctp-LookupSubscriber' );

            $lookupSubscriber->addAttribute( 'submitTimestamp', $this->submitTimestamp->timezone('UTC')->format( 'Y-m-d\TH:i:s' ) );

            $originator = $lookupSubscriber->addChild('wctp-Originator' );
            $originator->addAttribute('senderID', $this->senderID );
            if( $this->securityCode ){ $originator->addAttribute('securityCode', $this->securityCode ); }
            if( $this->miscInfo ){ $originator->addAttribute('MiscInfo', $this->miscInfo ); }

            $recipient = $lookupSubscriber->addChild( 'wctp-Recipient' );
            $recipient->addAttribute( 'recipientID', $this->recipientID );
            if( $this->authorizationCode ) { $recipient->addAttribute( 'authorizationCode', $this->authorizationCode ); }

            $messageControl = $lookupSubscriber->addChild('wctp-LookupMessageControl' );
            $messageControl->addAttribute('messageID', $this->messageID );

            if( $this->transactionID ) { $messageControl->addAttribute( 'transactionID', $this->transactionID ); }
            if( $this->sendResponsesToID ) { $messageControl->addAttribute( 'sendResponsesToID', $this->sendResponsesToID ); }

            if( $xml === false || $lookupSubscriber === false || $originator === false || $recipient === false || $messageControl === false )
            {
                $errors = libxml_get_errors();
                throw new InvalidArgumentException( $errors[0]->message );
            }

            return  $xml;
        }

        private function validate()
        {
            $msg = '';

            if( ! $this->senderID )
            {
                $msg = 'senderID parameter is required';
            }
            elseif( ! $this->messageID )
            {
                $msg = 'messageID parameter is required';
            }
            elseif( ! $this->recipientID )
            {
                $msg = 'recipientID parameter is required';
            }
            elseif( ! $this->submitTimestamp )
            {
                $msg = 'submitTimestamp parameter is required';
            }
            elseif( strlen( $this->senderID ) < 1 || strlen( $this->senderID ) > 128 )
            {
                $msg = 'senderID must be between 1 - 128 characters in length';
            }
            elseif( strlen( $this->messageID ) < 1 || strlen( $this->messageID ) > 32 )
            {
                $msg = 'messageID must be between 1 - 32 characters in length';
            }
            elseif( strlen( $this->recipientID ) < 1 || strlen( $this->recipientID ) > 128 )
            {
                $msg = 'recipientID must be between 1 - 128 characters in length';
            }
            elseif( ! is_null( $this->transactionID ) &&  (strlen( $this->transactionID ) < 1 || strlen( $this->transactionID ) > 32) )
            {
                $msg = 'transactionID must be between 1 - 32 characters in length';
            }
            elseif( ! is_null( $this->sendResponsesToID ) &&  (strlen( $this->sendResponsesToID ) < 1 || strlen( $this->sendResponsesToID ) > 128) )
            {
                $msg = 'sendResponsesToID must be between 1 - 128 characters in length';
            }

            if( strlen( $msg ) )
            {
                throw new InvalidArgumentException( $msg );
            }
        }

    }