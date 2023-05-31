#!/usr/bin/bash
#docker image pull sae103-imagick
docker container run --rm -v /Docker/$USER:/work sae103-bash
cp -R ./images /Docker/$USER/images
cd /Docker/$USER/
chmod 777 images
chmod 777 images/*
for boucle in images/???.*
do
	nomFic=$(echo $boucle | head -c -5)
	docker container run -v /Docker/$USER/images:/work/images sae103-imagick "magick $boucle 1$nomFic.png && magick convert 1$nomFic.png -colorspace gray 1$nomFic.png && magick convert 1$nomFic.png -crop 555x555+22+0 1$nomFic.png && magick convert 1$nomFic.png -resize 200x200 1$nomFic.png"
done
lig=$(cut -d':' -f -1  < region.conf)
for ligne in $lig
do
	docker container run -ti -v  /Docker/$USER/qrcodes:/work sae103-qrcode qrencode -o $ligne.png "https://bigbrain.biz/"$ligne
done
echo "y" | docker container prune > /dev/null 2>&1
