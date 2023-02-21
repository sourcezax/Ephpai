# Ephpai.php                     
## Une Classe PHP pour intéragir facilement avec l'api d'OpenAI (ChatGPT & Dall-E)

Cette Classe PHP n'est pas une Classe officielle et ce projet n'a aucun lien commercial avec OpenAI.

Pour pouvoir l'utiliser vous devez disposer d'un compte Openai et d'une clé pour l'api : [Get api key from Openai](https://openai.com/api/)

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

## Classe Ephpai 

Cette Classe PHP permet d'utiliser facilement l'api fournie par OpenAI.

Elle permet,de façon limitée mais simple, d'effectuer des requêtes et d'obtenir des réponses pour les fonctions suivantes :

* **Completion (texte)** :
 Interrogation, réponses et génération de textes. Support des modèles "text-davinci-003" (GPT),"text-curie-001","text-babbage-001" et "text-ada-001". Possibilité de définir le nombre maximum de tokens, gestion fine de la temperature. Export possible sous forme de json, d'array ou de texte.

* **Génération d'images** : 
Permutation facile de la completion à la génération d'images. 
Gestion de différentes tailles d'image, possibilité de sauvegarder une image. Récupération de l'image à partir du contenu texte encodé au format base64  ou via url. 

* **Modération** :
Possibilité de modérer automatiquement des requêtes ou du contenu par l'intermédiaire de l'api. Possibilité d'automatisation de la Modération. Possibilité d'obtenir les raisons pour lesquelles l'api d'openai a modéré les contenus.

## Comment l'utiliser?

Cela est fait de manière très simple, il suffit de créer un objet Ephpai, d'éxécuter la requête, et de récupérer la réponse.

Pour utiliser la classe Ephpai, la variable d'environnement OAIPIKEY doit contenir votre clé d'api  (conseillé). Si vous n''avez pas accès aux variables d'environnement sur votre serveur, vous pouvez utiliser la méthode **setApikey($key)**.

#### Voici un exemple simple :
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

Par défaut le type de recherche est défini sur la completion (texte), et le modèle text-davinci-003 (chat gpt).

[A propos des modèles](https://platform.openai.com/docs/models/overview)

Il est possible de le modifier avec la Méthode, **setModel($model)**

Le nombre de tokens est fixé par défaut à 850, il est possible de le modifier via la méthode **setMaxtoken($nombre)**;

#### Exemple de modification de la requête précédente :

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

Il y a un constructeur qui accepte le modèle et maxtoken en paramètres et permet de nous faciliter la tâche :

```
<?php require "Ephpai.php";

$Requestgpt=new Ephpai('Whois is spiderman?','text-curie-001',200); // Create object with model type 'text-curie-001' and maxtoken=200 
if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);
?>
```
Le résultat peut être récupéré au format json, sous forme d'array ou sous forme de texte.

Il existe plusieurs méthodes permettant de modifier les paramètres de la requetes, comme la gestion de la temperature par exemple, ou de récupérer des valeurs.
Elles sont documentées dans la documentation, disponible dans le répertoire doc.

## Génération d'images

Pour générer des images en utilisant Dall-E et l'api d'Openai, il faut activer la génération d'image avec la méthode **generateImage(true)**.
Il est possible de revenir au mode completion (texte), en lui passant false comme paramètre.

#### Exemple d'affichage d'image en utilisant la librairie GD :

Note : Il est important de n'avoir aucun espace superflu.
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

Si vous ne vous voulez pas vous embêter avec les fonctions de la librairie GD, il existe la méthode displayImg($nb,$quality), qui fait tout le travail. 

#### Le même exemple avec l'utilisation de la méthode displayImg :
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
#### De la même manière, la méthode **saveImgtojpeg($nb,$filename,$quality)** permet de sauvegarder l'image sur son serveur :
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

**Taille des images** : l'api d'OpenAI permet un nombre restreint de taille d'image : 256x256,512x512 et 1024x1024.

Pour plus d'informations sur les méthodes liées à la génération d'images, vous trouverez plus d'informations dans la documentation présente dans le répertoire doc.

## Modération

Il est conseillé d'utiliser l'api de modération fournie par OpenAI afin de ne pas soumettre de requêtes qui pourraient être contraires aux règles d'OpenAI, notamment si vous offrez à vos utilisateurs la possibilité de soumettre du contenu. 

Il est possible de vérifier auprès de l'api, sans effectuer la requête, que le contenu est conforme à la modération.

A noter que l'api de modération est valable sur tout type de texte que ce soit pour une image ou du texte.

Il sagit d'une implémentation fonctionnelle mais limitée de l'usage de la modération. 

S'il est possible de connaître les raisons d'une modération sous forme d'array, les scores respectifs de chaque raison (valeur numérique) ne sont pas retournés.


#### Cet exemple interroge l'api de moderation sur la conformité du texte de la requête. Cette requête devrait être modérée :

```
<?php

require "tomkey.php";
require "../Ephpai.php";

$Requestgpt=new Ephpai('I want to hit you. i hate you'); // Create object
$Requestgpt->setApikey($tomkey);
//Asks to moderation api to evaluate query.
if ($Requestgpt-> ModerateQuery()) 
     //Depending the result, the moderation status will be set to true (moderated) or false (no moderated).
     if ($Requestgpt->moderation_status())
        echo "I m moderated";
     else
          echo "I m correct";
     else
          echo  $Requestgpt->error();
?>
```

A noter que la modération fonctionne correctement pour la langue anglaise,et apparemment limité pour les autres langues.

Il est possible de connaitre la ou les raisons de la modération sous forme d'array. Remplacer la ligne dans l'exemple ci-dessus

```
echo "I m moderated";
```

par 

```
 {
          echo "I m moderated. Reason(s) :";
          foreach($Requestgpt->moderated_categories() as $reason)
               echo $reason;
        }
```

La méthode moderated_categories() renvoie une array contenant les différentes raisons de la modération.

### Modération automatique

Il est possible de modérer automatiquement les requêtes avant envoi. La méthode **Moderation_auto($status)** permet d'activer cette fonctionnalité.
Si la requête est modérée, la méthode executeQuery() retournera false et moderation_status() sera à true.

### Exemple de modération automatique de requête

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

Une documentation au format HTML se trouve dans le répertoire doc.

J'espère que ce code pourra vous être utile. N'hésitez pas à me contacter pour me montrer vos réalisations :)






