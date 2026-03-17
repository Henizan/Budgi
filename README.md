# Budgi
Budgi est une application web conçue pour aider les étudiants à gérer leur budget mensuel facilement. Elle permet de répartir ses dépenses dans plusieurs catégories (loisir, nourriture, etc.), et d'envoyer une notification ainsi que des conseils de gestion lorsqu'un de ces budgets atteint un certain stade.

## Technologies
- **Frontend** : HTML5, CSS3, JavaScript (Vanilla)
- **Backend** : PHP
- **Base de données** : MySQL

## Fonctionnalités principales
- Gestion de budget mensuel étudiant
- Catégorisation des dépenses (Loisirs, Nourriture, Transport, Santé, etc.)
- Suivi du budget actuel par rapport à la limite fixée
- Ajout et historique des transactions
- Authentification sécurisée (Inscription, Connexion avec mots de passe hachés)

## Structure
- `index.html` : Page d'accueil et présentation
- `gestion.php` : Tableau de bord utilisateur récapitulant le budget et les transactions
- `new-transac.php` : Script backend ajoutant une nouvelle transaction
- `set-budget.php` : Page permettant à l'utilisateur de définir sa limite de dépenses
- `register.php` / `signin.php` : Pages de création de compte et de connexion
- `style.css` / `script.js` : Styles et scripts de base
- `images/` : Ressources graphiques

## Lancer le projet (local)
### Prérequis
- Serveur PHP/MySQL local en cours d'exécution (ex : XAMPP, WAMP, MAMP, Laragon...)
- Base de données MySQL nommée `budgi_db`

### Configuration de la messagerie / Base de données
Assurez-vous que les informations de la base de données (serveur, port, identifiant `root` et mot de passe vide) correspondent à votre serveur local dans les fichiers PHP.

### Lancement
1. Placer ce dossier dans le dossier racine de votre serveur web (ex : `htdocs` ou `www`).
2. Démarrer les services Apache et MySQL.
3. Ouvrir votre navigateur web et accéder à `http://localhost/Budgi/` (ou le nom donné au dossier).
