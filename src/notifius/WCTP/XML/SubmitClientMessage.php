<?php

    namespace NotifiUs\WCTP\XML;

    use Carbon\Carbon;
    use SimpleXMLElement;
    use InvalidArgumentException;
    use NotifiUs\WCTP\DeliveryPriority;

    class SubmitClientMessage extends WCTPOperation
    {
        // Required
        protected $submitTimestamp;
        protected $senderID;
        protected $recipientID;
        protected $payload;

        // Optional
        protected $miscInfo;

        // Message Control Options, all optional
        protected $sendResponsesToID;
        protected $allowResponse;
        protected $notifyWhenQueued;
        protected $notifyWhenDelivered;
        protected $notifyWhenRead;
        protected $deliveryPriority;
        protected $deliveryBefore;
        protected $deliveryAfter;
        protected $preformatted;
        protected $allowTruncation;

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

        public function payload( string $payload )
        {
            $this->payload = $payload;
            return $this;
        }

        public function messageControlOptions( array $options )
        {
            $this->sendResponsesToID = $options['sendResponsesToID'] ?? null;
            $this->allowResponse = $options['allowResponse'] ?? null;
            $this->notifyWhenQueued = $options['notifyWhenQueued'] ?? null;
            $this->notifyWhenDelivered = $options['notifyWhenDelivered'] ?? null;
            $this->notifyWhenRead = $options['notifyWhenRead'] ?? null;
            $this->deliveryPriority = $options['deliveryPriority'] ?? null;
            $this->deliveryBefore = $options['deliveryBefore'] ?? null;
            $this->deliveryAfter = $options['deliveryAfter'] ?? null;
            $this->preformatted = $options['preformatted'] ?? null;
            $this->allowTruncation = $options['allowTruncation'] ?? null;

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

            $submitClientMessage = $xml->addChild( 'wctp-SubmitClientMessage' );

            $submitHeader = $submitClientMessage->addChild('wctp-SubmitClientHeader' );
            $submitHeader->addAttribute( 'submitTimestamp', $this->submitTimestamp->timezone('UTC')->format( 'Y-m-d\TH:i:s' ) );

            $payload = $submitClientMessage->addChild( 'wctp-Payload' );
            //wctp-Alphanumeric, wctp-Transparent, and wctp-MCR
            //Transparent is for base64 encoded binary data
            //MCR is for multiple choice response meh
            $alphanumeric = $payload->addChild( 'wctp-Alphanumeric' );
            $alphanumeric[0] = $this->payload; //doing this still seems to escape properly...

            $originator = $submitHeader->addChild('wctp-ClientOriginator' );
            $originator->addAttribute('senderID', $this->senderID );
            if( $this->miscInfo ){ $originator->addAttribute('MiscInfo', $this->miscInfo ); }

            $recipient = $submitHeader->addChild( 'wctp-Recipient' );
            $recipient->addAttribute( 'recipientID', $this->recipientID );

            $messageControl = $submitHeader->addChild('wctp-ClientMessageControl' );

            //optional
            if( ! is_null( $this->preformatted ) ) { $messageControl->addAttribute( 'preformatted', $this->preformatted ? 'true' : 'false' ); }
            if( ! is_null( $this->allowTruncation ) ) { $messageControl->addAttribute( 'allowTruncation', $this->allowTruncation ? 'true' : 'false' ); }
            if( ! is_null( $this->allowResponse ) ) { $messageControl->addAttribute( 'allowResponse', $this->allowResponse ? 'true' : 'false' ); }
            if( ! is_null(  $this->notifyWhenQueued ) ) { $messageControl->addAttribute( 'notifyWhenQueued', $this->notifyWhenQueued ? 'true' : 'false' ); }
            if( ! is_null(  $this->notifyWhenDelivered ) ) { $messageControl->addAttribute( 'notifyWhenDelivered',  $this->notifyWhenDelivered ? 'true' : 'false' ); }
            if( ! is_null(  $this->notifyWhenRead ) ) { $messageControl->addAttribute( 'notifyWhenRead', $this->notifyWhenRead ? 'true' : 'false' ); }
            if( ! is_null(  $this->deliveryPriority ) ) { $messageControl->addAttribute( 'deliveryPriority', $this->deliveryPriority ? 'true' : 'false' ); }

            if( $this->sendResponsesToID ) { $messageControl->addAttribute( 'sendResponsesToID', $this->sendResponsesToID ); }

            if( $this->deliveryBefore ) { $messageControl->addAttribute( 'deliveryBefore', $this->deliveryBefore ); }
            if( $this->deliveryAfter ) { $messageControl->addAttribute( 'deliveryAfter', $this->deliveryAfter ); }



            if( $xml === false || $submitClientMessage === false || $submitHeader === false || $payload === false || $originator === false || $recipient === false || $messageControl === false )
            {
                $errors = libxml_get_errors();
                throw new InvalidArgumentException( $errors[0]->message );
            }

            return  $xml;
        }

        private function validate()
        {
            $msg = '';

            if( ! $this->payload )
            {
                $msg = 'payload parameter is required';
            }
            elseif( ! $this->senderID )
            {
                $msg = 'senderID parameter is required';
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
            elseif( strlen( $this->recipientID ) < 1 || strlen( $this->recipientID ) > 128 )
            {
                $msg = 'recipientID must be between 1 - 128 characters in length';
            }
            elseif( strlen( $this->payload ) < 1 || strlen( $this->payload ) > 65535 )
            {
                $msg = 'payload must be between 1 - 65535 characters in length';
            }
            elseif( ! is_null( $this->deliveryBefore ) && ! ( $this->deliveryBefore instanceof Carbon ) )
            {
                $msg = 'deliveryBefore must be an instance of Carbon date/time library';
            }
            elseif( ! is_null( $this->deliveryAfter ) && ! ( $this->deliveryAfter instanceof Carbon ) )
            {
                $msg = 'deliveryAfter must be an instance of Carbon date/time library';
            }
            elseif( ! is_null( $this->sendResponsesToID ) &&  (strlen( $this->sendResponsesToID ) < 1 || strlen( $this->sendResponsesToID ) > 128) )
            {
                $msg = 'sendResponsesToID must be between 1 - 128 characters in length';
            }
            elseif(
                ! is_null( $this->deliveryPriority ) &&
                ! (
                    $this->deliveryPriority == DeliveryPriority::HIGH ||
                    $this->deliveryPriority == DeliveryPriority::NORMAL ||
                    $this->deliveryPriority == DeliveryPriority::LOW
                )
            )
            {
                $msg = 'deliveryPriority must be one of HIGH, NORMAL, or LOW';
            }
            elseif( ! is_null( $this->allowResponse ) && ! is_bool( $this->allowResponse )  ){
                $msg = 'allowResponse must be a boolean value';
            }
            elseif( ! is_null( $this->allowTruncation ) && ! is_bool( $this->allowTruncation )  ){
                $msg = 'allowTruncation must be a boolean value';
            }
            elseif( ! is_null( $this->notifyWhenDelivered ) && ! is_bool( $this->notifyWhenDelivered )  ){
                $msg = 'notifyWhenDelivered must be a boolean value';
            }
            elseif( ! is_null( $this->notifyWhenQueued ) && ! is_bool( $this->notifyWhenQueued )  ){
                $msg = 'notifyWhenQueued must be a boolean value';
            }
            elseif( ! is_null( $this->notifyWhenRead ) && ! is_bool( $this->notifyWhenRead )  ){
                $msg = 'notifyWhenRead must be a boolean value';
            }
            elseif( ! is_null( $this->preformatted ) && ! is_bool( $this->preformatted )  ){
                $msg = 'preformatted must be a boolean value';
            }

            if( strlen( $msg ) )
            {
                throw new InvalidArgumentException( $msg );
            }
        }

    }