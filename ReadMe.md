# Jobiz - Plateforme d'Emploi Tech

![image](https://github.com/user-attachments/assets/7e09a85b-ad69-4d57-bd62-e3049654836b)

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

![image1](https://github.com/user-attachments/assets/31f12e3b-5cea-4e69-94ec-57da54216df6)

### Liste des offres

![image2](https://github.com/user-attachments/assets/8d35e79c-c85c-4c04-8cc2-0fb959f689a3)


### Détail d'une offre

![image3](https://github.com/user-attachments/assets/6f8c5f2b-a3b7-467a-a02a-9576913a7694)

### Formulaire de candidature

![image4](https://github.com/user-attachments/assets/6ed171f4-ff71-4ed4-8625-5c52f3b71d03)

### Page d'inscription

![image5](https://github.com/user-attachments/assets/332f2c47-7ecc-43c3-9fbe-eb59c52406ea)

### Recherche d'emploi

![image6](https://github.com/user-attachments/assets/add43f88-910d-4164-997d-f7cf90f66508)

### Interface Admin

![image7](https://github.com/user-attachments/assets/9da42687-59bf-4b49-9ec2-2daf697081b6)

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

© 2025 Jobiz - Codé par Winner & Samuel ( WID )
