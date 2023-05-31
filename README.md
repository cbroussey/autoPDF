# autoPDF
Génération automatique de PDF à partir de fichiers de données

Le script principal à lancer est le script `auto.sh`

## Le projet
Nous avons été missionnés d'automatiser le traitement de jeux de données afin de générer des fichiers PDF personnalisés pour des comptes-rendus administratifs

## Ce que nous avons réalisé
La réalisation est faite de plusieurs scripts en bash, PHP, HTML et CSS. Le tout est automatisé avec un seul script `auto.sh` qui réalise le suivant : il va d'abord exécuter un script bash `nettoyeur.sh` qui va se charger d'uniformiser les jeux de données, puis un script `extraction.php` va se charger d'extraire toutes les données importantes et les sauvegarder dans des fichiers en .DATA. Le script va continuer en exécutant l'autre script `faiseur_de_html.php` qui va se charger de générer des pages web personnalisées à partir des données extraites, enfin le script va se charger de convertir le tout en fichiers PDF et nettoyer derrière lui tous les fichiers temporaires créés
