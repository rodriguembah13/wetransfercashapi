<?php


namespace App\Utils\Fpdf;


use App\Repository\ConfigurationRepository;
use App\Repository\TransactionRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ListingServicepdf
{
    private $pdf;
    private $configurationRepository;
    private $transactionRepository;
    private $params;
    /**
     * ListingServicepdf constructor.
     * @param $configurationRepository
     * @param $transactionRepository
     */
    public function __construct(ParameterBagInterface $parameterBag,ConfigurationRepository $configurationRepository, TransactionRepository $transactionRepository)
    {
        $this->configurationRepository = $configurationRepository;
        $this->transactionRepository = $transactionRepository;
        $this->params=$parameterBag;
        $this->pdf = new MFpdf();
    }


    function header()
    {
        $configuration = $this->configurationRepository->findOneByLast();
        $this->pdf->SetFont('Times', '', 10);
        $logo=strstr($configuration->getLogo(), 'uploads/');
        $path =$logo;
        $this->pdf->Image($path, 90, 10,25,25);
        $this->pdf->SetXY(30, 10);
        $this->pdf->Cell(20, 6, 'REPUBLIQUE DU CAMEROUN', 0, 0, 'C');
        $this->pdf->SetXY(30, 15);
        $this->pdf->Cell(20, 6, 'PAIX-TRAVAIL-PATRIE', 0, 0, 'C');
        $this->pdf->SetXY(30, 20);
        $this->pdf->Cell(20, 6, 'MINISTERE DES ENSEIGNEMENTS', 0, 0, 'C');
        $this->pdf->SetXY(30, 25);
        $this->pdf->Cell(10, 6, 'SECONDAIRES', 0, 0, 'C');

        $this->pdf->SetXY(160, 10);
        $this->pdf->Cell(10, 6, 'REPUBLIC OF CAMEROON', 0, 0, 'C');
        $this->pdf->SetXY(160, 15);
        $this->pdf->Cell(10, 6, 'PEACE-WORK-FATHERLAND', 0, 0, 'C');
        $this->pdf->SetXY(160, 20);
        $this->pdf->Cell(10, 6, 'MINISTRY OF SECONDARY', 0, 0, 'C');
        $this->pdf->SetXY(160, 25);
        $this->pdf->Cell(10, 6, 'EDUCATION', 0, 0, 'C');
        $this->pdf->SetFont('Times', 'B', 12);
        $this->pdf->SetY(35);
        $this->pdf->Cell(200, 6, strtoupper(utf8_decode($configuration->getName())), 0, 0, 'C');
        $this->pdf->SetY(40);
        $this->pdf->Cell(200, 6, strtoupper('Tel:' . $configuration->getPhone() . 'Bp: ' . $configuration->getBp()), 0, 0, 'C');
        $this->pdf->Ln(2);

    }

    function headerCarteL()
    {
        $configuration = $this->configurationRepository->findOneByLast();
        $this->pdf->SetFont('Times', '', 8);
        $path = "logo.png";
        $this->pdf->Image($path, 10, 5,null,20);
        $this->pdf->SetXY(30, 5);

        $this->pdf->SetXY(220, 5);
        $this->pdf->SetFont('Times', 'B', 18);
        $this->pdf->Cell(20, 10, utf8_decode('REÇU'), 0, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->SetXY(220, $this->pdf->GetY());
        $this->pdf->SetFont('Times', 'B', 16);
        $this->pdf->Cell(20, 10, utf8_decode('date'), 0, 0, 'C');
        $this->pdf->Ln();

    }
    function bodyRecupaiement($data)
    {
        $configuration = $this->configurationRepository->findOneByLast();
        $this->pdf->SetFont('Times', '', 8);
        $path = "logo.png";
        $this->pdf->Image($path, 10, 20,null,20);
        $this->pdf->SetXY(220, 20);
        $this->pdf->SetFont('Times', 'B', 18);
        $this->pdf->Cell(20, 10, utf8_decode('REÇU '.$data['numero']), 0, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->SetXY(220, $this->pdf->GetY());
        $this->pdf->SetFont('Times', 'B', 16);
        $this->pdf->Cell(20, 10, utf8_decode($data['datecreation']), 0, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Line(3, $this->pdf->GetY() + 5, 292, $this->pdf->GetY() + 5);
        $configuration = $this->configurationRepository->findOneByLast();
        $this->pdf->SetFont('Times', 'B', 14);
        $y1=$this->pdf->GetY();
        $this->pdf->SetXY(10, $this->pdf->GetY() + 5);
        $this->pdf->Cell(40, 10, utf8_decode('Expéditeur : '.$data['expediteur']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetFont('Times', '', 12);
        $this->pdf->Cell(40, 5, utf8_decode($data['exp_adresse']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->Cell(40, 5, utf8_decode('Téléphone : '.$data['exp_phone']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->Cell(40, 5, utf8_decode('Pièce d\'identité: Carte National d\'Identité :'.$data['exp_idcard']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->Cell(40, 5, utf8_decode('Pays:'.$data['exp_pays']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetFont('Times', 'B', 14);
        $this->pdf->SetXY(120, $y1 + 5);
        $this->pdf->MultiCell(80, 6, utf8_decode('Bénéficiaire :'.$data['beneficiare']), 0, 'J');
        $this->pdf->Ln();
        $this->pdf->SetXY(120, $this->pdf->GetY());
        $this->pdf->SetFont('Times', '', 12);
        $this->pdf->Cell(40, 5, utf8_decode('Pays:'.$data['b_pays']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetXY(120, $this->pdf->GetY()+1);
        $this->pdf->Cell(40, 5, utf8_decode('Téléphone :'.$data['b_phone']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetXY(120, $this->pdf->GetY()+1);
        $this->pdf->Cell(40, 5, utf8_decode('Code Swift :'.$data['b_swift']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetXY(120, $this->pdf->GetY()+1);
        $this->pdf->Cell(40, 5, utf8_decode('Code IBAN :'.$data['b_iban']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetXY(220, $y1 + 5);
        $this->pdf->SetFont('Times', 'B', 14);
        $this->pdf->Cell(40, 10, utf8_decode('Détail du transfert'), 0, 'J');
        $this->pdf->Ln();
        $this->pdf->SetFont('Times', '', 12);
        $this->pdf->SetXY(220, $this->pdf->GetY());
        $this->pdf->Cell(40, 5, utf8_decode('Agent :'.$data['agent']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetXY(220, $this->pdf->GetY());
        $this->pdf->Cell(40, 5, utf8_decode('Type transfert :'.$data['typetransaction']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetXY(220, $this->pdf->GetY());
        $this->pdf->Cell(40, 5, utf8_decode('Wallet : '.$data['wallet']), 0, 0, 'L');
        $this->pdf->Ln();
        $this->pdf->SetY($this->pdf->GetY()+10);
        $this->pdf->SetFont('Times', 'B', 12);
        $this->pdf->Cell(200, 10, utf8_decode('Description'), 1, 0, 'L');
        $this->pdf->Cell(80, 10, utf8_decode('Montant'), 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->SetFont('Times', '', 12);
        $this->pdf->Cell(200, 10, utf8_decode('Montant envoyé'), 1, 0, 'L');
        $this->pdf->Cell(80, 10, $data['montantsend'], 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Cell(200, 10, utf8_decode('Frais du tranfert'), 1, 0, 'L');
        $this->pdf->Cell(80, 10, $data['frais'], 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Cell(200, 10, utf8_decode('Taux d\' échange'), 1, 0, 'L');
        $this->pdf->Cell(80, 10, $data['taux'], 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Cell(200, 10, utf8_decode('Montant perçu par le bénéficiaire'), 1, 0, 'L');
        $this->pdf->Cell(80, 10, $data['montantpercu'], 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->SetFont('Times', 'B', 12);
        $this->pdf->Cell(200, 10, utf8_decode('Subtotal'), 0, 0, 'R');
        $this->pdf->Cell(80, 10, $data['subtotal'], 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Cell(200, 10, utf8_decode('Taxes'), 0, 0, 'R');
        $this->pdf->Cell(80, 10, $data['taxes'], 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Cell(200, 10, utf8_decode('Total'), 0, 0, 'R');
        $this->pdf->Cell(80, 10, $data['total'], 1, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->SetY($this->pdf->GetY()+10);
        $this->pdf->SetFont('Times', 'B', 14);
        $this->pdf->Cell(80, 10, "Signature du client", 0, 0, 'C');
        $this->pdf->Cell(180, 10, "Signature caisse", 0, 0, 'R');
        $this->pdf->RoundedRect(3, 3, 290, 203, 3.5);
    }
    public function initRecuScolarite($rows)
    {
        $this->pdf->AddPage('L', 'A4', 0);

        $this->pdf->AliasNbPages();
        $this->bodyRecupaiement($rows);
        $dir = "uploads";
        if (!is_dir($dir)) {
            mkdir($dir, 0700);
        }
        $dir = $dir . "/recu";
        if (!is_dir($dir)) {
            mkdir($dir, 0700);
        }
        $this->pdf->Output('F', $dir . '/recutransaction.pdf');
    }
}
