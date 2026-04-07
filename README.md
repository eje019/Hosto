<!-- # La logique globale de l’application
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

---

## Récap final : tous les fichiers du projet

Voici la liste complète des fichiers :

| `connexion_bdd.php` | Connexion à MySQL |
| `inscription.php` | Créer un compte (hachage mot de passe) |
| `connexion.php` | Se connecter (session) |
| `tableau_de_bord.php` | Afficher toutes les consultations |
| `ajouter_consultation.php` | Ajouter une consultation |
| `modifier_consultation.php` | Modifier une consultation (nom, date, motif) |
| `affecter_medecin.php` | Changer le médecin d'une consultation |
| `changer_statut.php` | Faire évoluer le statut |
| `deconnexion.php` | Se déconnecter |

**9 fichiers. Rien de plus. Ça fonctionne.**

---

## Les règles de sécurité que tu as appliquées (sans même t'en rendre compte)

1. **Requêtes préparées** → partout (INSERT, UPDATE, SELECT, DELETE)
2. **Hachage des mots de passe** → `password_hash()` et `password_verify()`
3. **Sessions PHP** → pour protéger les pages
4. **`htmlspecialchars()`** → pour éviter les failles XSS
5. **Vérification des id dans l'URL** → `is_numeric()` et vérification existence
6. **Redirections après actions** → `header('Location: ...')`

Avec ces 6 règles, l'application est déjà **bien protégée** pour un usage réel.

---

## Le script SQL complet se trouve dans le dossier fichiers sql -->

```markdown
# Application de gestion des consultations - HOSTO

## Description

Application web sécurisée permettant au personnel médical de gérer les consultations des patients.

**Fonctionnalités :**
- Création de compte (inscription)
- Authentification sécurisée (connexion / déconnexion)
- Affichage de toutes les consultations
- Ajout d'une nouvelle consultation
- Modification d'une consultation (nom patient, date/heure, motif)
- Affectation d'un médecin à une consultation
- Évolution du statut (en attente → en cours → terminée)

---

## Technologies utilisées

- PHP (natif, sans framework)
- MySQL
- HTML5 / CSS3
- Session PHP pour l'authentification

---

## Structure du projet

```
HOSTO/
│
├── connexion_bdd.php          # Connexion à MySQL
├── inscription.php            # Créer un compte
├── connexion.php              # Se connecter
├── tableau_de_bord.php        # Afficher les consultations
├── ajouter_consultation.php   # Ajouter une consultation
├── modifier_consultation.php  # Modifier une consultation
├── affecter_medecin.php       # Changer le médecin
├── changer_statut.php         # Changer le statut
├── deconnexion.php            # Se déconnecter
│
└── README.md                  # Ce fichier
```

---

## Installation

### 1. Démarrer XAMPP / WAMP
- Lancer Apache et MySQL

### 2. Créer la base de données
- Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
- Exécuter le script SQL ci-dessous

### 3. Script SQL (à exécuter dans phpMyAdmin)

```sql
-- Création de la base
CREATE DATABASE hosto;
USE hosto;

-- Table utilisateurs
CREATE TABLE utilisateurs (
    utilisateur_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenoms VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(30) DEFAULT 'personnel'
);

-- Table medecins
CREATE TABLE medecins (
    medecin_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenoms VARCHAR(50) NOT NULL,
    specialite VARCHAR(100),
    telephone VARCHAR(20),
    disponible BOOLEAN DEFAULT TRUE
);

-- Table consultations
CREATE TABLE consultations (
    consultation_id INT PRIMARY KEY AUTO_INCREMENT,
    nom_patient VARCHAR(100) NOT NULL,
    date_heure DATETIME NOT NULL,
    motif TEXT NOT NULL,
    medecin_id INT,
    statut ENUM('en attente', 'en cours', 'terminée') DEFAULT 'en attente',
    FOREIGN KEY (medecin_id) REFERENCES medecins(medecin_id) ON DELETE SET NULL
);

-- Insertion de 2 médecins (selon le sujet)
INSERT INTO medecins (nom, prenoms, specialite, telephone, disponible)
VALUES 
('KOUASSI', 'Jean', 'Généraliste', '90123456', TRUE),
('DOSSOU', 'Aminatou', 'Pédiatre', '90234567', TRUE);
```

### 4. Placer les fichiers
- Copier tous les fichiers PHP dans : `C:/xampp/htdocs/hosto/`

### 5. Configurer la connexion
- Vérifier que `connexion_bdd.php` contient les bons identifiants :
  - Base : `hosto`
  - Utilisateur : `root`
  - Mot de passe : (vide par défaut)

### 6. Tester l'application
- Créer un compte : http://localhost/hosto/inscription.php
- Se connecter : http://localhost/hosto/connexion.php
- Utiliser l'application

---

## Utilisation

### Créer un compte
1. Remplir le formulaire d'inscription (nom, prénoms, email, mot de passe)
2. Le mot de passe est automatiquement haché (sécurité)

### Se connecter
1. Saisir l'email et le mot de passe
2. Une session est créée (reste connecté pendant la navigation)

### Tableau de bord
- Affiche toutes les consultations avec :
  - Nom du patient
  - Date et heure
  - Motif
  - Médecin affecté (ou "Non affecté")
  - Statut (en attente / en cours / terminée)

### Actions disponibles
- **Modifier** : changer le nom du patient, la date/heure ou le motif
- **Médecin** : affecter ou changer le médecin
- **Avancer statut** : faire évoluer le statut (en attente → en cours → terminée)

### Déconnexion
- Cliquer sur "Déconnexion" dans le tableau de bord

---

## Sécurité implémentée

| Protection | Comment |
|------------|---------|
| Injections SQL | Requêtes préparées (PDO) partout |
| Mots de passe | Hachage avec `password_hash()` |
| Sessions | Pages protégées par vérification de session |
| XSS | `htmlspecialchars()` sur tous les affichages |
| URL tampering | Vérification des `id` avec `is_numeric()` |
| Accès direct | Redirection si utilisateur non connecté |

---

## Ce qu'on peut améliorer plus tard

- Interface plus jolie (Bootstrap ou CSS personnalisé)
- Messages de confirmation après chaque action
- Validation des dates (pas de date dans le passé)
- Empêcher les doublons (même patient à la même heure)
- Rôles (admin / opérateur)
- Recherche de consultations
- Pagination

---

## Auteur

Développé dans le cadre d'un apprentissage pas à pas de PHP / MySQL.

**Objectif :** Comprendre la logique du CRUD et de l'authentification, sans framework, en partant de zéro.

---

Une application web **fonctionnelle et sécurisée** avec :
- Authentification complète
- Gestion de sessions
- Opérations CRUD sur plusieurs tables
- Jointures SQL
- Liste déroulante dynamique
- Évolution de statut

**C'est une base solide pour des projets plus complexes.**

---

Ce README contient :
- La description du projet
- L'installation pas à pas
- Le script SQL complet
- La structure des fichiers
- Les règles de sécurité
- Ce qui peut être amélioré
