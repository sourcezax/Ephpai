# Ephpai                     {#mainpage}
## Une Classe PHP pour intéragir facilement avec l'api d'OpenAI (ChatGPT & Dall-E)
============

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

* Completion :
 Interrogation, réponses et génération de textes. Support des modèles S&,EZ2,DE23. Possibilité de définir le nombre maximum de tokens, gestion fine de la temperature. Export possible sous forme de json, d'array ou de texte.

* Génération d'images : 
Permutation facile de la completion à la génération d'images. 
Gestion de différentes tailles d'image, possibilité de sauvegarder une image. Récupération de l'image à partir du contenu texte encodé au format base64  ou via url. 

* Modération :
Possibilité de modérer automatiquement des requêtes ou du contenu par l'intermédiaire de l'api. Possibilité d'automatisation de la Modération. Possibilité d'obtenir les raisons pour lesquelles l'api d'openai a modéré les contenus.

## Comment l'utiliser?

Cela est fait de manière très simple, il suffit de créer un objetEphpai, d'éxécuter la requête, et de récupérer la réponse.

Pour utiliser la classe Ephpai, la variable d'environnement OAIPIKEY doit contenir votre api key (conseillé). Si vous n''avez pas accès aux variables d'environnement sur votre serveur, vous pouvez utiliser la méthode setApikey($key).
Voici un exemple simple :
```
<?php

require "Ephpai.php";

$Requestgpt=new Ephpai('Whois is spiderman?'); // Create 
if (!$Requestgpt->executeQuery())
     echo ($$Requestgpt->error());
else
echo "Result :".$Requestgpt->getTextresult(0);

?>```

