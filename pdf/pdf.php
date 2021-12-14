<?php
require('fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $width = ($this->w / 2) - 15;
    $this->Image('images/logo.jpg',$width,25,30);
    
    // Line break
    $this->Ln(50);
}

function contenu($idVisiteur, $nomVisiteur, $leMois, $lesFraisHorsForfait, $lesFraisForfait, $montantValide)
{
    $moisEcrit = dateAnneeMoisVersMoisAnneeEcrit($leMois);
    $moisTotal = dateAnglaisVersFrançaisMoisAnnee($leMois);
    
    $font = 'Times';
    $marge = 10;
    
    $this->SetLineWidth(0.2);
            
    //Titre
    $this->SetFont($font,'B',13.5);
    $this->SetTextColor(25,65,115);
    $this->Cell(0,6,"REMBOURSEMENT DE FRAIS ENGAGES",1,1,'C');
    $this->SetFont($font, '', 11);
    
    // texte couleur noir
    $this->SetTextColor(0,0,0);
    //Ligne Visiteur
    $this->Cell($marge, 20, '', 'L');
    $this->Cell(50,20,"Visiteur");
    $this->Cell(50,20,$idVisiteur);
    $this->Cell(0,20,$nomVisiteur, 'R');
    
    $this->Ln(15);
    
    //Ligne Mois
    $this->Cell($marge, 5);
    $this->Cell(50,5,"Mois");
    // Valeur Mois
    $this->Cell(50,5, utf8_decode($moisEcrit));
    
    $this->ln(5);
    
    $this->Cell(0, 10, '', 'LR');
    
    $this->ln(10);
    
    //Tableau frais forfaitaires
    $largeurLigne = $this->w - 40;
    $largeurColonne = $largeurLigne / 4;
    
    $this->Cell($marge, 0);
    
    $this->SetDrawColor(25,65,115);
    
    //Ligne du haut du tableau
    $this->Cell($largeurLigne, 0, '', 'B');
    
    $this->SetFont($font, 'BI', 11);
    $this->SetTextColor(25,65,115);
    
    $this->Ln(0);
    $this->SetDrawColor(0, 0, 0);
    
    $this->Cell($marge, 10, '', 'L');
    $this->SetDrawColor(25,65,115);
    // Noms des colonnes du tableau frais forfaitaires
    $this->Cell($largeurColonne + 10, 10,"Frais Forfaitaires","L",0, 'C');
    $this->Cell($largeurColonne, 10,utf8_decode("Quantité"),0,0,'C');
    $this->Cell($largeurColonne, 10,("Montant unitaire"),"LTRB",0,'C');
    $this->Cell($largeurColonne - 10, 10,("Total"),"R",0,'C');
    $this->SetDrawColor(0, 0, 0);
    $this->Cell(0, 10, '', 'R');
    
    $this->Ln(10);
    
    $this->Cell($marge, 0);
        
    $this->SetDrawColor(25,65,115);

    $this->Cell($largeurLigne, 0, '', 'T');

    $this->Ln(0);

    $this->SetFont($font, '', 11);
    
    $this->SetTextColor(0, 0, 0);
    
    foreach ($lesFraisForfait as $fraisForfait) {
            $libelle = iconv("UTF-8", "CP1252//TRANSLIT", $fraisForfait['libelle']);
            $quantite = $fraisForfait['quantite'];
            $montant = $fraisForfait['montant'];
            $total = ((int)$quantite * (float)$montant);

            //Couleur des traits en noir
            $this->SetDrawColor(0, 0, 0);

            //Marge
            $this->Cell(10, 10, '', 'L');

            //Couleur des traits en bleu
            $this->SetDrawColor(25,65,115);

            $this->Cell($largeurColonne + 10, 10, $libelle, 'L');
            $this->Cell($largeurColonne, 10, $quantite, 'L', 0, 'R');
            $this->Cell($largeurColonne, 10, $montant, 'L', 0, 'R');
            $this->Cell($largeurColonne - 10, 10, $total, 'LR', 0, 'R');

            //Couleur des traits en noir
            $this->SetDrawColor(0, 0, 0);
            $this->Cell(0, 10, '', 'R');

            $this->Ln(10);

            //Couleur des traits en bleu
            $this->SetDrawColor(25,65,115);

            $this->Cell($marge, 0);

            $this->Cell($largeurLigne, 0, '', 'T');

            $this->Ln(0);
        }
    
    $this->ln(0);
    
    $this->SetDrawColor(0, 0, 0);
    
    $this->Cell($marge, 10, '', 'L');
    $this->SetDrawColor(25,65,115);
    
    $this->Cell($largeurLigne, 10, '', 'LR');
    
    $this->SetDrawColor(0, 0, 0);
    
    $this->Cell(0, 10, '', 'R');
    
    $this->Ln(10);
    
    $this->SetFont($font, 'BI', 11);
    
    $this->SetTextColor(25,65,115);
    
    $this->SetDrawColor(0, 0, 0);
    
    $this->Cell($marge, 10, '', 'L');
    
    $this->SetDrawColor(25,65,115);
    
    $this->Cell($largeurLigne, 10, 'Autres frais','LR', 0, 'C');
    
    $this->SetDrawColor(0, 0, 0);
    
    $this->Cell(0, 10, '', 'R');

    $this->Ln(10);

    //Tableau des frais hors forfaits

    $this->Cell($marge, 0);

    $this->SetDrawColor(25,65,115);

    //Ligne Haut Tableau
    $this->Cell($largeurLigne, 0, '', 'B');

    $this->SetFont($font, 'BI', 11);

    $this->SetTextColor(25, 65, 115);

    $this->Ln(0);

    $largeurColonne = $largeurLigne / 3;

    $this->SetDrawColor(0, 0, 0);

    $this->Cell($marge, 10, '', 'L');
    
    $this->SetDrawColor(25, 65, 115);
    // Noms des colonnes du tableau frais forfaitaires
    $this->Cell($largeurColonne, 10,"Date","L",0, 'C');
    $this->Cell($largeurColonne + 10, 10,utf8_decode("Libellé"),0,0,'C');
    $this->Cell($largeurColonne - 10, 10,("Montant"),"R",0,'C');
    
    $this->SetDrawColor(0, 0, 0);
    $this->Cell(0, 10, '', 'R');
    
    $this->Ln(10);
    
    $this->Cell($marge, 0);

    $this->SetDrawColor(25, 65, 115);

    $this->Cell($largeurLigne, 0, '', 'T');

    $this->Ln(0);

    $this->SetFont($font, '', 11);

    $this->SetTextColor(0, 0, 0);
    
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = iconv("UTF-8", "CP1252//TRANSLIT", $unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant'];

            //Couleur des traits en noir
            $this->SetDrawColor(0, 0, 0);
            //Marge
            $this->Cell($marge, 10, '', 'L');
            //Couleur des traits en bleu
            $this->SetDrawColor(25,65,115);
            $this->Cell($largeurColonne, 10, $date, 'L');
            $this->Cell($largeurColonne + 10, 10, $libelle, 'L');
            $this->Cell($largeurColonne - 10, 10, $montant, 'LR', 0, 'R');
            //Couleur des traits en noir
            $this->SetDrawColor(0, 0, 0);
            $this->Cell(0, 10, '', 'R');

            $this->Ln(10);

            $this->Cell($marge, 0);

            //Couleur des traits en bleu
            $this->SetDrawColor(25,65,115);

            $this->Cell($largeurLigne, 0, '', 'T');

            $this->Ln(0);
        }
        
    $this->ln(0);
    $this->SetDrawColor(0, 0, 0);
    $this->Cell(0, 10, '', 'LR');

    $this->Ln(10);
    //Petit tableau total
    $largeurColonne = $largeurLigne / 4;
    
    $this->Cell(20 + $largeurColonne * 2);
    
    $this->SetDrawColor(25,65,115);
    $this->Cell(($largeurColonne * 2) - 10, 0, '', 'B');
    
    $this->ln(0);

    $this->SetDrawColor(0, 0, 0);
    $this->Cell(20 + $largeurColonne * 2, 10, '', 'L');
    
    $this->SetDrawColor(25,65,115);
    $this->Cell($largeurColonne, 10,utf8_decode('TOTAL' . $moisTotal),'L');
    $this->Cell($largeurColonne - 10,10,$montantValide,"LR",0,'R');
    
    $this->SetDrawColor(0, 0, 0);
    $this->Cell(0, 10, '', 'R');
    
    $this->ln(10);
    
    $this->SetDrawColor(0, 0, 0);
    $this->Cell(0, 10, '', 'LR');

    //Passage à la ligne
    $this->Ln(0);
    
    $this->Cell(20 + $largeurColonne * 2);
    
    $this->SetDrawColor(25,65,115);
    $this->Cell(($largeurColonne * 2) - 10, 0, '', 'T');

    $this->Ln(10);

    //Fermeture du tableau

    $this->SetDrawColor(0, 0, 0);
    $this->Cell(0, 0, '', 'T');

    $this->Ln(20);

    $this->SetFont($font, '', 12);
    
    $this->Cell(20 + $largeurColonne * 2, 5);
    $this->Cell(0, 5, iconv("UTF-8", "CP1252//TRANSLIT", 'Fait à Paris, le ' . dernierJourMois(substr($leMois, 4)) . ' ' . $moisEcrit));
    
    $this->Ln(10);

        //Marge
    $this->Cell(20 + $largeurColonne * 2, 5);
    $this->Cell(0, 5, 'Vu l\'agent comptable');

    $this->Ln(10);

    $this->Cell(20 + $largeurColonne * 2);

    $this->Image('images/signatureComptable.png', 120, 250);
    }
}