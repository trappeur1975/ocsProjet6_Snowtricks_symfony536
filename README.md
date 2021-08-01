# ocsProjet6_Snowtricks_symfony536
31/07/2021 : creation of the symfony5.3, the database, the homeController
1/08/2021 :  integration of a bootstrap template (pulse) and creation of home and trick page with fictitious content
1/08/2021 : creation of the user entity and its integration in database and implementation of the authentication 

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
        car sinon on aura une erreur par la suite (voir https://stackoverflow.com/questions/60867751/maildev-with-symfony-5-mailer-tls-crash)

    puis dans le navigateur aller a l adresse suivante
    http://localhost:1080

4) pour tester le service on peut envoyer un email depuis un fichier php

        mail('destinataireEmail@societe.fr', 'titre du email', 'message du email', 'From: emailExpediteur@societe.com');

----------------------infos commande--------------------
penser a executer wampserver pour acceder a la base de donnee via phpmyadmin

commande por excuter le serveur web de symfony :
    symfony server:start
    Dans son navigateur aller a l adresse : http://127.0.0.1:8000

----------------------infos doc--------------------
versions symfony : https://symfony.com/releases
bundles symfony :
    https://packagist.org/
    https://flex.symfony.com/