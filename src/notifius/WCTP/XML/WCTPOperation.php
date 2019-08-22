<?php

    namespace NotifiUs\WCTP\XML;

    use Exception;
    use SimpleXMLElement;

    class WCTPOperation
    {

        //next step
        // use magic method calls to split out this into own function
        // something like public function __call( $classname ){ blah::$classname}

        public static function ClientQuery( array $options )
        {
            // Mandatory disposition (WCTP verbiage for required parameters)
            if( ! isset( $options['senderID']) ){ throw new Exception('senderID parameter is required'); }
            if( ! isset( $options['recipientID']) ){ throw new Exception('recipientID parameter is required'); }
            if( ! isset( $options['trackingNumber']) ){ throw new Exception('trackingNumber parameter is required'); }

            // Values cannot be 0 length or empty
            if( ! strlen( $options['senderID']) ){ throw new Exception('senderID cannot be empty'); }
            if( ! strlen( $options['recipientID']) ){ throw new Exception('recipientID cannot be empty'); }
            if( ! strlen( $options['trackingNumber']) ){ throw new Exception('trackingNumber cannot be empty'); }

            // senderID cannot be greater than 128 characters
            if( strlen( $options['senderID']) > 128 ){ throw new Exception('trackingNumber must be between 1 - 128 characters in length'); }

            // recipientID cannot be greater than 128 characters
            if( strlen( $options['recipientID']) > 128 ){ throw new Exception('trackingNumber must be between 1 - 128 characters in length'); }

            // trackingNumber cannot be greater than 16 characters
            if( strlen( $options['trackingNumber']) > 16 ){ throw new Exception('trackingNumber must be between 1 - 16 characters in length'); }

            // trackingNumber must be a WCTP string type (alphanumeric + allowed characters + escaped xml)
            //$valid = [
            //    ";" , "@" , "|" , "," , "[" , "]" , ":" , "{" , "}" , "^"
            //];

            // but we can't enforce this if we aren't going to enforce the recipient ID stuff....
            //if( ! ctype_alnum( str_replace( $valid, '', $options['trackingNumber'] ) ) ){ throw new Exception('Invalid trackingNumber format. Please see Section 9.4.18 at http://www.wctp.org/release/wctp-v1r3_update1.pdf'); }

            libxml_use_internal_errors(true );

            $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><wctp-Operation wctpVersion=\"WCTP-DTD-V1R3\">
                    <wctp-ClientQuery
                        senderID=\"{$options['senderID']}\"
                        recipientID=\"{$options['recipientID']}\"
                        trackingNumber=\"{$options['trackingNumber']}\"
                    />
                </wctp-Operation>");

            if( $xml === false ){
                $errors = libxml_get_errors();
                throw new Exception( $errors[0]->message );
            }

            return  $xml;
        }
    }