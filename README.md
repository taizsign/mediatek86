
# MediatekFormation

Ce projet est basé sur le dépôt original : 

https://github.com/CNED-SLAM/mediatekformation





## Présentation de l'application.

C'est quoi MediaTek86 ?

MediaTek86 est une application web développée en PHP via le framework Symfony. Elle a pour objectif de gérer un catalogue de formations portant sur le développement, en s'appuyant sur une base de données relationnelle MySQL.

L'application permet d'organiser des formations pédagogiques sous forme de playlists et de catégories, afin de faciliter leur gestion et leur consultation.


## Front office : 

Ajout de fonctionnalités : 

- Au niveau de la page des playlists, une nouvelle colonne permet d'afficher le nombre de formations associées à chaque playlist.

- Des boutons d'action permettant un tri ASC ou DESC ont également été intégrés.

![App Screenshot](https://i.imgur.com/Lo0zrhS.png)

- Le nombre de formations est également affiché sur la page de détail des playlists.

![App Screenshot](https://i.imgur.com/AfxKulH.png)

## Back office : 

Ajout de fonctionnalités : 

- Le formulaire de connexion.

 Celui-ci permet d’accéder à la partie administration du site. L’authentification se fait à l’aide d’un identifiant et d’un mot de passe, définis dans le fichier security.yaml du projet.

![App Screenshot](https://i.imgur.com/TRr8ULS.png)


- La gestion globale des (formations, playlists, catégories).

Cette interface correspond à la gestion dans la partie administration. Elle permet de visualiser la liste des formations, playlists, catégories ainsi que d’effectuer des actions telles que l’ajout, la modification ou la suppression.

![App Screenshot](https://i.imgur.com/PnCoZjC.png)

* Vue des différents types de formulaire dans la partie "Gestion des formations".

*Formulaire d'Ajout :*

![App Screenshot](https://i.imgur.com/Ft53t9F.png)

*Formulaire de modification :*

![App Screenshot](https://i.imgur.com/J5InJQG.png)

*Formulaire de suppression :*

**Une confirmation est toujours demandée en guise de sécurité**.

![App Screenshot](https://i.imgur.com/W9QtzkK.png)

* Vue des différents types de formulaire dans la partie "Gestion des playlists".

*Formulaire d'Ajout :*

![App Screenshot](https://i.imgur.com/javRPF5.png)

*Formulaire de modification :*

![App Screenshot](https://i.imgur.com/A1eCz0x.png)

*Formulaire de suppression :*
(identique à celui de formations)

**Une confirmation est toujours demandée en guise de sécurité**.


* Vue des différents types de formulaire dans la partie "Gestion des catégories".

*Formulaire d'ajout :*

![App Screenshot](https://i.imgur.com/Dl0g8FV.png)

Pour la suppression, **une confirmation est toujours demandée en guise de sécurité**.


## Accès au projet.

Il est possible d'avoir une preview du projet d'un point de vue utilisateur sur cette page : 

https://mediatek.taizsys.xyz/

La documentation technique associée à celui-ci est aussi présente et accessible : 

https://docs.mediatek.taizsys.xyz/

*pour des raisons de sécurité évidentes la partie administration du projet n'est pas accessible.*

##  Installation locale.

**Prérequis**

* PHP > 8.4
* Symfony CLI | Composer
* MySQL (Workbench a été utilisé dans le cadre du projet)
* Serveur local
* Navigateur web (de préférence basé sur chromium)

**Installation**

1. Cloner le dépôt :
```bash
git clone https://github.com/taizsign/mediatek86.git
cd mediatek86
```
2. Installer les dépendances :
```bash
composer install
```

3. Modification du fichier .env

*c'est lui qui permet la connexion à la base de données.*

4. Importer la BDD mediatek86.sql dans MySQL Workbench.

5. Lancer le serveur : 
```bash
php -S localhost:8000 -t public
```