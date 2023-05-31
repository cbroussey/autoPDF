#!/bin/bash
# On teste si le fichier existe et si ce n'est pas un dossier
if [ ! -f "$1" -o -d "$1" ]; then
	echo "Pas de fichier modifiable trouvé"
	exit
fi
# Mise en majuscules de toutes les balises
sed -i -e 's/code=/CODE=/ig' -e 's/titre=/TITRE=/ig' -e 's/sous_titre=/SOUS_TITRE=/ig' -e 's/d[eé]but_texte/DEBUT_TEXTE/ig' -e 's/fin_texte/FIN_TEXTE/ig' -e 's/d[eé]but_stats/DEBUT_STATS/ig' -e 's/fin_stats/FIN_STATS/ig' -e 's/meilleurs:/MEILLEURS:/ig' -e 's/d[eé]but_cr[eé]dits/DEBUT_CREDITS/ig' -e 's/fin_cr[eé]dits/FIN_CREDITS/ig' "$1"
# Suppression des lignes vides du fichier
sed -i '/^$/d' "$1"
# Suppression des tabulations en début de ligne
sed -i 's/^[ \t]*//g' "$1"
# Mise en orde décroissant de la ligne des meilleurs producteurs
sed -i "s/MEILLEURS:.*/MEILLEURS:$(egrep 'MEILLEURS:' < "$1" | cut -d ':' -f 2 | tr ',' '\n' | sort -r -t '=' -k 2 | tr -s '\n\r' ',' | head -c -1 | sed -e 's/[]\/$*.^[]/\\&/g')/" "$1"