# 🚗 CoRide

## 📌 Description

CoRide est une application web développée avec **Laravel** permettant aux employés d'une même entreprise de partager leurs trajets domicile-travail.

L'application facilite le covoiturage en permettant aux conducteurs de publier leurs trajets et aux passagers de rechercher, réserver et suivre leurs réservations. Une fonctionnalité d'intelligence artificielle calcule un score de compatibilité afin de proposer les trajets les plus adaptés.

---

# 👥 Équipe

- **Aya Sadiq** 
- **Oussama Malih** 

---

# 🛠️ Technologies utilisées

- Laravel 13
- PHP 8.3
- MySQL
- Laravel Breeze
- Blade
- Eloquent ORM
- Laravel AI
- Git & GitHub
- Jira

---

# 📂 Fonctionnalités

## Authentification

- Inscription
- Connexion
- Déconnexion
- Gestion des profils

## Gestion des trajets

- Ajouter un trajet
- Modifier un trajet
- Supprimer un trajet
- Consulter les trajets

## Gestion des réservations

- Réserver un trajet
- Consulter ses réservations
- Modifier le statut d'une réservation
- Annuler une réservation

## Gestion des entreprises

- Association d'un employé à une entreprise
- Gestion des rôles :
  - Conducteur
  - Passager
  - Les deux

## Intelligence Artificielle

Le projet intègre Laravel AI afin de calculer :

- Score de compatibilité (0-100)
- Justification du score
- Horaire conseillé

Les résultats sont stockés grâce à un Cast Laravel.

---

# 📊 Base de données

Le projet comporte les tables suivantes :

- entreprises
- users
- trajets
- reservations
- sessions
- cache
- jobs

---

# 📁 Architecture

```
app/
 ├── Http/
 ├── Models/
 ├── Providers/

database/
 ├── migrations/
 ├── seeders/

resources/
 ├── views/

routes/
 ├── web.php
```

---

# ⚙️ Installation

Cloner le dépôt

```bash
git clone <url-du-projet>
```

Installer les dépendances

```bash
composer install
```

Créer le fichier `.env`

```bash
cp .env.example .env
```

Générer la clé

```bash
php artisan key:generate
```

Configurer la base de données dans le fichier `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coride
DB_USERNAME=root
DB_PASSWORD=
```

Exécuter les migrations

```bash
php artisan migrate
```

Lancer le serveur

```bash
php artisan serve
```

---

# 📌 Règles métier

- Un employé appartient à une seule entreprise.
- Un email professionnel est unique.
- Un employé peut être conducteur, passager ou les deux.
- Un trajet possède un nombre limité de places.
- Un passager ne peut réserver qu'une seule fois le même trajet.
- Les réservations possèdent un statut.
- Un trajet avec des réservations confirmées ne peut pas être supprimé.
- Le score IA est calculé uniquement lors de la recherche d'un trajet.

---

# 🧪 Tests

Pour exécuter les tests :

```bash
php artisan test
```

---

# 📦 Git

```bash
git clone <url>
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

---

# 📋 Gestion de projet

Le suivi du projet a été réalisé avec **Jira**.

Organisation :

- 1 Sprint
- 1 Epic
- 8 User Stories

---

# 📄 Livrables

- Dépôt GitHub
- Board Jira
- README
- MCD
- MLD
- Application Laravel
- Migrations
- Seeders
- Documentation

---

# 👨‍💻 Auteurs

**Aya Sadiq**

- Développement Backend
- MCD
- Jira
- MLD

**Oussama Malih**

- Développement Backend
- Laravel
- Git/GitHub
- Modélisation de la base de données

---

## 📜 Licence

Projet réalisé dans le cadre de la formation Développeur Full Stack.