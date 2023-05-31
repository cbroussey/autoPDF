<?php
    // mise dans un tableau de chaques lignes de region.conf.txt
    $fic_conf = file("region.conf");
    // calcul du nombre de lignes du fichier region.conf.txt
    $taille_conf = sizeof($fic_conf);
    // pour chaque région présente dans la conf on stock son nom dans le tableau fragmentation
    for ($loop=0;$loop<$taille_conf;$loop++)
    {
        $ligne = explode(":",$fic_conf[$loop]);
        $fragmentation[$loop]=$ligne[1];
    }
    for ($boucle=0;$boucle<$taille_conf;$boucle++)
    {
        // mise en tableau des lignes du fichier texte correspondant à la région de la ligne loop du fichier conf
        $fic = file("$fragmentation[$boucle].txt");
        // création des 3 fichiers de données
        $texte = fopen("DATA/texte_$fragmentation[$boucle].DATA","w");
        $tab = fopen("DATA/tableau_$fragmentation[$boucle].DATA","w");
        $producteurs = fopen("DATA/comm_$fragmentation[$boucle].DATA","w");
        // initialisation des variables d'écriture à faux car on commence à écrire qu'à un instant précis
        $ecr=false;
        $ecrtab=false;
        $ecrsour=false;
        $produc1 = [];        
        foreach ($fic as $lig)
        {   
            // les recherche de motifs dans le fichier région actuel
            $rec_deb = strpos($lig,"DEBUT_TEXTE"); 
            $rec_fin = strpos($lig,"FIN_TEXTE");
            $tab_deb = strpos($lig,"DEBUT_STATS"); 
            $tab_fin = strpos($lig,"FIN_STATS");
            $meilleurs = strpos($lig,"MEILLEURS");
            $titres = strpos($lig,"TITRE");
            $sources_deb = strpos($lig,"DEBUT_CREDIT");
            $sources_fin = strpos($lig,"FIN_CREDIT");
            $i=0;
            $j=0;
            // extraction des titres
            if ($titres !== false)
            {
			    $vrais_titres = explode("=",$lig);
                fwrite($texte,"\n".($vrais_titres[0][0] != "S" ? "\n" : "").$vrais_titres[1]."\n");
		    }
 /*extraction des meilleurs commerciaux : on extrait juste les 3 premiers commerciaux car le fichier de nettoyage les a déjà triés par ordre         décroissant*/
            // si on trouve le motif "Meilleur"
            if ($meilleurs !== false)
            {
                //manip pour enlever meilleur de la ligne des commerciaux
                $prod = explode(":",$lig);
                $tab_propre = $prod[1];
                $suite = explode(",",$tab_propre);
                
            for ($j=0;$j<3;$j++)
                {
                    $produc1[$j] = $suite[$j];
                    // on sépare le commercial de son chiffre d'affaire par une virgule
                    $produc1[$j] = explode("/", $suite[$j]);
                    $produc1[$j] = implode("=", $produc1[$j]);
                    $sep = explode("=",$produc1[$j]);
                    fwrite($producteurs,$sep[0] .",".$sep[1].",".$sep[2]."\n");
                }
         }
        // si on trouve le motif de début on commence à écrire
        if ($rec_deb !== false)
        {
            $ecr=true;
        }
        // si on trouve le motif de fin on arrête d'écrire
        if ($rec_fin !== false)
        {
            $ecr=false;                    
        }
        if ($tab_deb !== false)
        {
            $ecrtab=true;                    
        }
        if ($tab_fin !== false)
        {
            $ecrtab=false;                    
        }
        if ($sources_deb !== false)
        {
            $ecrsour=true;                    
        }
        if ($sources_fin !== false)
        {
            $ecrsour=false;                    
        }
        // si on est en train d'écrire et qu'on est pas à la ligne de notre motif on écrit dans le fichier correspondant à la donnée
        // encadré par DEBUT et FIN TEXTE
        if ($ecr === true && $rec_deb === false)
        {
            //echo $lig;
            fwrite($texte,$lig);                 
        }
        // DEBUT et FIN CREDITS
        if ($ecrsour === true && $sources_deb === false)
        {
            //echo $lig;
            fwrite($texte,"\n".$lig);                 
        }
        // DEBUT et FIN STATS
        if($ecrtab === true && $tab_deb === false)
        {
            // recuperation de ligne qu'on explose en cellule de tableau pour chaque colonne du tableau de produit
            $tablo = explode(",",trim($lig));
            //calcul de la difference de Chiffre d'affaire entre l'annee précédente et l'année actuelle
            $CA_diff = intval($tablo[2]) - intval($tablo[4]);
            // calcul d'un pourcent de la différence de chiffre d'affaire
            $un_pourcent = (intval($tablo[2]))/100;
            // calcul du pourcentage de différence du chiffre d'affaire
            $pourcentage = ($CA_diff / $un_pourcent);
            // ajout de la colonne calculée
            $tablo[5] = round($pourcentage,2)."%";
            // on reforme la ligne avec le implode pour l'écrire dans le fichier de données
            $tablo_final = implode(" ",$tablo);
            // on écrit la ligne précédente dans le fichier en passa,t à la ligne à la fin 
            fwrite($tab,$tablo_final."\n");
        }
      }  
    //fermeture des fichiers
    fclose($texte);
    fclose($tab);
    fclose($producteurs);
    }
?>
