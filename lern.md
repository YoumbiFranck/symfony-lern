# C'est quoi une entité en Symfony ?

En Symfony (et plus largement dans le contexte d'un framework MVC utilisant Doctrine comme ORM), une **entité** est une **classe PHP qui représente une table dans la base de données**.

### 📌 Définition simple :

> Une entité est un **objet métier** qui correspond à une **structure de données persistante**. Chaque instance de cette entité correspond à une ligne dans une table.

---

### 🔧 Exemple d'entité :

```php
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'float')]
    private float $price;

    // Getters et setters...
}
```

---

### 🔄 Que fait Symfony avec cette entité ?

* Grâce à Doctrine, Symfony peut **générer automatiquement la table** dans la base de données à partir de cette classe (migration).
* Elle permet de **créer, lire, modifier, supprimer** des enregistrements de manière orientée objet.
* Elle est liée au **Repository**, qui permet de faire des requêtes personnalisées sur cette entité.

---

### ✅ Caractéristiques d’une entité Symfony :

* Elle doit être annotée (ou utiliser des attributs PHP8+) pour que Doctrine comprenne comment la mapper.
* Elle contient généralement des **propriétés privées** et des **getters/setters** publics.
* Elle peut contenir des **relations** (`OneToMany`, `ManyToOne`, etc.) avec d'autres entités.

---

### 📦 Résumé :

| Élément    | Rôle                                 |
| ---------- | ------------------------------------ |
| Entité     | Représente une table                 |
| Attributs  | Colonnes de la table                 |
| Doctrine   | ORM qui gère la persistance          |
| Repository | Pour interagir avec l'entité en base |


---

# Comment créer une entité en Symfony ? 

Pour créer une entité en Symfony, tu peux utiliser la **console Symfony** avec la commande `make:entity`, qui fait partie du **MakerBundle**.

---

## ✅ Étapes pour créer une entité

### 1. 📦 Vérifie que le MakerBundle est installé

Il est normalement inclus dans les projets récents, mais au cas où :

```bash
composer require symfony/maker-bundle --dev
```

---

### 2. ▶️ Utilise la commande pour créer une entité

```bash
php bin/console make:entity
```

Symfony te posera des questions interactives :

```
Class name of the entity to create or update (e.g. Product):
> Product

New field name (press <return> to stop adding fields):
> name

Field type (enter ? to see all types) [string]:
> string

Field length [255]:
> 100

Can this field be null in the database (nullable) (yes/no) [no]:
> no
```

Tu peux ajouter autant de champs que tu veux. Appuie sur **Entrée sans rien taper** pour terminer.

---

### 3. 🧠 Résultat : Symfony crée un fichier dans `src/Entity/`

Exemple : `src/Entity/Product.php`

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    // ... Getters / Setters ...
}
```

---

### 4. 🧱 Génère la base de données (optionnel)

Si tu veux créer la table correspondante dans ta base :

#### a. Créer une migration :

```bash
php bin/console make:migration
```

#### b. Exécuter la migration :

```bash
php bin/console doctrine:migrations:migrate
```

---

## 📌 Résumé

| Étape               | Commande                                      |
| ------------------- | --------------------------------------------- |
| Créer l'entité      | `php bin/console make:entity`                 |
| Générer migration   | `php bin/console make:migration`              |
| Appliquer migration | `php bin/console doctrine:migrations:migrate` |


---

# C'est quoi une migration et comment l'éxécuter ?


Une **migration** en Symfony (via Doctrine) est un **fichier PHP qui contient les instructions nécessaires pour modifier la structure de la base de données** (ajouter une table, une colonne, une clé étrangère, etc.).

---

## 🔍 À quoi sert une migration ?

Quand tu crées ou modifies une entité, Symfony **ne modifie pas directement la base de données**. Tu dois :

1. **Générer** une migration pour convertir les changements de l'entité en SQL.
2. **Exécuter** cette migration pour appliquer les changements à la base.

---

## 🛠️ Comment utiliser les migrations

### 1. Générer une migration

```bash
php bin/console make:migration
```

🔹 Symfony scanne les entités et compare avec l'état actuel de la base.

🔹 Il crée un fichier dans le dossier `migrations/`, par exemple :

```
migrations/Version20250729101500.php
```

Ce fichier contient des instructions SQL comme :

```php
public function up(Schema $schema): void
{
    $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
}
```

---

### 2. Exécuter la migration

```bash
php bin/console doctrine:migrations:migrate
```

🔹 Symfony applique les modifications à la base de données.

---

## 📂 Où sont stockées les migrations ?

Les fichiers sont dans le dossier :

```
/migrations
```

Ils sont versionnés et servent à suivre l'évolution de la base de données dans le temps.

---

## 🔄 Exemple complet

1. Tu crées une entité `Article` :

```bash
php bin/console make:entity
```

2. Tu génères la migration :

```bash
php bin/console make:migration
```

3. Tu exécutes la migration :

```bash
php bin/console doctrine:migrations:migrate
```

---

## 🧠 Astuce

Tu peux vérifier ce que Doctrine va faire **avant** de créer la migration avec :

```bash
php bin/console doctrine:schema:update --dump-sql
```

Mais n’utilise **pas** `--force` en prod : ça applique directement les changements sans migration !




