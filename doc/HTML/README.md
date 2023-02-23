# Ephpai Class                     {#mainpage}
## Une Classe PHP pour intéragir facilement avec l'api d'OpenAI (ChatGPT & Dall-E)
============
it’s an unofficial class and This project has no commercial link with OpenAI.
In order to use it, you need to have an openai account and valid api key from openai :[Get api key from Openai] (https://openai.com/api/)

### MIT License
Copyright (c) 2023 Thomas Missonier (sourcezaxsourcezax@gmail.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## Ephpai Class

This easy PHP Class interacts with the API provided by OpenAI.

It allows, in a limited but simple way, to make requests and obtain answers for the following functions :

* **Completion (text)**:
  Query, response and text generation. Support for "text-davinci-003" (GPT), "text-curie-001", "text-babbage-001" and "text-ada-001" models. Possibility to define the maximum number of tokens. Fine temperature management. Can Export results as json, array or text.

  * **Image Generation**:
Easy switch from completion to image generation.
Management of different image sizes, possibility of saving an image. Can display or save the image in jpeg format. Possibility to get an base64 encoded text or via url 

**Moderation**:
Moderate content and/or image through the API. Ability to automate moderation. Possibility to get the reasons why the openai api have moderated the content.


## How to use it?

It's easy, just create an Ephpai o0bject, execute the request, and retrieve the response.

In order to use the Ephpai class, the OAIPIKEY environment variable must contain your api key (recommended). If you don't have access to environment variables on your server, you can use the **setApikey($key)** method.

### here is a simple example

```
<?php

require "Ephpai.php";

$Requestgpt=new Ephpai('Whois is spiderman?'); // Create object
if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);

?>
```

By default the request type is set to completion (text), and the model to text-davinci-003 (chatgpt).
[About models](https://platform.openai.com/docs/models/overview)

The model can be modified with the method **setModel($model)**.

The number of tokens is fixed by default at 850, it can be changed with the method **setMaxtoken($number)**;

#### Modification of the precious query :
```
<?php require "Ephpai.php";

$Requestgpt=new Ephpai('Whois is spiderman?'); // Create object
$Requestgpt->setModel('text-curie-001'); //model text-curie
$Requestgpt->setMaxtoken(200); //Maxtoken=200

if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);
?>
```

There is a constructor with the model and maxtoken as parameters and allows us to make our task easier :

```
<?php require "Ephpai.php";

$Requestgpt=new Ephpai('Whois is spiderman?','text-curie-001',200); // Create object with model type 'text-curie-001' and maxtoken=200 
if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);
?>
```
The result can be retrieved as a json format text,an array or as a text.

There are several methods for modifying the query parameters, such as temperature management, or retrieving values...

You can find them in the documentation, available on the "doc" directory

## Image generation 

To generate images using Dall-E and the Openai API, you must enable image generation with the **generateImage(true)** method.
To return to completion mode (text), just run this method with false as argument.


#### Example of image display using the GD library:

Note: pay attention to unnecessary spaces.

```
<?php
header("Content-type: image/jpeg");
require "../Ephpai.php";
$Requestgpt=new Ephpai('blue cat');
$Requestgpt->generateImage(true); //Activate image generation
if((!$Requestgpt->setImgsize('1024x1024'))||(!$Requestgpt->executeQuery()))
     die ($Requestgpt->error());
else
{
$imgdata=$Requestgpt->getTextresult(0);
$image = @imagecreatefromstring($imgdata);
imagejpeg($image,null, 75);
//echo $ImageData;
}
?>
``` 

If you don't want to bother with functions from the GD library, there is the **displayImg($nb,$quality)** method, which does all the work.

#### The same example with displayImg method

```
<?php
header("Content-type: image/jpeg");
require "../Ephpai.php";
$Requestgpt=new Ephpai('blue cat');
$Requestgpt->generateImage(true);//
if((!$Requestgpt->setImgsize('1024x1024'))||(!$Requestgpt->executeQuery()))
     die ($Requestgpt->error());
else
{
    if (!$Requestgpt->displayImg(0,100))
    die ($Requestgpt->error());
}
?>
```
**In the same way, the **saveImgtojpeg($nb,$filename,$quality)** allows to save image on your server.

```
<?php
require "../Ephpai.php";
$string='blue cat and pink mouse playing card';
$Requestgpt=new Ephpai($string);
$Requestgpt->generateImage(true);//
if((!$Requestgpt->setImgsize('1024x1024'))||(!$Requestgpt->executeQuery()))
     die ($Requestgpt->error());
else
{
    if (!$Requestgpt->saveImgtojpeg(0,urlencode($string).'.jpg',100))
    die ($Requestgpt->error());
    else 
    echo 'Image '.urlencode($string).'.jpg saved with success';
}
?>
```



