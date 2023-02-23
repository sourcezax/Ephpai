<?php 
    /** 
    *@file        Ephpai.php
    *
    *@brief     Ephpai :Simple and easy to use Class to interact with OpenAI api (ChatGPT & Dall-E)
    *@author  Thomas Missonier
    *@version     0.1
    * @date        2023
    * @copyright   MIT License
    * @details Copyright © 2023, Thomas Missonier (sourcezax@gmail.com)
    * 
    * it’s an unofficial class and This project has no commercial link with Openai.
    * Needs to have an openai account and valid api key from openai : https://openai.com/api/ 
    *
    * MIT License
    * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the
    * Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
    * Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    * 
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
    * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
    * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
    */

    //OpenAI API COMPLETION URL
    define ("API_COMPLETION_URL",'https://api.openai.com/v1/completions');
    //OpenAI API IMAGE GENERATION URL
    define ("API_IMAGE_GENERATION_URL",'https://api.openai.com/v1/images/generations');
    //OpenAI API IMAGE GENERATION URL
    define ("API_MODERATION_URL",'https://api.openai.com/v1/moderations');
    //Possible sizes of generated images
    //define ("IMG_SIZES","256x256,512x512,1024x1024");
    define ('IMG_SIZES',array ('256x256','512x512','1024x1024'));
    //Type of models for completion
    define ('MODEL_TYPES',array('text-davinci-003','text-curie-001','text-babbage-001','text-ada-001'));
    /**
     * Ephpai Class
     */
    class Ephpai
    {
        //private Variables
            private $apikey;
            private $query='';
            private $model='text-davinci-003';
            private $temperature=0.6;
            private $maxtoken=850;
            private $nbresults=1;
            private $answer;
            //Image generation vars
            private $img_generation=false;
            private $img_size="512x512";
            private $img_type="b64_json";
            private $img_sizes_values=IMG_SIZES;
            private $model_types=MODEL_TYPES;
            private $moderation_flagged=false;
            private $moderation_enabled=false;
            private $moderation_reasons=[];
            private $error='';
            //Public variables   
            //Changed Error to private
            //No public variable
            
            //Constructors
            
        /** Constructor of Ephpai Class. 
         * Redirects to the sub-constructors depending on the number of arguments given.
         *@param string query 
         *@param string model [optionnal except if maxtoken is set] 
         *@param number maxtoken
         *@see __construct1,__construct2,__construct3
         */
        public function __construct() 
        {
            $arguments       = func_get_args();
            $nbargs = func_num_args();

            if (method_exists($this, $method_name = '__construct'.$nbargs)) 
            {
                call_user_func_array(array($this, $method_name), $arguments);
            }
        }
        
        /**
         *Constructor with only Query as argument.
         *@param string query
         *@see __construct() 
         */
        public function __construct1($query) {
            $this->query=$query;
                }
        /**
        * Constructor with query and model as arguments.
        *@param  string $query 
        *@param  string $model 
        *
        *$model needs to be in ['text-davinci-003','text-curie-001','text-babbage-001','text-ada-001'].
        *@see https://platform.openai.com/docs/models/overview
        */
        public function __construct2($query,$model) 
        {
            $this->query=$query;
            $this->model=$model;
        } 
        /**
         * Constructor with query,model and maxtoken as arguments.
        *@param string $query 
        *@param string $model
        *@param number $maxtoken
        *
        * $model needs to be in ['text-davinci-003','text-curie-001','text-babbage-001','text-ada-001'].
        *@see https://platform.openai.com/docs/models/overview
        */
        public function __construct3($query,$model,$maxtoken) 
        {
            $this->query=$query;
            $this->model=$model;
            $this->maxtoken=$maxtoken;
        }
        /** Destructor of Ephpai
         */
        public function __destruct() 
        {
                unset($this->answer );
                unset ($this->moderated_categories);
                
            }
        /**
         * Sets the query.
        * @param string $query (utf-8 encoded)
        * @return true of false
        *
        */
        public function setQuery($query)
            {
                if (!empty($query))
                {
                    $this->query=$query;
                    return true;
                }
                else return $this->exit_error(__METHOD__,'Empty query');
            }
            /**
        * Returns the query.
        * @param None
        * @return string containing the query
        *
        */
            public function getQuery()
            {
            return ($this->query);
            }
            /** 
             * Set temperature.
             * Note : Temperature is an important setting to control how much randommes is the result of a query.
             * It needs to be between 0 and 1.
             * @see https://platform.openai.com/docs/api-reference/completions/create
             * @param  number temp
             * @return ture or false
             */
            
            public function setTemperature($temp)
            {
                if (is_numeric($temp)&&($temp>0)&&($temp<=1))
                {
                    $this->temperature=$temp;
                    return true;
                }
                return $this->exit_error(__METHOD__,'Bad value for temperature');
            }
            
            /**
             * Returns temperature.
             * Note : Temperature is an important setting to control how much randommes is the result of a query.
             * It needs to be between 0 and 1.
             * @see https://platform.openai.com/docs/api-reference/completions/create
             * @param  none
             * @return temperature 
             */
            public function getTemperature()
            {
                return $this->temperature;
            }
            /**
             * Increases temperature by step for completion.
             * Note: : Temperature is an important setting to control how much randommes is the result of a query
             * 
             * if (temperature+STEP)>1 temperature will be set to 1. 
             *
             * @param number step
             * @return true of false 
             */
            public function increaseTemperature($step)
            {
                if(is_numeric($step)&&($step>=0)&&($step<=1))
                {
                $this->temperature+=$step;
                if ($this->temperature>1)
                $this->temperature=1;
                return true;   
            }
                return $this->exit_error(__METHOD__,'Bad value for temperature step');
            }
            /**
             * Decreases temperature by step for completion.
             * Note : Temperature is an important setting to control how much randommes is the result of a query
             * 
             * if (temperature-STEP)<0 temperature will be set to 0
             *  
             * @param number step
             * @return true of false; 
             */
            public function decreaseTemperature($step)  
            {
                if(is_numeric($step)&&($step>=0)&&($step<=1))
                {
                $this->temperature-=$step;
                if ($this->temperature<0)
                    $this->temperature=0;
                return true;   
            } 
            return $this->exit_error(__METHOD__,'Bad value for temperature step');
            }
            
        /** 
        * Returns the model type. 
        * @param none
        * @return string 
        */
        public function getModel()
        {
            return  $this->model;
        }
        /**
         * Set the model of image($img_type).
         * @param string $model
         * @return true or false;
         * 
         *$model needs to be in ['text-davinci-003','text-curie-001','text-babbage-001','text-ada-001']
         *@see https://platform.openai.com/docs/models/overview
         */
        public function setModel($model)
        {
            // if (($model=='text-davinci-003')||($model=='text-curie-001')||($model=='text-babbage-001')||($model=='text-ada-001'))
            if (in_array($model,$this->model_types))
            { 
                $this->model=$model;
                return true;
            }
            return $this->exit_error(__METHOD__,'Bad model type parameter');
        }
        /**
         * Defines the environment variable containing the API key.
         * @param string $key
         * @return true or false
         */
        public function setApikey($key)
        {
            if (!empty($key))
            {
                $envar="OAIPIKEY=".$key;
                putenv($envar);
                return true;
            }
            return  $this->exit_error(__METHOD__,'Empty Api key');
        }
    
        /**
        *Returns error message.
        *@param none
        *@return string
        */
        public function error()
        {
            if (!empty($this->error))
                return $this->error;
            else
                return 'No error';
        }
        /**
        *Returns Maxtoken value.
        *@param none
        *@return number
        *
        function getMaxtoken()
        {
            return $this->maxtoken;
        }
        
        /**
         *Sets maxtoken value.
         *@param number $maxtoken
         *@return true or false
         */
        public function setMaxtoken($maxtoken)
        {
            if (is_numeric($maxtoken))
            {
                $this->maxtoken=$maxtoken;
                return true;
            }
            return  $this->exit_error(__METHOD__,'Not a numeric argument');
        }
        
        /**
         *Defines the desired number of results.
         *@param number $results
         *@return true or false
         */
        public function setNbresults($nbresults)
        {
            if (is_numeric($nbresults))
            {
                $this->nbresults=$nbresults;
                return true;
                }
                return  $this->exit_error(__METHOD__,'Not a numeric argument');
        }
        //Image Generation
        /**
         *Activates image generation. Set to true to perform image generation.
         *@param true or false
         *@return no return
         */
        public function generateImage($imgstatus)
        {
            $this->img_generation=$imgstatus;
        }
        
        /**
         * Sets the image size
         *
         * Allowed sizes :'256x256','512x512','1024x1024'
         * generateImage(true) needs to be called before
         *@param string $size
         *@return true or false
         * @see generateImage
         */
        public function setImgsize($size) 
        {
            
            if (!$this->img_generation)
            {
                    return  $this->exit_error(__METHOD__,'Image generation is not enabled. Use generateImage() first');
            }
            
            if (in_array($size,$this->img_sizes_values))
            { 
                $this->img_size=$size;
                return true;
            }
            
            return  $this->exit_error(__METHOD__,'Bad Image size. Allowed :'.implode(' ',$this->img_sizes_values));
        }
        
        /** 
         * Returns the image size.
         * @param none
         * @return string
         */
          public function getImgsize()
        {
            return $this->img_size;
        }
        
        /** 
         * Sets the type of image. Image can be an url or a bs64-json encoded string.
         * @param string $type
         * @return true or false
         */
        public function setImgtype($imagetype)
        {
            if (!$this->img_generation)
            {
                return  $this->exit_error(__METHOD__,'Image generation is not enabled. Use generateImage() first');
            }
            if (($imagetype=='url')||($imagetype=='b64_json'))
            { 
                $this->img_type=$imagetype;
                return true;
            }
            return  $this->exit_error(__METHOD__,'Unkown error');
        }       
        /**
         * Returns the type of image.
         * @param none
         *@return string
         */
        public function getImgtype()
        {
            return $this->img_type;
        }
       
       /**
        * Render image to screen (Jpeg format 100% quality)
        * 
        * Headers need to be set . ex :header("Content-type: image/jpeg");
        * @param number $number 
        * @param number $quality (between 0-100, or -1 to use the default IJG quality (about 75)
        * @return true or false
        */
       public function displayImg($nb,$quality)
       {
           if (!$this->img_generation)
            {
                return  $this->exit_error(__METHOD__,'Image generation is not enabled. Use generateImage() first');
            }
           $imgstring=$this->getTextresult($nb);
        if (!empty($imgstring))
        {
            $image = @imagecreatefromstring($imgstring);
           if (!$image)
              return  $this->exit_error(__METHOD__,'Create image from string error'); 
            if (!imagejpeg($image,null,$quality))
                return  $this->exit_error(__METHOD__,'Error during creation of jpeg image');
            
             imagedestroy($image);
            return true;
            
        }
        return  $this->exit_error(__METHOD__,'No result found. String is empty');
       }
     /** Save image to jpeg file
      * 
      * @param number number
      * @param string filename (not empty)
      * @param number quality  (between 0-100, or -1 to use the default IJG quality (about 75)
      */
     public function saveImgtojpeg($nb,$filename,$quality)
      {
           if (!$this->img_generation)
            {
                return  $this->exit_error(__METHOD__,'Image generation is not enabled. Use generateImage() first');
            }
           $imgstring=$this->getTextresult($nb);
        if ((!empty($imgstring))&&(!empty($filename)))
        {
            $image = @imagecreatefromstring($imgstring);
           if (!$image)
              return  $this->exit_error(__METHOD__,'Create image from string error'); 
            if (!imagejpeg($image,$filename,$quality))
                return  $this->exit_error(__METHOD__,'Error during creation of jpeg file');
            
             imagedestroy($image);
            return true;
            
        }
              return  $this->exit_error(__METHOD__,'No result found. String is empty');
       }
       //End of image generation
       //Moderation methods
        /**
         * Enable or disable auto moderation.
         * @param bool $choice
         * @return no return
        */
        public function Moderation_auto($choice)
            {  if ($choice)
            $this->moderation_enabled=true;
            else
            $this->moderation_enabled=false;
            }
        /**
         * Returns moderation flag
         *If a query has been moderated, returns true otherwise false
         *@param none
         *@return true or false
         */    
        public function moderation_status()
        {
            return $this->moderation_flagged;
        }
        /**
         * Reasons of moderation. If a query has been moderated, an array containing the different reasons is returned.
         * 
         * It lacks moderation results by categories in order to keep it simple to use
         * @param none
         * @return array or false
         * */
        public function moderated_categories()
        {
            if (!($this->moderation_flagged))
                return  $this->exit_error(__METHOD__,'No moderation flag');   
        return $this->moderation_reasons;
        }
        
        /**
         *Moderate the query. Allows to moderate the content before making a real request.
         * If the query is moderated, the query is flagged.
         * @param none
         * @return true or false
        */
        public function ModerateQuery()
        {
            //initiation of moderation values;
            $this->moderation_flagged=false;
            $this->moderation_reasons=[];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, API_MODERATION_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('input'=>$this->query)));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Cache-Control: no-cache",
                "content-type:application/json;charset=utf-8",
                    "Authorization: Bearer ".$this->getApikey()
                ));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
            //Check SSLsetImgtype
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $moderation_answer = curl_exec($ch);
            if (curl_errno($ch)) 
            {
            $moderation_answer='';
            curl_close($ch);
            return  $this->exit_error(__METHOD__,'Curl Error :'.curl_error($ch));
            }
        curl_close($ch);
        
        // print_r($moderation_answer);
        $moderationarray=json_decode($moderation_answer,true);
        //print_r($moderationarray);
            

            if (!empty($moderationarray) && array_key_exists('results', $moderationarray)) 
            {
            $flagresult=$moderationarray['results'][0]['flagged'];
            if (!empty($flagresult)&&($flagresult=="1"))
                $this->moderation_flagged=true;
                else
                    $this->moderation_flagged=false;
        // print_r($moderation_answer); 
            }  
            //Store the reasons of moderation flag
            foreach($moderationarray['results'][0]['categories'] as $key => $value)        
            {
                if ($value=="1")
                $this->moderation_reasons[]=$key;
                }
           /* Debug
            echo "<br >Arraymoderation :<br />";
            print_r($moderationarray);
            echo "<br >reasons :<br />";
            print_r ($this->moderation_reasons);
            echo "<br >Flagged :<br />";
            print_r ($this->moderation_flagged);
            */
            return true;
            
        }
        /**
         * Executes query. It sends query to Openai api using curl.
         * Note : if  auto Moderation is enabled, a query will be send to moderation before and will fail if moderated
         * @see  Moderation_auto($choice)
         * 
         * @param none
         * @return true or false
         */
        public function executeQuery()
        {
            
            if ($this->moderation_enabled)
            {
                if ($this->ModerateQuery())
                {
                    if ($this->moderation_flagged)
                    return $this->exit_error(__METHOD__,"Moderated");
                } else
                return  $this->exit_error(__METHOD__,"Moderation failed");
            }
                $ch = curl_init();
            if (!$this->img_generation)
            {
                curl_setopt($ch, CURLOPT_URL, API_COMPLETION_URL);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('model'=>$this->model, 'prompt'=> $this->query, 'temperature'=>$this->temperature,'max_tokens'=>$this->maxtoken,'n'=>$this->nbresults)));
            }
            
                else
                {
                        curl_setopt($ch, CURLOPT_URL, API_IMAGE_GENERATION_URL);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('size'=>$this->img_size, 'prompt'=> $this->query,'response_format'=>$this->img_type,'n'=>$this->nbresults)));
                }
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Cache-Control: no-cache",
                "content-type:application/json;charset=utf-8",
                    "Authorization: Bearer ".$this->getApikey()
                ));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
            //Check SSLsetImgtype
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $this->answer = curl_exec($ch);
            if (curl_errno($ch)) 
            {
            $this->answer='';
            curl_close($ch);
            return  $this->exit_error(__METHOD__,'Curl Error :'.curl_error($ch));
            }
        curl_close($ch);
        return true;
        }
        /**
         * Returns the answer to query from Openai api in Json format.
         * @param none
         * @return string
         */
        public function getJsonresults()
        { 
            return $this->answer;     
        }
        /**
         * Returns the answer to query from Openai api in array form.
         * @param none
         * @return string
         */
        public function getArrayresults()
        {
            return json_decode($this->answer,true);
        }
        /**
         * Returns the answer ($n) to query from Openai api in string format
         * @param number $n
         * @return string
         */
        public function getTextresult($n)
        {
            $arrayresults=$this->getArrayresults();
            if (!$this->img_generation)
            { if (!empty($arrayresults) && array_key_exists('choices', $arrayresults)) 
            {
            return($arrayresults['choices'][$n]['text']);
            
            } 
            }
            else
            { if (!empty($arrayresults) && array_key_exists('data', $arrayresults)) 
            {
                if ($this->img_type=='b64_json')
                return (base64_decode($arrayresults['data'][$n]['b64_json']));
                else 
                return ($arrayresults['data'][$n]['url']);    
            } 
            }
            
            return  $this->exit_error(__METHOD__,'No available result or error');
        
        }    
        //private methods
        /**
         * Returns false and store method name and message to $this->error
         *@param string $methodename :
         *@param string $$msg
         * @return false
        */
        private function exit_error($methodename,$msg)
        {
        $this->error=$methodename."() : ".$msg;
        return false;
        }
        private function getApikey()
            {
                return getenv("OAIPIKEY");
            }
    }        
?>
