# La logique globale de l’application
Voilà comment l’utilisateur va l’utiliser (le parcours) :

Il arrive sur une page d’accueil
S’il n’a pas de compte → il crée un compte
S’il a un compte → il se connecte
Après connexion → tableau de bord avec la liste des consultations
Depuis ce tableau, il peut :
Ajouter une consultation
Modifier une consultation (rectifier)
Changer le médecin affecté
Changer le statut (en attente → en cours → terminée)
Il peut se déconnecter


Ce qu’on doit construire (fichiers principaux) :

Fichiers	              |     Rôles
inscription.php	            Créer un compte (utilisateur)
connexion.php	            Se connecter (email + mot de passe)
tableau_de_bord.php	        Afficher toutes les consultations (comme liste.php)
ajouter_consultation.php	Ajouter une consultation
modifier_consultation.php	Modifier (nom patient, date, motif)
affecter_medecin.php	    Changer le médecin
changer_statut.php	        Changer le statut
deconnexion.php	            Se déconnecter
connexion_bdd.php	        Connexion à MySQL

Et aussi :
Un fichier SQL pour créer les tables
Un peu de CSS pour que ce soit lisible


# L’ordre dans lequel on va construire (très important)
On ne va pas tout faire d’un coup. VOICI UN ORDRE :

Créer la base de données et les 3 tables (SQL)

Insérer 2 médecins à la main (dans phpMyAdmin)

Créer le fichier de connexion à la BDD (connexion_bdd.php)

Faire l’inscription (hachage mot de passe)

Faire la connexion (session)

Faire le tableau de bord (afficher les consultations avec nom du médecin)

Faire l’ajout d’une consultation (avec liste déroulante des médecins)

Faire la modification d’une consultation (rectifier nom, date, motif)

Faire l’affectation d’un médecin (changer medecin_id)

Faire le changement de statut

Faire la déconnexion

Ajouter un peu de CSS pour rendre ça propre