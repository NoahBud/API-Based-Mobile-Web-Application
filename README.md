# 📚 Book'in — Application mobile & API Laravel

**Book’in** est une application mobile Android connectée à une API Laravel permettant de gérer des **boîtes à livres communautaires**.  
Les utilisateurs peuvent **scanner un code-barres (ISBN)** pour ajouter, consulter ou retirer un livre de la boîte à livres la plus proche.

---

## 🚀 Fonctionnalités

### 🎯 Côté utilisateur (application Android)
- 📷 Scan de code-barres ISBN
- 🔍 Recherche automatique d'informations (titre, auteur, couverture...)
- 📍 Localisation des boîtes à livres à proximité
- ➕ Ajout d’un livre dans une boîte
- ➖ Emprunt d’un livre
- 🧾 Historique personnel des livres ajoutés/pris

### 🔧 Côté serveur (API Laravel)
- 📡 API RESTful pour gérer les utilisateurs, livres et boîtes à livres
- 🔐 Authentification via tokens (Laravel Sanctum)
- 📦 Gestion des stocks de livres par boîte
- 🗺️ Support des coordonnées GPS

---

## 🧱 Stack technique

| Composant        | Technologie utilisée             |
|------------------|----------------------------------|
| Backend API      | Laravel 11 (PHP)                 |
| Authentification | Laravel Sanctum                  |
| Base de données  | MySQL                            |
| Application mobile | Android Studio (Java)          |
| Communication    | API REST (JSON)                  |

---

## 🛠️ Installation (local)

### Backend Laravel
```bash
git clone https://github.com/NoahBud/bookin-api.git
cd bookin-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
