<?php
require('include/fpdf/fpdf.php');
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$siret=$_SESSION['siret'];
$billId=$_SESSION['idBill'];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Image('asset/largeLogo.png', 10,10,65,15);

function convertUTF($str){
  return iconv('UTF-8', 'windows-1252', $str);
}

$queryBill=$bdd->prepare("SELECT * FROM facture WHERE entreprise = ? AND id = ?");
$queryBill->execute([$siret,$billId]);
$bill=$queryBill->fetch();

$pdf->Text(140,15,convertUTF('Facture payée le : '.date('d/m/Y', strtotime($bill['date']))));
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

$queryCompany=$bdd->prepare("SELECT client.nom,client.prenom,client.email,client.adresse,client.ville,client.codePostal,client.numPhone FROM entreprise INNER JOIN client ON entreprise.client = client.id WHERE entreprise.numSiret = ?");
$queryCompany->execute([$siret]);
$company=$queryCompany->fetch();

$ycoordinate += 12;
$xcoordinate = 130;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($company['nom'].' '.$company['prenom']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($company['adresse']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($company['codePostal'].' '.$company['ville']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($company['numPhone']));
$ycoordinate += 6;
$pdf->Text($xcoordinate,$ycoordinate,convertUTF($company['email']));

$pdf->SetLineWidth(1.5);
$pdf->SetDrawColor(222,194,65);
$pdf->Line(0,105,210,105);

$pdf->SetY(110);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(40,10,convertUTF('Référence'),0,0,'C');
$pdf->Cell(70,10,convertUTF('Mode de livraison'),0,0,'C');
$pdf->Cell(40,10,convertUTF('Poids'),0,0,'C');
$pdf->Cell(30,10,convertUTF('Prix'),0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$queryParcel = $bdd->prepare("SELECT * FROM colis WHERE facture = ? ORDER BY prix");
$queryParcel->execute([$billId]);
while($parcel=$queryParcel->fetch()){
  $pdf->Cell(50,10,$parcel['refQrcode'],0,0,'C');
  $pdf->Cell(60,10,$parcel['modeLivraison'],0,0,'C');
  $pdf->Cell(50,10,$parcel['poids'].' kg',0,0,'C');
  $pdf->Cell(20,10,convertUTF($parcel['prix'].' €'),0,0,'C');
  $pdf->Ln();
  if(($pdf->GetY())>280){
    $pdf->AddPage();
  }
}

if(($pdf->GetY())>210){
  $pdf->AddPage();
}else{
  $ycoordinate =$pdf->GetY();
  $pdf->Line(0,$ycoordinate+5,210,$ycoordinate+5);
}
$ycoordinate =$pdf->GetY();


$pdf->SetY($ycoordinate+10);
$pdf->SetX(135);
$pdf->Cell(50,10,convertUTF('Montant total : '.$bill['montant'].' €'));
$pdf->Ln();
$pdf->SetX(135);
$pdf->Cell(50,10,convertUTF('Nombre total de colis : '.$bill['nbColis'].' colis'));

$pdf->Image('asset/signatureUP.png', 40,$ycoordinate+30,50,50);

$pdf->Output();
?>
