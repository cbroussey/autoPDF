#!/bin/bash
if [ ! -d "./images" ]; then
	echo "Dossier 'images' non trouvé, pensez à le créer et à y insérer les photos des commerciaux"
	exit
elif [ ! -d "./logos" ]; then
	echo "Dossier 'logos' non trouvé, pensez à le créer et à y insérer les logos des régions"
	exit
fi
echo "Création des dossiers..."
mkdir qrcodes 2> /dev/null
mkdir html 2> /dev/null
mkdir pdf 2> /dev/null
mkdir DATA 2> /dev/null
echo "Nettoyage des fichiers de données..."
tr " " "_" < region.conf > region.conf2
mv region.conf2 region.conf
for nom in $(cat region.conf); do
	nom=$(echo "$nom" | cut -d ":" -f 2 | tr " " "_")
	./nettoyeur.sh $nom.txt > /dev/null
done
tr "_" " " < region.conf > region.conf2
mv region.conf2 region.conf
echo "Extraction des données..."
php extraction.php > /dev/null 2>&1
echo "Création et modification des images..."
./img.sh
cp -R /Docker/$USER/images .
cp -R /Docker/$USER/qrcodes .
echo "Création des fichiers HTML..."
php faiseur_de_html.php
echo "Conversion des fichiers HTML en PDF..."
#script pour html to pdf
echo "Nettoyage..."
rm images/1*.png qrcodes/* html/* DATA/*
rmdir qrcodes html DATA
echo "Fichiers enregistrés dans le Dossier 'pdf'"