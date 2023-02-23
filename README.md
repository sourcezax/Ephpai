# Ephpai Class                    

## A PHP Class to easily interact with the OpenAI API (ChatGPT & Dall-E)



it’s an unofficial class and This project has no commercial link with OpenAI.

In order to use it, you need to have an OpenAI account and valid api key from OpenAI :[Get api key from OpenAI] (https://openai.com/api/)

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
  Query, response and text generation. Support for "text-davinci-003" (cahtGPT), "text-curie-001", "text-babbage-001" and "text-ada-001" models. Possibility to define the maximum number of tokens. Fine temperature management. Can Export results as json, array or text.

* **Image Generation**:
Easy switch from completion to image generation.
Different image sizes, possibility of saving an image. Can display or save the image in jpeg format. Possibility to get an base64 encoded text or an url 

* **Moderation**:
Moderate contents and/or images through the API. Ability to automate moderation. Possibility to get the reasons of openAI moderation.


## How to use it?

It's easy, just create an Ephpai object, execute the request, and retrieve the response.

In order to use the Ephpai class, the OAIPIKEY environment variable must contain your api key (recommended). If you don't have access to environment variables on your server, you can use the **setApikey($key)** method.

### here is a simple example

```
<?php

require "Ephpai.php";

$Requestgpt=new Ephpai('Who is Spiderman?'); // Create object
if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);

?>
```

Here is a sample answer :
```
Result : Spider-Man is a Marvel Comics superhero created by Stan Lee and Steve Ditko in 1962. He is a costumed crime fighter with superhuman strength, agility, and the ability to cling to most surfaces. He uses his powers to protect the innocent and fight crime in New York City.
```

By default the request type is set to completion (text), and the model to text-davinci-003 (chatGPT).
[About models](https://platform.openai.com/docs/models/overview)

The model can be modified with the method **setModel($model)**.

The number of tokens is fixed by default at 850, it can be changed with the method **setMaxtoken($number)**;

#### Modification of the previous query :
```
<?php require "Ephpai.php";

$Requestgpt=new Ephpai('Who is Spiderman?'); // Create object
$Requestgpt->setModel('text-curie-001'); //model text-curie
$Requestgpt->setMaxtoken(200); //Maxtoken=200

if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);
?>
```

Sample Answer :
```
Result : Spiderman is a superhero who first appeared in the comic book series "Amazing Fantasy" in 1962. He is known for his webshooters, Spiderman suits, and his ability to climb walls.
```

There is a constructor with the model and maxtoken as parameters and allows us to make our task easier :

```
<?php require "Ephpai.php";

$Requestgpt=new Ephpai('Who is Spiderman?','text-curie-001',200); // Create object with model type 'text-curie-001' and maxtoken=200 
if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);
?>
```
The result can be retrieved as a json format text,an array or as a text.

There are several methods for modifying the query parameters, such as temperature management, or retrieving values...

You can find them in the documentation, available in the "doc" directory (HTML & pdf)

## Image generation 

To generate images using Dall-E and the OpenAI API, you need to enable image generation with the **generateImage(true)** method.
To return to completion mode (text), just run this method with false as argument.


#### Example of image display using the GD library :

Note: pay attention to unnecessary spaces.
In this example, we ask a "cat blue" image and display it. It s a simple example.

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
## In the same way, the **saveImgtojpeg($nb,$filename,$quality)** allows to save image on your server in jpeg format.

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
**Image size**: the OpenAI API allows a limited number of image sizes: 256x256,512x512 and 1024x1024.

For more information on the methods related to the generation of images, you will find more information in the documentation present in the doc directory.

## Moderation

It is recommended to use the moderation API provided by OpenAI in order not to submit requests that could be against OpenAI rules, especially if yours users can submit content.

Without making the request, It is possible to check that the content is in compliance with moderation.

Note that the moderation API is valid for any type of text, whether for an image or text.

This is a functional but limited implementation of the functionalities of moderation.

If it is possible to know the reasons for a moderation in the form of an array, the respective scores of each reason (numerical values) are not returned.

#### Example of moderation api. This request should be moderated :

```
<?php

require "../Ephpai.php";

$Requestgpt=new Ephpai('I want to hit you. i hate you'); // Create object
//Asks to moderation api to evaluate query.
if ($Requestgpt-> ModerateQuery()) 
     //Depending the result, the moderation status will be set to true (moderated) or false (no moderated).
     if ($Requestgpt->moderation_status())
        echo "I'm moderated";
     else
          echo "I'm correct";
     else
          echo  $Requestgpt->error();
?>
```
This sentence will be moderated. "I'm moderated" will be displayed.

Note : If moderation works correctly for the English language, support for other languages is limited.


It is possible to know the reason(s) for the moderation in the form of an array. Replace the line in the example above
```
echo "I'm moderated";
```

by 

```
 {
          echo "I'm moderated. Reason(s) :";
          foreach($Requestgpt->moderated_categories() as $reason)
               echo $reason;
        }
```
Here is the result, this request will be moderated for violence :


```
I'm moderated. Reason(s) :violence
```

### Auto moderation

It is possible to automatically moderate requests before sending them. The **Moderation_auto($status)** method enables this feature.
If the query is moderated, the executeQuery() method will return **false** and moderation_status() will be **true**.

### Example of auto moderation

```
<?php
require "../Ephpai.php";

$Requestgpt=new Ephpai('enter your bad words..'); // Create object
$Requestgpt->Moderation_auto(true);
if (!$Requestgpt->executeQuery())
{
     if($Requestgpt->moderation_status())
          echo "I m moderated";
     else echo ($$Requestgpt->error());
}
else
echo "Result :".$Requestgpt->getTextresult(0);
?>
```

## Thanks for reading

I hope this code can be useful for you.

Don't hesitate to contact me to show me your creations :)

Thomas Missonier
