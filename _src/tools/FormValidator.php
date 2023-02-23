<?php
class FormValidator
{
//==============================================================================
// Loop door alle velden in array, als veld ok 
// waarde opnemen in resultaat
// anders error opnemen in resultaat EN totaal resultaat op niet ok (false)
//==============================================================================
protected $uploadpath;
protected $max_width;

public function __construct()
{
    $this->uploadpath = 'assets/shop/'; 
    $this->max_width = 980;
}
//==============================================================================
    public function checkFields(array $fieldinfo) : array
    {
        $result = array();
        $result['ok'] = true;
        foreach ($fieldinfo as $name => $info)
        {
            if($name !== 'image upload')
            {
                $check = $this->checkField($name, $info);
                if ($check['ok'])
                {
                    $result[$name] = $check['value'];
                }	
                else
                {
                    $result['ok'] = false;
                    $result[$name.'_err'] = $check['error'];
                }
            } 
            else
            {
                if(!empty($_SERVER['image_upload']['tmp_name']))
                {
                    $image_name = $this->uniqueFilename();
                    $image_ext = substr($_FILES['image_upload']['type'], strpos($_FILES['image_upload']['type'], "/") + 1);  
                    if($this->saveFile($_FILES['image_upload']['tmp_name'], $image_name, $image_ext))
                    {
                        $result['ok'] = true;
                        $result[$name] = $image_name.'.'.$image_ext;
                    }
                    else
                    {
                        $result['ok'] = false;
                    }
                }
            }    			
        }
        return $result;
    }
//==============================================================================
    protected function checkField(string $fieldname, array $fieldinfo) : array
    {
        $result = array();
        $result['ok'] = false;
        
        if (isset($_POST[$fieldname]))
        {
            $value = Tools::getRequestVar($fieldname, true, '');
            if (empty($value))
            {
                $result['error'] = $fieldinfo['label'].' is leeg.';
            }
            else
            {
                if (isset($fieldinfo['check_func']) && !empty($fieldinfo['check_func']))
                {
                    $valid = call_user_func([$this, $fieldinfo['check_func']], $value);
                    if ($valid)
                    {
                        $result['ok'] = true;
                        $result['value'] = $value;
                    }
                    else
                    {
                        $result['error'] = $fieldinfo['label'].' is ongeldig.';
                    }			
                }		
                else
                {	
                    $result['ok'] = true;
                    $result['value'] = $value;
                }	
            }	
        }
        else
        {
            $result['error'] = $fieldname.' niet gevonden.';
        }
        return $result;
    }
//==============================================================================    
    protected function validEmail(string $value) : bool
    {
	return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
//==============================================================================    
    protected function uniqueFilename() : string 
        { 
            // explode the IP of the remote client into four parts

            $ipbits = explode(".", $_SERVER['REMOTE_ADDR']);
            // Get both seconds and microseconds parts of the time
            list($usec, $sec) = explode(" ",microtime());
            // Fudge the time we just got to create two 16 bit words
            $usec = (integer) ($usec * 65536);
            $sec = ((integer) $sec) & 0xFFFF;
            // Fun bit - convert the remote client's IP into a 32 bit
            // hex number then tag on the time.
            // Result of this operation looks like this xxxxxxxx-xxxx-xxxx
            return sprintf("%08x-%04x-%04x",
                 ($ipbits[0] << 24) 
                |($ipbits[1] << 16) 
                |($ipbits[2] << 8) 
                | $ipbits[3], $sec, $usec);
        }

//==============================================================================    
    protected function saveFile(string $tmp_fn, string $fn,string $extension='') : bool|array
        {
            try
            {

                list($width, $height) = getimagesize($tmp_fn); 
                $max_width = ($width <= $this->max_width ? $width : $this->max_width);
                $percent    = $max_width/$width; 
                $newwidth   = $width * $percent; 
                $newheight  = $height * $percent; 
                $thumb = imagecreatetruecolor($newwidth, $newheight); 
               
                switch ($extension)
                {
                    case 'jpg':
                    case 'jpeg': 
                        $source = imagecreatefromjpeg($tmp_fn);
                         
                        break;
                    case 'gif': 
                        $source = imagecreatefromgif($tmp_fn);
                        break;
                    case 'png': 
                        $source = imagecreatefrompng($tmp_fn); 
                        break;
                    default:
                        $source = null;
                        $result['error'] = "UNKNOWN EXTENSION [".$extension."]";
                        $result['ok'] = false;
                        return $result;
                }
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
                $save_as =  $this->uploadpath.$fn.'.'.$extension;
                switch ($extension)
                {
                    case 'jpg':
                    case 'jpeg': 
                        return imagejpeg($thumb,$save_as);
                    case 'gif': 
                        return imagegif($thumb, $save_as);
                    case 'png': 
                        return imagepng($thumb, $save_as);
                }
                $result['ok'] = true;
                return $result;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
                return $result;
            }
        }
//==============================================================================
}
