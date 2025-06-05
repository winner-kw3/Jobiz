# Jobiz - Plateforme d'Emploi Tech

![Jobiz Logo](public/assets/jobiz/image.png)

## À propos du projet

Jobiz est une plateforme dédiée à la mise en relation des professionnels de l'informatique avec des entreprises tech innovantes. Ce projet a été réalisé dans le cadre d'un projet scolaire à Keyce, développé entièrement sous Symfony en seulement 1 journée.

## Auteurs

- **Winner** - [GitHub Profile](https://github.com/winner-kw3/)
- **Samuel** - [GitHub Profile](https://github.com/samitochi04)

## Fonctionnalités

- Recherche d'emplois tech par catégories, pays, et type de contrat
- Filtrage des offres en télétravail
- Visualisation détaillée des offres d'emploi
- Candidature en ligne avec lettre de motivation
- Système d'authentification complet
- Interface d'administration pour la gestion du contenu
- Design responsive avec Tailwind CSS

## Captures d'écran

### Page d'accueil

![Page d'accueil](public/assets/jobiz/image1.png)

### Liste des offres

![Liste des offres](public/assets/jobiz/image2.png)

### Détail d'une offre

![Détail d'une offre](public/assets/jobiz/image3.png)

### Formulaire de candidature

![Candidature](public/assets/jobiz/image4.png)

### Page d'inscription

![Inscription](public/assets/jobiz/image5.png)

### Recherche d'emploi

![Recherche](public/assets/jobiz/image6.png)

### Interface Admin

![Admin](public/assets/jobiz/image7.png)

## Technologies utilisées

- **Framework:** Symfony 6
- **Base de données:** MySQL / MariaDB
- **Frontend:** Twig, Tailwind CSS
- **Bibliothèques:** KnpPaginatorBundle
- **Admin:** EasyAdmin Bundle

## Installation

1. Cloner le dépôt

   ```
   git clone [url-du-repo]
   ```

2. Installer les dépendances

   ```
   composer install
   ```

3. Configurer la base de données dans le fichier `.env.local`

   ```
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/jobiz?serverVersion=5.7"
   ```

4. Créer la base de données

   ```
   php bin/console doctrine:database:create
   ```

5. Exécuter les migrations

   ```
   php bin/console doctrine:migrations:migrate
   ```

6. Charger les fixtures (données de test)

   ```
   php bin/console doctrine:fixtures:load
   ```

7. Démarrer le serveur
   ```
   symfony serve
   ```

## Contexte du projet

Ce projet a été développé dans le cadre d'une évaluation à Keyce, avec le défi de créer une application fonctionnelle en Symfony en une seule journée. Malgré ce délai serré, nous avons réussi à implémenter les fonctionnalités essentielles d'une plateforme de recherche d'emploi moderne.

---

© 2023 Jobiz - Codé par Winner & Samuel
