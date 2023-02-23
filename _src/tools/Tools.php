<?php
abstract class Tools
{
//==============================================================================
    public static function getSesVar(string $key, mixed $default="") : mixed
    {
        return self::getValueFromArray($key, $_SESSION, $default);
    }    
//==============================================================================
    public static function setSesVar(string $key, mixed $value) : void
    {
        $_SESSION[$key] = $value;
    }    
//==============================================================================
    public static function _getVar($name, $default="NOPPES")
    {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }
//==============================================================================
    public static function getRequestVar(string $key, bool $frompost, $default="", bool $asnumber=FALSE) : mixed
    {
        $filter = $asnumber ? FILTER_SANITIZE_NUMBER_FLOAT : FILTER_SANITIZE_STRING;
        $result = filter_input(($frompost ? INPUT_POST : INPUT_GET), $key, $filter);
        return ($result===FALSE || $result===NULL) ? $default : $result;
    } 
//==============================================================================
    public static function getValueFromArray(string $key, array $arr, mixed $default='') : mixed
    {
        return (isset($arr[$key])
                ? $arr[$key]
                : $default);
    }        
//==============================================================================
    public static function dump($var) : void
    {
        echo '<pre>'. var_export($var, true).'</pre>';
    }
//==============================================================================
    public static function hex2str(string $str) : string
    {
        $val = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15);
        $result = "";
        $l = strlen($str);
        for ($i=0;$i<$l;$i+=2)
        {
            $result .= chr( ($val[$str[$i]]*16) + $val[$str[$i+1]] );
        }
        return $result;
    }        
//==============================================================================
    public static function str2hex(string $str) : string
    {
        $val = array(0=>'0',1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',10=>'A',11=>'B',12=>'C',13=>'D',14=>'E',15=>'F');
        $result = "";
        $l = strlen($str);
        for ($i=0;$i<$l;$i++)
        {
            $result .= $val[ ord($str[$i]) / 16];
            $result .= $val[ ord($str[$i]) % 16];
        }
        return $result;
    }        
//==============================================================================
    public static function garble(string $str, string $key) : string
    {
        $ky = str_replace(chr(32),'',$key); 
        $kl = strlen($ky)<32 ? strlen($ky) : 32; 
        $k = array();
        for($i=0;$i<$kl;$i++)
        { 
                $k[$i] = ord($ky[$i]) & 0x1F;
        } 
        $j=0;
        for($i=0;$i<strlen($str);$i++)
        { 
                $e = ord($str[$i]); 
                $str[$i] = $e & 0xE0 ? chr($e^$k[$j]) : chr($e); 
                $j++;
                $j = $j==$kl ? 0 : $j;
        } 
        return $str; 
    } 
//==============================================================================
    public static function nicePrice(string $price, string $cur  = '&euro;') : string
    {
        return sprintf($cur."&nbsp;%01.2f",$price);
    }    
//==============================================================================
    public static function niceDate(date $date) : string
    {
        return sprintf($cur."&nbsp;%01.2f",$price);
    }    
//==============================================================================
    public static function niceNumber(int $number) : string
    {
        return sprintf(number_format($number,1));
    }    
//==============================================================================

}    