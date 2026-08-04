# Cahier des Charges & Cadrage Fonctionnel - CoRide

## 1. Contexte & Problématique

### Contexte
**MobiliTech** est une startup spécialisée dans la mobilité durable en entreprise. Dans le cadre de sa stratégie RSE et pour accompagner la transition écologique de ses entreprises partenaires (NextBuild, Atlas Digital, GreenLogix, Kandia Solutions, MobiliTech), elle souhaite encourager le covoiturage inter-entreprises entre collègues habitant des zones proches.

### Problématique
Aujourd'hui, la majorité des collaborateurs utilisent leur véhicule individuel faute d'outil simple pour se coordonner. Un simple filtre par ville ne suffit pas : deux trajets peuvent partager la même ville de départ mais avoir des horaires décalés ou des itinéraires incompatibles.
**CoRide** doit résoudre ce problème en centralisant la publication de trajets et la gestion de réservations, tout en offrant au passager une aide à la décision intelligente via une brique IA expliquant la compatibilité temporelle et géographique des trajets.

---

## 2. Rôles et Acteurs du système

* **Employé (Rôle mixte)** : Tout utilisateur inscrit sur la plateforme avec son email professionnel. Il appartient à une unique entreprise partenaire.
* **Conducteur** : Employé proposant un trajet (ville de départ/arrivée, horaire, jours de récurrence, places disponibles).
* **Passager** : Employé recherchant un trajet et effectuant des demandes de réservation.

---

## 3. Périmètre du Projet (In / Out)

### Dans le périmètre (In-Scope)
* **Authentification sécurisée** : Inscription, connexion, déconnexion avec restriction d'accès par email professionnel.
* **Gestion des trajets (CRUD)** : Publication par le conducteur, modification et suppression sous conditions.
* **Gestion des réservations** : Soumission par le passager, suivi et traitement par le conducteur avec transition d'états contrôlée.
* **Brique d'Intelligence Artificielle** : Calcul automatique d'un score de compatibilité (0-100), d'une justification textuelle et d'une suggestion d'horaire lors d'une recherche passager.
* **Cast Eloquent personnalisé** : Sérialisation et désérialisation propre des données structurées renvoyées par l'IA.
* **Seeding de démonstration** : Population de la base avec le jeu de données réaliste fourni.
* **Tests automatisés** : Suite de tests couvrant les règles métier critiques.

### Hors périmètre (Out-of-Scope)
* Paiement en ligne ou partage de frais financier.
* Géolocalisation en temps réel ou calcul d'itinéraires sur carte interactive.
* Messagerie instantanée entre utilisateurs.
* Système d'évaluation, d'avis ou d'étoiles après le trajet.

---

## 4. Règles Métier Critiques

1. **Unicité de l'email** : Un employé possède un email professionnel unique et ne peut être rattaché qu'à une seule entreprise.
2. **Capacité des trajets** : Le nombre de réservations confirmées pour un trajet donné ne peut jamais dépasser le nombre de places disponibles.
3. **Double réservation interdite** : Un passager ne peut pas réserver deux fois le même trajet.
4. **Suppression protégée** : Un trajet ne peut pas être supprimé par son conducteur s'il possède au moins une réservation confirmée.
5. **Machine à états des réservations** :
   * Une réservation nouvellement créée prend le statut `en_attente`.
   * Le conducteur peut la passer à `confirmee` ou `refusee`.
   * Le passager peut passer sa réservation à `annulee` (qu'elle soit `en_attente` ou `confirmee`).
   * Les statuts `refusee` et `annulee` sont terminaux.
6. **Asymétrie du score IA** : Le score de compatibilité IA est calculé à la demande du passager lorsqu'il recherche un trajet, jamais côté conducteur.

---

## 5. Rédaction des User Stories (US)

### US1 : Cadrer le besoin et rédiger les User Stories
* **En tant que** Développeur, **je veux** cadrer le besoin et rédiger les User Stories **afin de** définir précisément le périmètre fonctionnel et technique de CoRide.
* **Critères d'acceptation** :
  * Document `CADRAGE.md` rédigé et disponible à la racine du projet.
  * Hors-périmètre clairement identifié.

### US2 : Planification du projet (Tâches Jira)
* **En tant que** Développeur, **je veux** découper le projet en tâches Jira **afin de** suivre l'avancement réel des développements au sein de l'équipe.
* **Critères d'acceptation** :
  * Board de projet défini avec des statuts clairs (À faire, En cours, À valider, Terminé).
  * Tâches techniques et fonctionnelles rédigées et planifiées.

### US3 : Modélisation de la base de données (MCD/MLD)
* **En tant que** Développeur, **je veux** modéliser le MCD puis le MLD en Merise **afin de** concevoir une base relationnelle cohérente (employés, entreprises, trajets, réservations).
* **Critères d'acceptation** :
  * Cardinalités et types de relations clairement explicités.
  * Clés primaires et étrangères identifiées.
  * Contrainte d'unicité composite sur le couple (trajet, passager).

### US4 : Création du projet & CRUD Trajets
* **En tant que** Conducteur, **je veux** publier, modifier et supprimer mes trajets de covoiturage **afin de** proposer des places à mes collègues.
* **Critères d'acceptation** :
  * Création d'un trajet avec : départ, arrivée, horaire, places disponibles et jours de récurrence.
  * Autorisations : Seul le propriétaire d'un trajet peut le modifier ou le supprimer.
  * Blocage de la suppression si au moins une réservation est confirmée.

### US5 : Authentification sécurisée (Breeze)
* **En tant que** Employé, **je veux** m'inscrire et me connecter de manière sécurisée **afin d'**accéder aux fonctionnalités de covoiturage de mon entreprise.
* **Critères d'acceptation** :
  * Intégration de Laravel Breeze avec Blade.
  * Inscription limitée aux adresses emails professionnelles uniques.
  * Association automatique de l'employé à son entreprise lors de l'inscription.

### US6 : Interfaces utilisateur (Vues Blade)
* **En tant que** Employé, **je veux** naviguer sur une interface claire et ergonomique **afin de** gérer mes trajets et réservations sans friction.
* **Critères d'acceptation** :
  * Page d'accueil / Dashboard centralisant mes actions.
  * Vue de recherche des trajets avec filtres.
  * Écran "Mes réservations" (effectuées en tant que passager).
  * Écran "Réservations reçues" (reçues en tant que conducteur) permettant d'accepter/refuser les demandes.

### US7 : Score de compatibilité IA (Laravel AI)
* **En tant que** Passager, **je veux** obtenir un score de compatibilité IA expliqué pour chaque trajet lors de ma recherche **afin d'**identifier rapidement le trajet le plus adapté à mes contraintes.
* **Critères d'acceptation** :
  * Intégration de `laravel/ai` pour évaluer les trajets.
  * Retour structuré de l'IA contenant : score (0-100), justification textuelle, horaire suggéré.
  * Exécution asymétrique : le calcul n'est déclenché que par le passager en recherche de trajet.

### US8 : Cast Eloquent personnalisé
* **En tant que** Développeur, **je veux** stocker le résultat de l'IA sous forme de JSON casté en base de données **afin de** manipuler proprement un objet PHP typé dans mes contrôleurs et mes vues Blade.
* **Critères d'acceptation** :
  * Création du cast `ResultatIACast`.
  * Persistance correcte du format JSON en base de données.
  * Désérialisation automatique en objet typé exposant les propriétés `score`, `justification` et `horaire_suggere`.
