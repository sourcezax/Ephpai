# Ephpai.php                     
## Une Classe PHP pour intéragir facilement avec l'api d'OpenAI (ChatGPT & Dall-E)

Cette Classe PHP n'est pas une Classe officielle et ce projet n'ai aucun lien commercial avec OpenAi
Pour pouvoir l'utiliser vous devez disposer d'un compte Openai et d'une clé pour l'api:[Get api key from Openai] (https://openai.com/api/)

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

Cette Classe PHP permet d'utiliser facilement l'api fournie par Openai php.

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

Le nombre de tokens est fixé par défaut à 850, il est possible de le modifier via la methode **setMaxtoken($nombre)**;

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

Il y a un constructeur qui permet de nous faciliter la tâche

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
Il est possible de revenir à au mode completion (texte), en lui passant false comme paramètre.



