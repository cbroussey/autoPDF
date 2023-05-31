#!/bin/bash

lig=$(cut -d':' -f -1  < region.conf)
for ligne in $lig
	do
		docker container run -ti -v  /Docker/$USER/test:/work sae103-qrcode qrencode -o $ligne.png "https://bigbrain.biz/"$ligne
	done
