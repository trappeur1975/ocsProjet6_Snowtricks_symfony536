# ocsProjet6_Snowtricks_symfony536
31/07/2021 : creation of the symfony5.3, the database, the homeController
1/08/2021 : integration of a bootstrap template (pulse) and creation of home and trick page with fictitious content
1/08/2021 : creation of the user entity and its integration in database and implementation of the authentication
2/08/2021 : integration of the reset password bundle 
5/08/2021 : Frenchization of text for authentication and password reset 
5/08/2021 : integration of a flash message in controller \ RegistrationController.php for the "function register ()"
6/08/2021 : creation of message, trick, picture, pool, video entities and modification of user 
7/08/2021 : modification of the registration form (addition of username and picture fields) and its processing in the database
7/08/2021 : User crude, Picture and Video update and RegistrationController.php and RegistrationFormType.php update
7/08/2021 : change the username property of User to nickname
7/08/2021 : integration in the basic template the javascript scripts link to bootstrap
13/08/2021 : integration of the orm-fixture bundle, creation of a "pool, user, trick" dataset (fixtures)
14/08/2021 : creation of a script to upgrade my fixtures in composer.json, integration of the faker directory, picture data set and update of the frontController and its templates
15/08/2021 : update of the display management of front images (frontConntroller and front template), integration of a css file
16/08/2021 : change the fzaninotto / Faker library to FakerPHP / Faker and create MessageFixtures.php
17/08/2021 : modify the fixtures "pictureFixtures.php" to integrate the upload of image file in the symfony project and modification of the code for the display of images in the template of the frontController.php
19/08/2021 : integration of messages in the frontController during a "showTrick ()"
20/08/2021 : crud trick and restructuring of folders and files (frontController becomes HomeController)
21/08/2021 : integration of a bootstrap template for the forms and update of the "design" of the 
22/08/2021 : addition of the Picture field in the form of new and edit of trick and management of deletion of pictures in ajax for edit of trick
28/08/2021 : modification de l ajout de picture dans la fonction edit
4/09/2021 : management of the list of pictures for a trick in image checkbox 
5/09/2021 : updated trick (removing pictures)
6/09/2021 : cascade deletion of pictures

----------------- gestion des email ----------------
pour tester l'envoi des email un serveur de email ("MailDev) en local a été utilisé

1) pour l'installer (utilise nodejs => installer nodejs auparavant sur sa machine)
    npm install -g maildev # Utilisez sudo si nécessaire
    maildev

2) configurer wampserver
    ce serveur SMTP fonctionnant sur le port 1025.
    il faut doncmodifier le fichier php.ini

        [mail function]
        SMTP = localhost
        smtp_port = 1025

    ne pas oublier de relancer apache/wamp
3) pour le lancer
    executer la commande suivante dans l invité commande (pas dans le terminal powershell de vscode car cela pose un probleme) :
            
            maildev --hide-extensions STARTTLS
        
        et non juste
            
            maildev

        car sinon on aura une erreur par la suite (voir https://stackoverflow.com/questions/60867751/maildev-with-symfony-5-mailer-tls-crash

    puis dans le navigateur aller a l adresse suivante
    http://localhost:1080


----------------------infos doc--------------------
versions symfony : https://symfony.com/releases
bundles symfony :
    https://packagist.org/
    https://flex.symfony.com/

----------------------infos commande--------------------
penser a executer wampserver pour acceder a la base de donnee via phpmyadmin

commande pour excuter le serveur web de symfony :
    symfony server:start
    Dans son navigateur aller a l adresse : http://127.0.0.1:8000

 executer la commande suivante dans l invité commande (pas dans le terminal powershell de vscode car cela pose un probleme) pour lancer le serveur de email en local : 
            maildev --hide-extensions STARTTLS

            Dans son navigateur aller a l adresse : http://localhost:1080

dans le fichier "composer.json" un script (que j ai nommé « reset-data ») a été crée pour remettre a zero ma base de donnée, surtout utile pour l'utilisation d'un jeu de donnée via des fixtures. Pour l executer il suffit d'executer la commande suivante :
            composer reset-data
