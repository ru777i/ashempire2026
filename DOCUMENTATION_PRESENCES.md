# Gestion des Absences/Présences - Documentation des Corrections

## 🎯 Objectif

Corriger et rendre fonctionnel le système de gestion des absences/présences pour permettre aux formateurs d'insérer et modifier les présences des apprenants.

---

## ✅ Modifications Apportées

### 1. **Migration - Correction du type de colonne `statut`**

- **Fichier**: `database/migrations/2026_07_02_000000_fix_presences_statut_column.php`
- **Changement**: Le champ `statut` est passé de `date` à `string`
- **Raison**: Pour pouvoir stocker les statuts: "present", "absent", "retard", "justifie"

### 2. **Modèle Presences - Améliorations**

- **Fichier**: `app/Models/Presences.php`
- **Modifications**:
    - Correction du nom de colonne `inscription_id` (suppression de l'espace)
    - Ajout de constantes pour les statuts (PRESENT, ABSENT, RETARD, JUSTIFIE)
    - Ajout du cast pour `datePresence` en `datetime`
    - Correction des relations (emploiTemps, inscription, apprenant)
    - Ajout de méthodes utilitaires: `getStatuts()`, `isPresent()`, `isAbsent()`

### 3. **Composant Livewire - Create (Nouvelle implémentation)**

- **Fichier**: `app/Livewire/Presence/Create.php`
- **Fonctionnalités**:
    - Récupération automatique du formateur connecté
    - Récupération des emplois du temps du formateur
    - Chargement dynamique des inscriptions selon la séance sélectionnée
    - Gestion des statuts avec dropdowns
    - Sauvegarde des présences avec `updateOrCreate`
    - Sécurité: Vérification que le formateur enseigne la séance
    - Messages de succès/erreur

### 4. **Composant Livewire - Index (Nouvelle implémentation)**

- **Fichier**: `app/Livewire/Presence/Index.php`
- **Fonctionnalités**:
    - Affichage de l'historique des présences
    - Filtrage par séance
    - Filtrage par statut
    - Pagination intégrée

### 5. **Vues Blade**

- **Fichier Create**: `resources/views/livewire/presence/create.blade.php`
    - Interface pour enregistrer les présences
    - Sélection dynamique des séances
    - Tableau avec sélecteurs de statut
    - Bouton d'enregistrement

- **Fichier Index**: `resources/views/livewire/presence/index.blade.php`
    - Affichage de l'historique des présences
    - Filtres pour rechercher
    - Affichage coloré des statuts

### 6. **Routes - Ajout des routes pour les présences**

- **Fichier**: `routes/web.php`
- **Routes ajoutées**:
    - `GET /presences` → Enregistrer les présences (Create)
    - `GET /presences/historique` → Consulter les présences (Index)

---

## 🔧 Statuts Disponibles

| Statut   | Code       | Description                      |
| -------- | ---------- | -------------------------------- |
| Présent  | `present`  | L'apprenant était présent        |
| Absent   | `absent`   | L'apprenant était absent         |
| Retard   | `retard`   | L'apprenant est arrivé en retard |
| Justifié | `justifie` | L'absence est justifiée          |

---

## 📋 Flux d'Utilisation

### Pour Enregistrer des Présences (Formateur)

1. Accéder à `/presences`
2. Sélectionner une séance dans le dropdown
3. Les apprenants inscrits s'affichent automatiquement
4. Pour chaque apprenant, sélectionner son statut (Présent/Absent/Retard/Justifié)
5. Cliquer sur "Enregistrer les présences"
6. Un message de confirmation s'affiche

### Pour Consulter l'Historique

1. Accéder à `/presences/historique`
2. (Optionnel) Sélectionner une séance pour filtrer
3. (Optionnel) Sélectionner un statut pour filtrer
4. Affichage des résultats avec code couleur

---

## 🔒 Sécurité

- ✅ Vérification que l'utilisateur est un formateur
- ✅ Vérification que le formateur enseigne la séance
- ✅ Utilisation de `updateOrCreate` pour éviter les doublons
- ✅ Authentification requise sur toutes les routes

---

## 🔍 Vérifications Effectuées

- ✅ Migration exécutée avec succès
- ✅ Modèles avec relations correctes
- ✅ Composants Livewire fonctionnels
- ✅ Routes configurées
- ✅ Vues Blade créées

---

## ⚙️ Configuration de la Base de Données

### Table `presences`

```sql
CREATE TABLE presences (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  inscription_id BIGINT FOREIGN KEY REFERENCES inscriptions(id),
  emploi_temp_id BIGINT FOREIGN KEY REFERENCES emploi_temps(id),
  datePresence DATETIME,
  statut VARCHAR(255) DEFAULT 'absent',
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## 📝 Notes Importantes

1. **Relation Formateur**: Le système récupère automatiquement le formateur connecté via `Auth::user()->id`
2. **Date/Heure**: La date/heure de présence est automatiquement enregistrée au moment de la sauvegarde
3. **Modification**: Un formateur peut modifier les présences d'une séance autant qu'il le souhaite (grâce à `updateOrCreate`)
4. **Filtrage**: Les formateurs ne voient que leurs propres séances

---

## 🚀 Prochaines Étapes (Optionnel)

- [ ] Ajouter des rapports de présence
- [ ] Implémenter un système de notifications
- [ ] Ajouter l'export des présences en PDF/Excel
- [ ] Créer un dashboard des statistiques de présence
- [ ] Ajouter une validation des dates
