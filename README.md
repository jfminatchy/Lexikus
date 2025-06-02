# Lexikus - WordPress Lexicon Plugin

Lexikus automatically links the first occurrence of defined terms within your selected WordPress content types to their respective definition posts. This saves you the tedious task of manually creating these links across your entire site.

## Description

Manually linking every term in your articles to its definition page is a time-consuming process. Lexikus streamlines this by:

* Allowing you to define which content type and taxonomy represent your lexicon/definition entries.
* Automatically finding the first instance of each lexicon term within your chosen content types (e.g., posts, pages).
* Creating a link from that term to its definition post.
* Allowing custom CSS styling for these automatically generated links.
* Displaying a tooltip with the definition's title and excerpt on hover.

## Features

* **Admin Settings Page:**
    * Select the content type that serves as your definitions (e.g., "Definition", "Lexicon Entry").
    * Select the taxonomy associated with these definitions.
    * View a dynamic list of detected definition terms.
    * Choose which content types (e.g., Posts, Pages) should have terms automatically linked.
    * A dedicated CSS editor to style the appearance of the lexical links, with a live preview.
* **Automatic Linking Logic:**
    * Operates via a WordPress hook on content rendering.
    * intelligently ignores definition posts themselves to prevent self-linking loops.
    * Targets only the **first occurrence** of a term within the content.
    * Excludes headings (H1-H6) from term searching to maintain clean headings.
    * Applies the custom CSS defined in the settings.
    * Adds a helpful tooltip on mouse hover, showing the title and excerpt of the linked definition post.

## Installation

1.  **Download:** Download the `lexikus.zip` file from the GitHub repository (or the WordPress Plugin Directory if submitted there).
2.  **Upload via WordPress Admin:**
    * Navigate to your WordPress admin dashboard.
    * Go to `Plugins` > `Add New`.
    * Click `Upload Plugin`.
    * Choose the `lexikus.zip` file and click `Install Now`.
3.  **Manual Upload (FTP):**
    * Extract the `lexikus.zip` file.
    * Upload the extracted `lexikus` folder to the `wp-content/plugins/` directory on your server.
4.  **Activate:**
    * Go to `Plugins` in your WordPress admin.
    * Find "Lexikus" in the plugin list and click `Activate`.

## Configuration

Once activated, you can configure Lexikus by navigating to **Settings > Lexikus** in your WordPress admin dashboard.

The settings page is divided into the following sections:

1.  **Selection of content type and taxonomy:**
    * **Content type:** Choose the content type that you use for your definition entries (e.g., 'Article', 'Definition', 'Glossary Term').
    * **Taxonomy term:** Select the specific taxonomy associated with your definitions if applicable (e.g., a category or tag like 'definition').
    * Click `Enregistrer` (Save) to save these settings. A dynamic list of found definitions based on your selection will appear below.

2.  **Dynamic listing of definitions:**
    * This section will automatically display terms (titles of your definition posts) based on the content type and taxonomy you selected above. This is for verification and does not require direct input.

3.  **Selection of content types to link:**
    * Check the boxes for the content types where you want Lexikus to automatically create links. For example, if you want terms in your 'Posts' and 'Pages' to link to definitions, check both.
    * Available options might include: Article, Page, Fichier média (Media File), Élément flottant (Floating Element), Modèle (Template), and any other custom post types you have.
    * Click `Enregistrer` (Save) to save your choices.

4.  **CSS editing section with preview:**
    * **CSS applied to lexical links:** Enter your custom CSS rules here to style how the automatically generated links will appear. For example: `text-decoration: underline dotted; color: #0073aa;`
    * Click `Enregistrer le CSS` (Save CSS) to apply your styles.
    * **Render preview:** Below the CSS input, you'll see an example link (`Exemple de lien lexical`) that updates to reflect your CSS changes, allowing you to preview the styling.

Once configured, Lexikus will automatically process the content of the selected post types and create the links as specified.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

# Français

Lexikus lie automatiquement la première occurrence des termes définis dans les types de contenu WordPress que vous avez sélectionnés vers leurs articles de définition respectifs. Cela vous épargne la tâche fastidieuse de créer manuellement ces liens sur l'ensemble de votre site.

## Description

Lier manuellement chaque terme de vos articles à sa page de définition est un processus chronophage. Lexikus simplifie cela en :

* Vous permettant de définir quel type de contenu et quelle taxonomie représentent vos entrées de lexique/définition.
* Trouvant automatiquement la première instance de chaque terme du lexique dans les types de contenu que vous avez choisis (par exemple, articles, pages).
* Créant un lien depuis ce terme vers son article de définition.
* Permettant une personnalisation CSS pour ces liens générés automatiquement.
* Affichant une infobulle (tooltip) avec le titre et l'extrait de la définition au survol.

## Fonctionnalités

* **Page de Réglages Administrateur :**
    * Sélectionnez le type de contenu qui sert pour vos définitions (par ex., "Définition", "Entrée de Lexique").
    * Sélectionnez la taxonomie associée à ces définitions.
    * Visualisez une liste dynamique des termes de définition détectés.
    * Choisissez les types de contenu (par ex., Articles, Pages) où les termes doivent être automatiquement liés.
    * Un éditeur CSS dédié pour styliser l'apparence des liens lexicaux, avec un aperçu en direct.
* **Logique de Liaison Automatique :**
    * Opère via un hook WordPress lors du rendu du contenu.
    * Ignore intelligemment les articles de définition eux-mêmes pour éviter les boucles d'auto-liaison.
    * Cible uniquement la **première occurrence** d'un terme dans le contenu.
    * Exclut les titres (H1-H6) de la recherche de termes pour maintenir des titres propres.
    * Applique le CSS personnalisé défini dans les réglages.
    * Ajoute une infobulle utile au survol de la souris, affichant le titre et l'extrait de l'article de définition lié.

## Installation

1.  **Téléchargement :** Téléchargez le fichier `lexikus.zip` depuis le dépôt GitHub (ou le répertoire des plugins WordPress s'il y est soumis).
2.  **Téléversement via l'administration WordPress :**
    * Naviguez vers votre tableau de bord d'administration WordPress.
    * Allez dans `Extensions` > `Ajouter`.
    * Cliquez sur `Téléverser une extension`.
    * Choisissez le fichier `lexikus.zip` et cliquez sur `Installer maintenant`.
3.  **Téléversement manuel (FTP) :**
    * Extrayez le fichier `lexikus.zip`.
    * Téléversez le dossier `lexikus` extrait dans le répertoire `wp-content/plugins/` sur votre serveur.
4.  **Activation :**
    * Allez dans `Extensions` dans votre administration WordPress.
    * Trouvez "Lexikus" dans la liste des plugins et cliquez sur `Activer`.

## Configuration

Une fois activé, vous pouvez configurer Lexikus en naviguant vers **Réglages > Lexikus** dans votre tableau de bord d'administration WordPress.

La page de réglages est divisée en plusieurs sections :

1.  **Sélection du type de contenu et de la taxonomie :**
    * **Type de contenu :** Choisissez le type de contenu que vous utilisez pour vos entrées de définition (par ex., 'Article', 'Définition', 'Terme de glossaire').
    * **Terme de taxonomie :** Sélectionnez la taxonomie spécifique associée à vos définitions si applicable (par ex., une catégorie ou une étiquette comme 'definition').
    * Cliquez sur `Enregistrer` pour sauvegarder ces réglages. Une liste dynamique des définitions trouvées en fonction de votre sélection apparaîtra ci-dessous.

2.  **Listing dynamique des définitions :**
    * Cette section affichera automatiquement les termes (titres de vos articles de définition) en fonction du type de contenu et de la taxonomie que vous avez sélectionnés ci-dessus. Ceci est à des fins de vérification et ne nécessite pas de saisie directe.

3.  **Sélection des types de contenus à lier :**
    * Cochez les cases des types de contenu où vous souhaitez que Lexikus crée automatiquement des liens. Par exemple, si vous voulez que les termes de vos 'Articles' et 'Pages' pointent vers des définitions, cochez les deux.
    * Les options disponibles peuvent inclure : Article, Page, Fichier média, Élément flottant, Modèle, et tout autre type de publication personnalisé que vous possédez.
    * Cliquez sur `Enregistrer` pour sauvegarder vos choix.

4.  **Section d'édition CSS avec aperçu :**
    * **CSS appliqué aux liens lexicaux :** Entrez vos règles CSS personnalisées ici pour styliser l'apparence des liens générés automatiquement. Par exemple : `text-decoration: underline dotted; color: #0073aa;`
    * Cliquez sur `Enregistrer le CSS` pour appliquer vos styles.
    * **Aperçu du rendu :** Sous le champ de saisie CSS, vous verrez un lien d'exemple (`Exemple de lien lexical`) qui se met à jour pour refléter vos modifications CSS, vous permettant de prévisualiser le style.

Une fois configuré, Lexikus traitera automatiquement le contenu des types de publication sélectionnés et créera les liens comme spécifié.

## Contribuer

Les "pull requests" sont les bienvenues. Pour des changements majeurs, veuillez d'abord ouvrir une "issue" pour discuter de ce que vous aimeriez changer.