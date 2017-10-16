<?php

/**
 * Handles encoding of incoming user input, decoding, and making
 * safe for output
 *
 */
class Input_Sanitize
{
    /**
     * Makes user input safe for output in terms of preventing execution of HTML
     *
     * Supported Input Types: bool, numeric, string or array
     *
     * We run a decode twice in case data has been double entity encoded.
     * For example encoded pre DB storage and again on retrieval.
     *
     * @param mixed $val The value to be made safe
     * @return mixed
     */
    public static function makeSafeForOutput($val)
    {
        $input = new Input_Sanitize();
        $val = $input->decode($val);
        return $input->encode($val);
    }
    public static function convertToCompatHtml($val)
    {
        $input = new Input_Sanitize();
        $val = $input->decode($val);
        $val = $input->decode($val);
        return $input->encodeToCompatHTML($val);
    }
    /**
     * Encodes an object using HTML Special Chars
     *
     * Supported Input Types: bool, numeric, string or array
     * For any other input type returns blank
     *
     * @param mixed $val The value to be encoded
     *
     * @return mixed
     */
    public static function encode($val)
    {
        $input = new Input_Sanitize();
        if( is_bool($val) )
        {
            return $val;
        }
        if( is_numeric($val) )
        {
            return $val;
        }
        if( is_string($val) )
        {
            return $input->encodeString($val);
        }
        if( is_array($val) )
        {
            return $input->encodeArray($val);
        }
        if( is_object($val) )
        {
            return $val;
        }
        return '';
    }
    /**
     * Encodes an object using HTML Special Chars - no ENT_QUOTES flag
     *
     * Supported Input Types: bool, numeric, string or array
     * For any other input type returns blank
     *
     * @param mixed $val The value to be encoded
     *
     * @return mixed
     */
    public static function encodeToCompatHTML($val)
    {
        $input = new Input_Sanitize();
        if( is_bool($val) )
        {
            return $val;
        }
        if( is_numeric($val) )
        {
            return $val;
        }
        if( is_string($val) )
        {
            return $input->encodeStringToCompatHTML($val);
        }
        if( is_array($val) )
        {
            return $input->encodeArrayToCompatHTML($val);
        }
        if( is_object($val) )
        {
            return $val;
        }
        return '';
    }
    /**
     * Decodes an object using HTML Entity Decode
     *
     * Supported Input Types: bool, numeric, string or array
     * For any other input type returns blank
     *
     * @param mixed $val The value to be decoded
     *
     * @return mixed
     */
    public static function decode($val)
    {
        $input = new Input_Sanitize();
        if( is_bool($val) )
        {
            return $val;
        }
        if( is_numeric($val) )
        {
            return $val;
        }
        if( is_string($val) )
        {
            return $input->decodeString($val);
        }
        if( is_array($val) )
        {
            return $input->decodeArray($val);
        }
        if( is_object($val) )
        {
            return $val;
        }
        return '';
    }
    /**
     * Loops through an array performing the encode action
     *
     * @param array $array The array to be encoded
     *
     * @return array
     */
    protected function encodeArray($array)
    {
        foreach( $array as $k => $v )
        {
            $array[$k] = $this->encode($v);
        }
        return $array;
    }
    /**
     * Loops through an array performing the encode action
     *
     * @param array $array The array to be encoded
     *
     * @return array
     */
    protected function encodeArrayToCompatHTML($array)
    {
        foreach( $array as $k => $v )
        {
            $array[$k] = $this->encodeToCompatHTML($v);
        }
        return $array;
    }
    /**
     * Loops through an array performing the decode action
     *
     * @param array $array The array to be decoded
     *
     * @return array
     */
    protected function decodeArray($array)
    {
        foreach( $array as $k => $v )
        {
            $array[$k] = $this->decode($v);
        }
        return $array;
    }
    /**
     * Perform HTML Special Chars on a string
     *
     * @param string $val The string to be entity encoded
     *
     * @return string
     */
    protected function encodeString($val)
    {
        return htmlspecialchars($val, ENT_QUOTES);
    }
    /**
     * Perform HTML Special Chars on a string
     *
     * @param string $val The string to be entity encoded
     *
     * @return string
     */
    protected function encodeStringToCompatHTML($val)
    {
        static $mask;
        if( !isset($mask) )
        {
            $mask = $this->getCompatBitmask();
        }
        return htmlspecialchars($val, $mask);
    }
    public function getCompatBitmask()
    {
        $mask = ENT_COMPAT;
        if( defined('ENT_HTML401') )
        {
            $mask = $mask | ENT_HTML401;
        }
        return $mask;
    }
    /**
     * Perform an HTML Entity Decode on a String
     *
     * @param string $val The string to be entity decoded
     *
     * @return string
     */
    protected function decodeString($val)
    {
        $val = str_replace("&nbsp;", " ", $val);
        return html_entity_decode($val, ENT_QUOTES);
    }
}