# Gestion des Absences/Présences - Documentation Complète

## 🎯 Objectif Principal

Permettre aux formateurs d'enregistrer, modifier et consulter les absences/présences des apprenants avec support complet des **enregistrements multiples** pour une même séance.

---

## ✨ NOUVELLE FONCTIONNALITÉ - Enregistrements Multiples

### Caractéristiques

- ✅ Un formateur peut enregistrer les absences plusieurs fois pour la **même séance**
- ✅ Chaque enregistrement reçoit un **numéro d'enregistrement** (1, 2, 3, ...)
- ✅ **UUID unique** identifie chaque session d'enregistrement
- ✅ **Historique complet** accessible et consultable
- ✅ Support des **observations** par absence (ex: "Justificatif reçu")
- ✅ **Traçabilité**: Chaque enregistrement sait qui l'a créé et quand
- ✅ **Modification par création**: Les anciens enregistrements restent intacts

---

## 📋 Modifications de Base de Données

### Migration 1: `2026_07_02_000000_fix_presences_statut_column`

- Changement du type `statut` de `date` à `string`
- Valeur par défaut: `'absent'`

### Migration 2: `2026_07_02_000001_improve_presences_table` ✨ NOUVELLE

**Colonnes Ajoutées:**

```sql
session_enregistrement_id UUID UNIQUE          -- Identifie une session d'enregistrement
numero_enregistrement INT DEFAULT 1             -- Numéro séquentiel (1, 2, 3...)
observations TEXT NULL                          -- Notes: "Justificatif reçu", etc.
formateur_id UNSIGNED BIGINT                    -- Qui a créé l'enregistrement
```

**Indexes:**

- Index composite: `(emploi_temp_id, session_enregistrement_id)`
- Foreign key: `formateur_id → formateurs.id`

---

## 🔧 Statuts Disponibles

| Code       | Label    | Couleur  | Signification     |
| ---------- | -------- | -------- | ----------------- |
| `present`  | Présent  | 🟢 Vert  | Apprenant présent |
| `absent`   | Absent   | 🔴 Rouge | Apprenant absent  |
| `retard`   | Retard   | 🟡 Jaune | Arrivée tardive   |
| `justifie` | Justifié | 🔵 Bleu  | Absence justifiée |

---

## 📝 Fichiers Modifiés/Créés

| Fichier                                              | Action      | Description                                       |
| ---------------------------------------------------- | ----------- | ------------------------------------------------- |
| `app/Models/Presences.php`                           | ✏️ Modifié  | Nouveaux statuts, relations, méthodes utilitaires |
| `app/Livewire/Presence/Create.php`                   | ✏️ Modifié  | Support enregistrements multiples, historique     |
| `app/Livewire/Presence/Index.php`                    | ✏️ Modifié  | Filtres par enregistrement numéro                 |
| `resources/views/livewire/presence/create.blade.php` | ✏️ Modifié  | Historique + observations                         |
| `resources/views/livewire/presence/index.blade.php`  | ✏️ Modifié  | Affichage numéro enregistrement + observations    |
| `database/migrations/2026_07_02_000000_*`            | ✏️ Existant | Correction colonne statut                         |
| `database/migrations/2026_07_02_000001_*`            | ✨ NOUVEAU  | Enregistrements multiples                         |

---

## 🎯 Flux Utilisateur - Enregistrement

```
1. Sélectionner séance
   ↓
2. Voir historique (3 derniers enregistrements)
   ↓
3. Option A: Créer nouvel enregistrement
   Option B: Charger ancien pour modification
   ↓
4. Définir statuts + observations
   ↓
5. Cliquer "Enregistrer un nouvel enregistrement"
   ↓
6. Résultat: "Enregistrement #2 créé! (25 apprenants)"
```

### Exemple Concret

- **Jeudi 14h**: Créer Enregistrement #1 (20 présents, 5 absents)
- **Jeudi 14h30**: Corriger → Enregistrement #2 (1 apprenant devient présent)
- **Jeudi 15h**: Ajouter justification → Enregistrement #3 (2 absences justifiées)

**Résultat**: 3 enregistrements distincts conservés avec traçabilité complète

---

## 🎯 Flux Utilisateur - Consultation

```
1. Aller à /presences/historique
   ↓
2. Sélectionner séance
   ↓
3. Voir résumé des enregistrements
   ↓
4. Sélectionner enregistrement (optionnel)
   ↓
5. Filtrer par statut (optionnel)
   ↓
6. Tableau affiche tous les détails + numéro enregistrement
```

---

## 🚀 Routes Disponibles

| Route                       | Composant | Description                   |
| --------------------------- | --------- | ----------------------------- |
| `GET /presences`            | `Create`  | Enregistrer/corriger absences |
| `GET /presences/historique` | `Index`   | Consulter historique complet  |

---

## 💡 Améliorations de l'Interface

### Vue Create (Enregistrement)

- ✅ Affichage des 3 derniers enregistrements
- ✅ Boutons cliquables pour charger anciens enregistrements
- ✅ Colonne "Observations" dans le tableau
- ✅ Message succès avec numéro d'enregistrement
- ✅ Support des observations (texte libre)

### Vue Index (Historique)

- ✅ Sélection par séance
- ✅ **Sélection par numéro d'enregistrement** ✨
- ✅ Filtrage par statut
- ✅ Affichage numéro enregistrement en badge
- ✅ Affichage des observations
- ✅ Tableau avec 6 colonnes (dont numéro + observations)

---

## 🔒 Sécurité & Traçabilité

- ✅ Authentification requise
- ✅ Vérification du formateur connecté
- ✅ Vérification que le formateur enseigne la séance
- ✅ **UUID unique** pour chaque session
- ✅ **formateur_id** enregistré pour chaque entrée
- ✅ Timestamps (created_at, updated_at)
- ✅ Aucune modification d'enregistrements précédents

---

## 📊 Modèle Presences - Nouvelles Méthodes

```php
// Récupérer les numéros d'enregistrements pour une séance
Presences::getSessionsEnregistrement($emploiTempId)

// Obtenir le dernier enregistrement
Presences::getLatestSession($emploiTempId)

// Obtenir toutes les absences d'une session
Presences::getBySession($emploiTempId, $sessionId)

// Vérifier le statut
$presence->isPresent()
$presence->isAbsent()
```

---

## 🎓 Exemples Concrets

### Scenario 1: Enregistrement Initial

```
Formateur ouvre /presences
→ Sélectionne "Python 101 - Lundi 10h"
→ 25 apprenants affichés
→ Définit statuts
→ Ajoute "Justificatif: Sophie (retard bus)"
→ Clique "Enregistrer un nouvel enregistrement"
✅ Enregistrement #1 créé (UUID: 550e8400-e29b-41d4-a716-446655440000)
```

### Scenario 2: Correction Rapide

```
Même jour, 30 min plus tard...

Formateur ouvre /presences
→ Sélectionne "Python 101 - Lundi 10h"
→ Voit "Enregistrement #1 - 14:05"
→ Clique sur ce bouton pour charger
→ Change "Apprenant X" de "absent" à "present"
→ Clique "Enregistrer un nouvel enregistrement"
✅ Enregistrement #2 créé (UUID: 550e8400-e29b-41d4-a716-446655440001)
```

### Scenario 3: Comparaison Historique

```
Formateur ouvre /presences/historique
→ Sélectionne "Python 101 - Lundi 10h"
→ Voit 3 enregistrements:
   - #3 (16:30)
   - #2 (14:35)
   - #1 (14:05)
→ Sélectionne #2
→ Tableau montre uniquement les données de l'enregistrement #2
→ Peut voir les observations associées
```

---

## 📈 Structure de Données Complète

### Table `presences`

```sql
id                          BIGINT PRIMARY KEY
inscription_id              BIGINT FK → inscriptions
emploi_temp_id              BIGINT FK → emploi_temps
session_enregistrement_id   UUID (index)
numero_enregistrement       INT (1, 2, 3, ...)
datePresence                DATETIME
statut                      VARCHAR(255) [present|absent|retard|justifie]
observations                TEXT
formateur_id                BIGINT FK → formateurs
created_at                  TIMESTAMP
updated_at                  TIMESTAMP
```

### Index Principaux

- Primary: `id`
- Unique: `session_enregistrement_id`
- Composite: `(emploi_temp_id, session_enregistrement_id)`
- Foreign: `inscription_id`, `emploi_temp_id`, `formateur_id`

---

## ✅ Checklist d'Implémentation

- ✅ Migration 1: Correction colonne `statut` - Exécutée
- ✅ Migration 2: Support enregistrements multiples - Exécutée
- ✅ Modèle Presences - Enrichi
- ✅ Composant Create - Implémenté
- ✅ Composant Index - Amélioré
- ✅ Vue Create - Enrichie
- ✅ Vue Index - Enrichie
- ✅ Routes - Configurées

---

## 🎁 Avantages de Cette Approche

1. **Historique Complet**: Aucune perte de données
2. **Traçabilité**: Qui, quand, et quoi pour chaque enregistrement
3. **Flexibilité**: Corriger sans suppression
4. **Audit**: Vérifier les modifications effectuées
5. **Observation**: Justifications et notes attachées
6. **Performance**: Requêtes optimisées avec indexes

---

## 🚀 Prochaines Étapes Optionnelles

- [ ] Dashboard des statistiques de présence
- [ ] Export en PDF/Excel avec enregistrement numéroté
- [ ] Rapports de présence par apprenant/période
- [ ] Notifications au responsable
- [ ] Archive des anciens enregistrements
- [ ] Aperçu avant enregistrement
- [ ] Sauvegarde temporaire (brouillon)

---

## 📞 Support & Maintenance

**Question**: Comment éviter les doublons?
**Réponse**: Chaque enregistrement est unique via `session_enregistrement_id` (UUID)

**Question**: Peut-on restaurer un ancien enregistrement?
**Réponse**: Oui, en cliquant sur le bouton correspondant, les données se rechargent

**Question**: Qui peut voir les enregistrements?
**Réponse**: Le formateur voit ses propres enregistrements (filtrage automatique)
