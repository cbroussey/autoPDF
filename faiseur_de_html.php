<?php
    $fichier = file("region.html");
    $conf = file("region.conf");
    foreach($conf as $region) {
        $region = rtrim($region);
        $region = explode(":", $region);
        $copie = fopen("html/" . $region[1] . ".html", "w");
        $maintenant = date('d-m-Y H:i:s');
        $donnees = [
            $region[1],
            $region[2],
            $region[3],
            $region[4],
            "src='../logos/" . $region[0] . ".png'",
            $region[1],
            $maintenant,
            "0" . strval(intdiv(intval(date("m")), 4) + 1) . date("-Y"),
            "TEXTE",
            "TABLEAU",
            $maintenant,
            "COMM",
            $maintenant,
            "https://bigbrain.biz/". $region[0],
            "https://bigbrain.biz/". $region[0],
            "src='../qrcodes/" . $region[0] . ".png'",
            $maintenant
        ];
        $i = 0;
        foreach($fichier as $ligne) {
            if (rtrim($ligne) == "") {
                if ($donnees[$i] == "TEXTE") {
                    $texte = file("DATA/texte_" . $region[1] . ".DATA");
                    $retours = 0;
                    foreach($texte as $paragraphe) {
                        $paragraphe = rtrim($paragraphe);
                        if ($paragraphe == "") {
                            $retours++;
                            $retours %= 3;
                        } else {
                            if ($retours == 1) {
                                fwrite($copie, "<h2>" . $paragraphe . "</h2>\n");
                                $retours = 2;
                            } elseif ($retours == 2) {
                                fwrite($copie, "<h1>" . $paragraphe . "</h1>\n");
                            } else {
                                $paragraphe = preg_replace("/\[(.+)\]\((.+)\)/", '<a href="\2">\1</a>', $paragraphe);
                                fwrite($copie, "<p>" . $paragraphe . "</p>\n");
                            }
                        }
                    }
                } elseif ($donnees[$i] == "TABLEAU") {
                    $tableau = file("DATA/tableau_" . $region[1] . ".DATA");
                    foreach($tableau as $prod) {
                        $prod = rtrim($prod);
                        $prod = explode(" ", $prod);
                        unset($prod[0]);
                        $ligneTab = "<tr>\n";
                        foreach($prod as $case) {
                            $ligneTab .= "<td";
                            if ($case[-1] == "%") {
                                if ($case[0] == "-") {
                                    $ligneTab .= " class='baisse'";
                                } else {
                                    $ligneTab .= " class='hausse'";
                                }
                            }
                            $ligneTab .= ">" . $case . "</td>\n";
                        }
                        $ligneTab .= "</tr>\n";
                        fwrite($copie, $ligneTab);
                    }
                } elseif ($donnees[$i] == "COMM") {
                    $meilleurs = file("DATA/comm_" . $region[1] . ".DATA");
                    foreach($meilleurs as $vendeur) {
                        $vendeur = rtrim($vendeur);
                        $vendeur = explode(",", $vendeur);
                        fwrite($copie, "<figure>\n<img src='../images/1" . $vendeur[0] . ".png' alt='vendeur1'>\n<figcaption>\n<p>" . $vendeur[1] . "</p>\n<p>CA réalisé : " . $vendeur[2] . "</p>\n</figcaption>\n</figure>");
                    }
                } else {
                    fwrite($copie, $donnees[$i] . "\n");
                }
                $i++;
            } else {
                fwrite($copie, $ligne);
            }
        }
    }
    // fclose($fichier);
    // fclose($conf);
    fclose($copie);
?>