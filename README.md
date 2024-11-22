# Lokalt

[EN] - The digital ally to boost your business - the solution for your local shopping.

[FR] - L’allié numérique pour dynamiser votre commerce, la solution pour vos courses locales.

---

_The rest will be redacted in french_

---

## Liens

[Figma](https://www.figma.com/file/Yn4Lz9B75M7069EJhsYlAG/Lokalt?type=design&node-id=0%3A1&mode=design&t=CuTNaJV8YL8e3nTO-1)

## Tables des matières

- [Lokalt](#lokalt)
  - [Liens](#liens)
  - [Tables des matières](#tables-des-matières)
  - [Introduction](#introduction)
    - [Volonté](#volonté)
    - [Public cible](#public-cible)
  - [Projet](#projet)
    - [Framework - Backend - Frontend](#framework---backend---frontend)
    - [Services tiers - Hébergement](#services-tiers---hébergement)
    - [Cahier des charges](#cahier-des-charges)
      - [Utilisateur commerçant](#utilisateur-commerçant)
        - [Fonctionnalités obligatoires](#fonctionnalités-obligatoires)
        - [Fonctionnalités optionnelles](#fonctionnalités-optionnelles)
      - [Utilisateur client](#utilisateur-client)
    - [Fonctionnalités futures](#fonctionnalités-futures)
  - [Futur](#futur)
    - [Desktop/Bureau](#desktopbureau)
    - [Mobile](#mobile)

## Introduction

Pour commencer, j’ai décidé pour mon projet de fin d’études de créer une application web pour aider les commerçants dans leur travail du quotidien. Cette application web leur donnera des outils pour gérer au mieux leur travail, comme par exemple leur gestion des stocks, leur gestion de fidélité des clients, leur calendrier pour divers événements, etc. En plus de ces outils, ils auront la possibilité de créer un commerce en ligne à partir de mon application web. Cela leur permettra de vendre leurs produits en ligne et d’avoir cette visibilité tant voulue sur le web.

### Volonté

Cette volonté d’aider les commerçants vient du fait que j’ai pu moi même essayer de créer un commerce en ligne via des plateformes comme [Shopify](https://www.shopify.com/) ou [Wix](https://fr.wix.com/) mais j’ai très vite remarqué que ces solutions étaient très peu adaptées pour des clients qui n’ont pas beaucoup de temps ou de capacités techniques à consacrer à la création d’un commerce en ligne. Shopify et Wix sont tous deux très rapides pour créer un site/commerce mais une fois créé, le configuré devient un enfer.

En effet, il faut passer par beaucoup de menus ou d’onglets pour changer la moindre valeur/texte/image/couleur sur le site/commerce. Lors de mon expérience avec Shopify, j’ai voulu changer le nom de mon site et je n’ai jamais réussi à trouver comment faire. J’ai dû aller perdre mon temps sur des vidéos tutorielles afin d’avoir ma réponse, alors que cela aurait dû être une action simple et rapide.

De plus, les prix de ces services sont assez élevés. Il y a des abonnements, des frais de paiements, des frais d’utilisation, ... Cela peut vite devenir très cher pour des commerçants qui ne verront pas forcément de différence au niveau de leurs bénéfices ou de leurs clients. Les frais de paiements sont aussi élevés, Shopify prend 2% de chaque paiement effectué sur le site. Cela peut paraître peu mais si vous avez un commerce qui vend des produits à 100€, cela fait 2€ de frais de paiement. Si vous avez 100 clients qui achètent un produit à 100€, cela fait 200€ de frais de paiement. C’est une somme assez importante qui pourrait être utilisée pour autre chose. Le premier abonnement de Shopify est à 32€/mois, si vous souhaitez plus d’options/outils il faut prendre le deuxième abonnement à 92€/mois, voire même le troisième à 384€/mois, se sont des prix quand même très conséquents.

En fait, une fois qu’on regarde bien les plateformes comme Shopify ou Wix, on se rend vite compte que ses plateformes sont pour le commerce international voire le commerce mondial. Un commerçant qui ne souhaite que vendre dans son pays uniquement, se trouve assez peu servi par de tels services.

### Public cible

Lokalt doit alors viser des clients avec plus de précision que les concurrents déjà présent. C’est pour cela que Lokalt sera fait en pensant à un commerçant, avec un commerce de petite ou moyenne taille car les grands commerces sont plus à même de créer leur propre site web, qui ne souhaite que de vendre ces produits en lignes et uniquement dans son pays pour éviter toutes surcharges de travail au niveau de créer des commerces internationaux. Tout en gardant des prix abordables et surtout configurables !

C’est-à-dire que les commerçants pourront créer leur propre pack d’options/outils et ne payer que pour ce qu’ils utilisent. Cela permettra de ne pas avoir de frais inutiles et de ne pas avoir des outils qui ne servent à rien, le prix des options/outils sera du coup plus bas, rien n’est encore réfléchit à ce niveau là mais les prix des outils se trouveront probablement entre 5€ et 20€ par option, en accord avec l’utilité/coût de l’outil.

Lors de la mise en ligne du projet, pour la date de présentation du PFE, il faut que le site soit utilisable par des commerçants en Belgique obligatoirement, avec comme langues l’allemand, l’anglais, le français et le néerlandais. Optionnellement, si le temps le permet, il sera alors utilisable par des commerçants en France, au Luxembourg, au Pays-Bas et en Allemagne. (La seule chose qui change entre le pays, c’est la devise et les taxes.)

Tout type de commerce est la bienvenue, que ce soit un commerce de vêtements, de nourriture, de meubles, de produits de beauté, etc. Tant que le commerce est légal et que le commerçant est en règle avec la loi, il pourra profiter de Lokalt.

Lorsque mon design sera terminé, je commencerai alors à aller trouver des commerçants pour avoir mes utilisateurs tests. Leurs retours seront très importants pour la suite du projet, ils me permettront de savoir si mon projet est viable ou non, s’il y a des choses à changer ou à ajouter, etc. J’ai déjà deux clients pour m’aider dans cette tâche, un commerce de vins et un commerce de produits locaux.

## Projet

### Framework - Backend - Frontend

Pour réaliser ce projet, je compte bien le faire en Laravel et Livewire. J’ai déjà une petite expérience avec Laravel et je trouve que c’est un framework très puissant et très bien fait. Il permet de faire des applications web très rapidement et facilement. Il est aussi très bien documenté et il y a beaucoup de ressources sur internet pour l’apprendre. J’ai pu réaliser un projet scolaire avec Livewire et j’ai été très vite conquis par le dynamisme qu’offre ce framework avec ce côté application live. Ces deux frameworks sont très puissants ensemble et me permettront de rendre mon projet dynamique.

Le frontend sera fait en Blade avec du Sass pour le styliser. L’utilisation de Blade n’est pas une question qui peut se poser, c’est le moteur de template de Laravel et il est très bien fait. Par contre, je souhaite utiliser Sass pour le styliser car Sass est plus puissant que le CSS et que les autres solutions comme Tailwind CSS ou Bootstrap ne m’intéresse pas. Je souhaite avoir un contrôle total sur le style de mon site et Sass me permettra de le faire et je pense que TailwindCSS me fera perdre du temps à cause des classes à connaître et à ajouter dans le HTML.

### Services tiers - Hébergement

Mon projet devra avoir recours à plusieurs services, allant de l’envoi d’email à la gestion des paiements en passant par la gestion des images.

Pour les emails, je pense qu’un service comme Mailtrap sera intéressant. J’ai déjà une petite expérience avec et j’ai bien aimé ce service. Pour le développement par contre, Mailpit sera utilisé car Mailtrap est limité sur le nombre d’emails envoyés même en local.

Pour la gestion des paiements, je pense utiliser Stripe. Stripe est un service très connu maintenant et une offre gratuite se trouve dans notre Github Student Pack, ce qui est parfait.

Pour le cloud des images, je n’ai pas encore choisi. Je sais bien que Heroku possède un service pour les images mais je trouve qu’Amazon S3 est plus intéressant. Amazon S3 possède énormément de fonctionnalités que j’ai pu utiliser lors de mon stage scolaire et il est payant suivant l’utilisation, ce qui est parfait pour un projet comme celui-ci. Je vais encore me renseigner sur les différentes solutions mais je pense que Amazon S3 sera le meilleur choix.

Pour l’hébergement, le choix est des plus vastes. Heroku, DigitalOcean, Laravel Forge, AWS, Azure, etc. Je serai beaucoup plus enclin à utiliser DigitalOcean ou Laravel Forge car je sais qu’ils sont tout deux très puissants avec des sites en Laravel et surtout que DigitalOcean est extrêmement configurable au niveau des serveurs. Je vais encore me renseigner sur les différentes solutions mais je pense que DigitalOcean sera le meilleur choix.

D’autres services seront utilisés comme Github, Github Actions, etc. mais je ne vais pas m’étendre sur ces services car ils sont très connus et très utilisés. Les seuls autres services que je pense utiliser sont Doppler pour la gestion des variables d’environnement, Doppler est un service qui permet de gérer les variables d’environnement de manière simple et sécurisée. Et peut-être POEditor pour la gestion des traductions avec une interface plus sympathique que les fichiers de traductions de Laravel.

### Cahier des charges

#### Utilisateur commerçant

##### Fonctionnalités obligatoires

En tant qu’utilisateur commerçant, je veux pouvoir :

- gérer mon compte
  - créer un compte
  - modifier mon compte
  - supprimer mon compte
  - me connecter/déconnecter
- gérer mon commerce
  - créer un commerce
  - modifier un commerce
  - supprimer un commerce
  - ajouter des comptes employés/ouvriers
- gérer mes produits
  - créer un produit et des variantes
  - créer une catégorie de produits
  - modifier une ressource
  - supprimer une ressource
  - gérer le type de produit et le type de vente
    - catégorie de produit pré-définie (alimentaire, vêtement, etc.)
    - vente à l’unité, au poids, au mètre, etc.
  - notifier les clients d’un nouveau produit, d’une promotion, etc.
  - gérer la cote de popularité d’un produit
    - créer un système de cote pour les clients
    - pouvoir demander la suppression d’une cote
- gérer mes commandes (payante, option de base avec l’e-commerce)
  - créer, modifier, supprimer une commande chez un fournisseur (OPTIONNELLE)
  - gérer les commandes en ligne
    - voir les commandes
    - modifier les commandes
    - supprimer les commandes
    - status de la commande (en cours, terminée, annulée, etc.)
  - notifier les clients d’une commande ainsi que le commerçant
- gérer mes stocks (payante)
  - créer un stock
  - modifier un stock
  - supprimer un stock
  - gérer les notifications de stock
    - stock trop bas
    - stock vide
    - notifier les clients de nouveaux stocks
- Suivi de toutes les ressources de mon commerce | Monitoring (clients, produits, stocks, commandes, etc.)

##### Fonctionnalités optionnelles

- gérer l’événementiel du commerce
  - créer un événement
  - modifier un événement
  - supprimer un événement
  - notifier les clients d’un événement
- gérer mes paiements (payante)
  - créer un paiement
    - sur le site avec QR code
    - sur le site avec paiement sans contact via smartphone (NFC)
    - envoyer le paiement sur un Bancontact (OPTIONNELLE)
  - décompter le stock après paiement
- gérer mes clients (payante)
  - créer un client
  - modifier un client
  - supprimer un client
  - gérer la fidélité des clients
    - créer un programme de fidélité
    - modifier un programme de fidélité
    - supprimer un programme de fidélité
    - créer une carte de fidélité (électronique)
    - modifier une carte de fidélité (électronique)
    - supprimer une carte de fidélité (électronique)
    - créer un système pour qu’un client puisse récupérer sa carte de fidélité électronique si il se crée un compte sur Lokalt, en demandant un code chez le commerçant
- gérer ma comptabilité (de mon/mes commerce•s)
  - rapport de vente
  - rapport de stock
  - rapport de paiement
  - rapport de client
  - rapport de commande
  - estimation des taxes

#### Utilisateur client

En tant qu’utilisateur client, je veux pouvoir :

- gérer mon compte
  - créer mon compte (avec ou sans compte Google/Facebook/Apple/etc.)
  - modifier mon compte
  - supprimer mon compte
- gérer le style du commerce en ligne
  - choisir un template visuel de commerce (gratuit/payant)
- acheter/réserver des produits/services en ligne
  - acheter/réserver avec/sans compte
  - choisir une méthode de paiement (Bancontact, carte de crédit, etc.)
  - être notifié de l’état de ma commande (en cours, terminée, retrait disponible, annulé, etc.) (par email et/ou sms)
  - annuler un achat/réservation/commande
  - reporter un problème sur un achat/réservation/commande
- placer des produits/services dans mes favoris
- créer des collections de produits/services
- placer des produits/services dans mon panier
- voir les produits/services/commerçants autours de moi
  - voir les produits/services/commerçants sur une carte avec filtres/tris
  - voir les produits/services/commerçants sur une liste avec filtres/tris
  - voir les produits/services/commerçants sur des pages dédiées avec filtres/tris
  - voir les notes et commentaires sur un produit/service/commerçant
- mettre une note sur un produit/service/commerçant (avec ou sans commentaire)
- voir l’événementiel des commerçants
  - voir le calendrier des événements d’un commerçant
  - réserver une plage horaire/une date pour un événement
  - recevoir confirmation de la réservation (par email et/ou sms)
  - annuler une réservation
- participer au(x) programme(s) de fidélité des commerçants
  - récupérer une carte de fidélité électronique chez un commerçant et rajouter les achats sur mon compte
    - récupérer la carte de fidélité électronique via un code/QR code/email/sms

### Fonctionnalités futures

Si le temps le permet avant la présentation du PFE ou après, si le projet est viable, je souhaite ajouter des fonctionnalités supplémentaires comme :

- la gestion des employés/ouvriers du commerce
  - gestion des horaires
  - posséder différents comptes/rôles pour le commerce
  - gestion des salaires
  - monitoring des employés/ouvriers
- gestion des livraisons
  - pouvoir s’inscrire chez des sociétés de livraison
  - l’utilisateur client peut choisir une livraison
    - à domicile
    - en point relais
  - l’utilisateur peut être tenu au courant de l’état de sa livraison
- gestion des promotions
  - créer une promotion
  - modifier une promotion
  - supprimer une promotion
  - notifier les clients d’une promotion
- compléter les différents template de commerce, avec leurs fonctionnalités, avec par exemple :
  - les restaurants (réservations, menus, etc.)
  - les sociétés (fournisseurs) locales (drinks, boucheries, etc.) (commandes, stocks, livraisons, etc.)

D’autres fonctionnalités me viendront à l’esprit plus tard ou même les utilisateurs tests me donneront sûrement des idées.

## Futur

Le but de ce projet est de non seulement réaliser un PFE qui pose un challenge intéressant mais aussi d’être peut-être ma source de revenus principale pour plus tard en tant qu’indépendant.

Ce qui veut dire que plusieurs points seront à prendre en charge assez rapidement, le plus gros étant la disponibilité du site. Dans le monde des commerçants, on y trouve vraiment de tout comme gestion du travail. Sur papier, téléphone, ordinateur, tablette ou même rien du tout. Il faut donc que Lokalt soit disponible sur tous les supports possibles.

### Desktop/Bureau

Pour faire une application bureau, je pourrai utiliser [NativePHP](https://nativephp.com/). À la date que j’écris ces lignes, je n’ai pas encore utilisé NativePHP et il se trouve que ce framework est en alpha et n’est donc pas recommandé pour les projets en production. Je vais donc être patient et suivre son développement de près.

### Mobile

Pour créer une application mobile, j’ai plusieurs expériences. J’ai eu des cours de Flutter à l’école mais j’ai trouvé le langage de Flutter assez compliqué et je n’ai pas vraiment aimé l’utiliser. J’ai pu faire mon stage en travaillant sur une application mobile en utilisant React Native. C’était très sympa à utiliser et j’ai beaucoup aimé travailler avec mais travailler sur une application qui était déjà en ligne depuis plusieurs mois était plus facile que de commencer moi-même une application mobile de zéro.

Après plusieurs recherches sur le web, j’ai pu trouver un framework qui me semble être une bonne solution : [Ionic](https://ionicframework.com/). Rien que sur leur site, on peut voir qu’ils passent beaucoup de temps à parler de la popularité croissante de leur framework, de la rapidité et facilité de développement, de la compatibilité avec les autres frameworks comme Angular, React et Vue, etc. Bien que j’ai déjà fait du React Native, je pense que j’utiliserai VueJS. J’ai pu réaliser quelques petits exercices en cours avec VueJS et je l’ai trouvé très complet et facile à prendre en main. Je pense que VueJS sera un bon choix pour Ionic.
