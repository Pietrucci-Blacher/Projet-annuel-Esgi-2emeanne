<?php
require('include/fpdf/fpdf.php');
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$idDeliver=$_SESSION['idDeliver'];
$salaryId=$_SESSION['idSalary'];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Image('asset/largeLogo.png', 10,10,65,15);

function convertUTF($str){
  return iconv('UTF-8', 'windows-1252', $str);
}

$querySalary=$bdd->prepare("SELECT * FROM salaire WHERE livreur = ? AND id = ?");
$querySalary->execute([$idDeliver,$salaryId]);
$salary=$querySalary->fetch();

$pdf->Text(140,15,convertUTF('Facture payée le : '.date('d/m/Y', strtotime($salary['date']))));
$pdf->Text(140,21,convertUTF('Facture exportée le : '.date('d/m/Y')));

$ycoordinate = 32;
$xcoordinate = 12;
$pdf->Text($xcoordinate,$ycoordinate,'Ultimate Parcel');
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,'242 Rue du Faubourg Saint-Antoine');
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,'75012 Paris');
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,'(+33) 1 56 06 90 41');
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,'contact@ultimate-parcel.com');

// for ($i=0; $i < 500; $i+=10) {
// $pdf->Text(1,$i,$i);
// }
//
// for ($i=0; $i < 500; $i+=10) {
// $pdf->Text($i,4,$i);
// }

$queryDeliver=$bdd->prepare("SELECT client.nom,client.prenom,client.email,client.adresse,client.ville,client.codePostal,client.numPhone FROM livreur INNER JOIN client ON livreur.client = client.id WHERE livreur.id = ?");
$queryDeliver->execute([$idDeliver]);
$deliver=$queryDeliver->fetch();

$ycoordinate += 12;
$xcoordinate = 130;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($deliver['nom'].' '.$deliver['prenom']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($deliver['adresse']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($deliver['codePostal'].' '.$deliver['ville']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($deliver['numPhone']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($deliver['email']));

$pdf->SetLineWidth(1.5);
$pdf->SetDrawColor(222,194,65);
$pdf->Line(0,105,210,105);

$pdf->SetY(110);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(100,10,convertUTF('Détails du montant'),0,0,'C');
$pdf->Cell(100,10,convertUTF('Montant'),0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','',12);

$pdf->Cell(110,10,convertUTF($salary['nbColis'].' colis délivrés x 1.90 €'),0,0,'C');
$pdf->Cell(90,10,convertUTF($salary['nbColis']*1.90.' €'),0,0,'C');
$pdf->Ln();
$pdf->Cell(110,10,convertUTF($salary['nbKm'].' km parcourus x 0.36 €'),0,0,'C');
$pdf->Cell(90,10,convertUTF($salary['nbKm']*0.36.' €'),0,0,'C');
$pdf->Ln();
$pdf->Cell(110,10,convertUTF('Prime charge lourde'),0,0,'C');
$pdf->Cell(90,10,convertUTF($salary['primePoids'].' €'),0,0,'C');
$pdf->Ln();
$pdf->Cell(110,10,convertUTF('Prime d\'objectif'),0,0,'C');
$pdf->Cell(90,10,convertUTF($salary['primeObjectif'].' €'),0,0,'C');
$pdf->Ln();


$ycoordinate =$pdf->GetY();
$pdf->Line(0,$ycoordinate+5,210,$ycoordinate+5);

$ycoordinate =$pdf->GetY();

$pdf->SetY($ycoordinate+10);
$pdf->SetX(90);
$pdf->Cell(50,10,convertUTF('Montant total viré au destinataire :    '.$salary['montant'].' €'));
$pdf->Ln();

$pdf->Image('asset/signatureUP.png', 40,$ycoordinate+30,50,50);

$pdf->Output();
?>
