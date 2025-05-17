# Helpdesk / Logiciel de traitement de tickets

Bienvenue sur mon projet de gestion de tickets ! Effectué avec Bogala Jan et Balens Matthis.
![image](https://github.com/user-attachments/assets/8429ab56-2c9a-4752-ae64-c3531bd2c576)



Ce projet est un site de gestion de tickets pour entreprises ou parc informatique, conçu pour aider aussi bien les techniciens réseau débutants que professionnels. Ce projet a été développé dans le cadre d'un BTS SIO SISR à partir d'une idée de projet en cours.

Ce site est entièrement libre et modifiable selon vos besoins.

---

## Fonctionnalités



### 1. Création de tickets pour demander à résoudre un incident (Avec gestion de connexions actives) :
- **Base de données gérant les différents comptes utilisateurs** comprenant :
  - Nom
  - Prenom
  - Mail
  - Nom d'utilisateur
  - Mot de passe (**En format MD5**)
- **Opérations disponibles** :
  - Changement de votre mot de passe en cas de problème
  - Création de tickets (Titre, description, type de problème, notre service, notre OS)
  - Consultation de nos tickets (leurs détails, statuts, etc)
  - Modification de notre tickets si il est encore en attente
 
    ![image](https://github.com/user-attachments/assets/008f6251-f14c-4382-8896-9163cb3d99e2)

    ![image](https://github.com/user-attachments/assets/be436225-14db-445d-8432-39f0e06a54d7)

    ![image](https://github.com/user-attachments/assets/ad63fb6c-4ea2-4bc3-a489-7d50cae9af6a)



### 2. Coté adminstrateur/technicien (Avec gestion de connexions actives) :
- Un tableau de bord dynamic, avec affichage des tickets, leurs status, statistiques général, etc avec une fonctionnalité de mode sombre 
![image](https://github.com/user-attachments/assets/0c485152-df2e-481a-a976-47f72685d023)
  
- **Gestion des tickets** :
  - Modification de type de priorité (bas, moyen, élevée, critique)
  - Modification du status de prise en charge
  - Affichage dynamic en fonctions des filtres voulues
  - Détail de l'ensemble du ticket
![image](https://github.com/user-attachments/assets/83daaf61-1ca2-4781-8aec-9e18df747b96)
    
- **Gestion des utilisateurs** :
  - Ajouts et suppréssion d'utilisateur avec des formulaires
  - Recherche dynamic d'utilisateur avec saisie du nom de famille
  - Détail de l'ensemble des utilisateurs (nom, prénom, nom d'utilisateur, mot de passe en MD5)
![image](https://github.com/user-attachments/assets/5e0a78f7-0684-42ba-b0e3-3655b4319eea)

- **Gestion des administrateurs** :
  - Ajouts et suppréssion d'administrateurs avec des formulaires et sécurité en cas d'oublie de déconnexion (demande de resaisie d'identifiants pour valider)
  - Détail de l'ensemble des administrateurs (nom, prénom, nom d'utilisateur)
![image](https://github.com/user-attachments/assets/7173c2d3-47bd-4a3d-b591-798103d8d2df)

- **Gestion des services de l'entreprise** :
  - Ajouts et suppréssion de service avec des formulaires et sécurité en cas d'oublie de déconnexion (demande de resaisie d'identifiants pour valider)
  - Détail de l'ensemble des service (identifiants et nom)
![image](https://github.com/user-attachments/assets/9e7e4782-5562-4431-9a83-3ee8ef769f19)

- **Affichage des différents logs et recherche en fonction des jours** :
![image](https://github.com/user-attachments/assets/b23ba27a-004b-431a-8f59-a9f7005cb0d5)


---

## Installation

Exécutez le script install.sh qui effectueras l'inserssion du fichier sql et des droits nécéssaires.

---

## Tutoriels

Des vidéos tutoriels sont disponibles pour vous aider à utiliser le site :
[Voir les tutoriels]()

---

## Contribution

Le projet est un projet **open-source**. Vous pouvez le modifier, l'améliorer et proposer des mises à jour.

N'hésitez pas à ouvrir une issue ou une pull request pour toute amélioration ou correction.

---

## Licence

Ce projet est sous licence **libre**. Vous êtes libre de l'utiliser, le modifier et le redistribuer selon vos besoins.

---

Merci de l'utiliser ! 🚀

