# CoRide — Corrections apportées

## 🐛 Bugs critiques corrigés
0. **Register/Login ne fonctionnait pas du tout** : `resources/views/auth/register.blade.php`
   n'avait **aucun champ `entreprise_id`** (select) alors que le contrôleur le passe à la vue
   et le rend **obligatoire** en validation. Résultat : l'inscription échouait systématiquement
   (silencieusement, sans que l'erreur soit visible si le layout n'affiche pas bien les erreurs),
   donc aucun compte n'était jamais créé — et le login échouait forcément après, puisque
   l'utilisateur n'existait pas en base. → select "Entreprise" ajouté au formulaire.
   Testé de bout en bout (`RegistrationPuisLoginTest`) : inscription → connexion → OK.
1. **Models mal nommés (PSR-4)** : `app/Models/entreprise.php`, `trajet.php`, `reservation.php`
   étaient en minuscule alors que les classes sont `Entreprise`, `Trajet`, `Reservation`.
   Ça fonctionne par accident sur Windows/Mac (système de fichiers insensible à la casse)
   mais **provoque une erreur fatale sur tout serveur Linux/production**. → fichiers renommés.
2. **Crash sur la page d'édition d'un trajet** : `edit.blade.php` appelait
   `$trajet->horaire->format(...)` alors que `horaire` est une simple colonne texte
   (`"08:00"`), pas une date castée. → champ remis en `<input type="text">`.
3. **Double `json_encode`** dans `ReservationController::store()` : le résultat IA était
   encodé deux fois avant d'être stocké. → on laisse le Cast Eloquent gérer la sérialisation.

## ✅ Fonctionnalités manquantes ajoutées
- **CRUD Trajets complet** : `edit`, `update`, `destroy` (routes + contrôleur + boutons dans
  la vue), avec la règle métier *"un trajet ne peut pas être supprimé s'il a une réservation
  confirmée"*, et vérification que seul le conducteur propriétaire peut modifier/supprimer.
- **Cast Eloquent réellement utilisé** : `Reservation::$casts['resultat_ia']` pointe maintenant
  vers `ScoreCompatibiliteCast`, donc l'objet `ScoreCompatibilite` (score, justification,
  horaire_suggere, points_forts, points_faibles) est vraiment exploité (US8).
- **Intégration IA réelle avec repli local** : `AiMatcher` essaie d'abord `ScoreCompatibiliteAgent`
  (laravel/ai) si une clé API est configurée, sinon retombe sur une heuristique déterministe
  locale — l'app reste utilisable hors-ligne / en démo sans clé API.
- **Contrainte unique en base** (migration) sur `(trajet_id, passager_id)` en plus du check
  applicatif, pour éviter toute double réservation en cas de requêtes concurrentes.
- **Faille IDOR corrigée** : `/dashboard/{user?}` → `/dashboard` (chaque utilisateur ne voit
  que son propre tableau de bord, plus moyen de consulter celui d'un collègue via l'URL).
- **Restriction email professionnel** : ajout de `entreprises.domaine_email` + validation à
  l'inscription (si un domaine est configuré pour l'entreprise choisie).

## 🧪 Tests
- `tests/Unit/AiMatcherTest.php` mis à jour (le service retourne un objet `ScoreCompatibilite`,
  plus un tableau).
- `tests/Feature/TrajetOwnershipTest.php` (nouveau) : autorisation edit/delete, suppression
  protégée.
- `tests/Feature/ReservationEtDashboardTest.php` (nouveau) : anti double-réservation, dashboard
  restreint à son propriétaire.
- **32/32 tests passent** (`php artisan test`), vérifié dans le sandbox.

## ⚙️ Pour lancer le projet chez toi
```bash
composer install
cp .env.example .env   # si besoin, adapter DB_* à ton environnement (mysql/sqlite)
php artisan key:generate
php artisan migrate --seed
npm install
npm run build   # ou npm run dev en développement
php artisan serve
```
Si tu veux activer la vraie IA (au lieu du repli local) : ajoute `ANTHROPIC_API_KEY` (ou une
autre clé de provider) dans `.env`.

## ⚠️ Reste à faire / points d'attention
- Committer ces changements (`git add -A && git commit`) — le repo original avait beaucoup de
  fichiers modifiés/non trackés.
- Les seeders (`EmployeSeeder`) créent des utilisateurs directement en base : pensez à vérifier
  que leurs emails correspondent aux `domaine_email` si vous voulez tester la nouvelle validation.
- Aucune policy Laravel (`Gate`/`Policy`) n'a été introduite ; l'autorisation reste faite "à la
  main" dans les contrôleurs (`autoriserConducteur()`). Pour un projet plus large, une
  `TrajetPolicy` serait plus propre.
