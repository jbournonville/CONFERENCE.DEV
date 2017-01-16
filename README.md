#TP GESTION DE CONFÉRENCE
Ce site est un TP réalisé dans la cadre la formation Mastère Spécialisé en Technologies du Web et Cyber Sécurité au sein de [IMT Atlantique](http://www.imt-atlantique.fr/formation/masteres-specialises/mastere-specialise-technologies-du-web-et-cyber-securite "imt-atlantique.fr")

##LE BUT

L'objectif de ce TP est de mettre en oeuvre les connaissances acquises en : 
-   PHP POO
-   AJAX
-   SQL avec PDO 
-   HTML
-   CSS

##FONCTIONNEMENT
A votre arrivée sur le site vous serez dirigé sur la page de login. Pour les essais vous pouvez vous créer votre propre compte ( S'ENREGISTRER ) ou utiliser ceux prédéfinis (login -> mot de passe):
-   usr -> usr  (compte utilisateur standard)
-   spk -> spk  (compte conférencier)
-   adm -> adm  (compte administrateur)

Vous accéderez alors à l'interface d'utilisation avec les options suivantes :

1. Réservation :   
Vous pourrez vous insricre a la conférence souhaitée puis choisir les sessions auxquelles vous souhaitez assister.

2. Mon Profil :  
Vous pourrez y compléter et modifier vos identifants, mot de passe et thème choisi. L'administrateur pourra vous y attribuer le rôle de contributeur (conférencier) ou d'admin (administrateur).

3. Administration (administrateur uniquement):  
Dans cette section vous pourrez administrer la gestion et la configuration de l'application.
    -   Gestion conférences :  
    Création, suppression de conférences. Gestion des sessions propres à chaque conférence.  
    Modification des paramètres de conférence et administration des créneaux horaires de sessions.
    -   Gestion membres :  
    Liste des utilisateurs enregistrés.  
    Modification des paramètres des utilisateurs et de leur rôle.  
    Suppression d'utilisateurs
    -   Gestion des modules :  
    Création, modification et suppression des modules disponibles à la planification lors des conférences.
    - Gestion des salles :  
    Liste des salles disponibles.   
    Création, modification et suppression des salles disponibles à la planification lors des conférences.
    - Statistiques :
    Statistiques d'utilisation du site.

4. Se déconnecter :  
Permets de se déconnecter d'une session sur le site

##INSTALLATION
Pour mettre en place le site, voici les étapes à suivre :  
1. Copier l'ensemble des fichiers de ce répertoire sur votre serveur.  
2. Utiliser le script SQL nommé 'base.sql' pour importer les données dans votre base de donnée MySQL.

##TEST EN LIVE
Ce site est en démonstration [ici](http://conference.julienbournonville.com "conference.julienbournonville.com"), vous pouvez le tester en utilisant les identifiants indiqués dans le chapitre FONCTIONNEMENT