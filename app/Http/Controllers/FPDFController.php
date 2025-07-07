<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;
use \App\Http\Controllers\LoginController;
use \App\Models\Jornada;
class FPDFController extends Controller
{
    private $fpdf;
    public function __construct()
    {

    }

    public function createPDF(Request $request)
    {
        $info=new LoginController();
        $partits=$info->generateResults($request);
        $jornada=Jornada::where('jornada','=',$request->session()->get('jornada'))->firstOrFail();
        $this->fpdf = new Fpdf('L','mm',array(210,148.5));
        $this->fpdf->SetFont('Arial','B', 18);
        $this->fpdf->AddPage();
        $this->fpdf->Text(10,10,"MAVIAPOSTES - Apostes Jornada: ".$request->session()->get('jornada'));
        $this->fpdf->Ln(5);
        $this->fpdf->SetFont('Arial','B', 8);
        $this->fpdf->Cell(10,10,"Data Inici: ".$jornada->inici,'C');
        $this->fpdf->Ln(5);
        $estat="Oberta";
        if($jornada->tancada==1){
            $estat="Tancada";
        }
        $this->fpdf->Cell(10,10,"Estat: ".$estat,'C');
        $this->fpdf->Ln();
        $this->fpdf->setFillColor(56,158,16);
        $this->fpdf->Cell(40,6,"LOCAL",1,0,'L',1);
        $this->fpdf->Cell(40,6,"VISITANT",1,0,'L',1);
        $this->fpdf->Cell(20,6,"RESULTAT",1,0,'C',1);
        $this->fpdf->Cell(25,6,"VOTS LOCAL",1,0,'C',1);
        $this->fpdf->Cell(25,6,"VOTS EMPAT",1,0,'C',1);
        $this->fpdf->Cell(25,6,"VOTS VISITANT",1,0,'C',1);
        $this->fpdf->Ln(6);
        $this->fpdf->SetFont('');
        $this->fpdf->setFillColor(120,227,77);
        $font=1;
        foreach($partits as $p){
            if($font==0){
                $font=1;
            }else{
                $font=0;
            }
            $this->fpdf->Cell(40,6, $p['local'],1,0,'L',$font);
            $this->fpdf->Cell(40,6, $p['visitant'],1,0,'L',$font);
            $this->fpdf->Cell(20,6, $p['resultat'],1,0,'C',$font);
            $this->fpdf->Cell(25,6, $p['guanya'],1,0,'C',$font);
            $this->fpdf->Cell(25,6, $p['empata'],1,0,'C',$font);
            $this->fpdf->Cell(25,6, $p['perd'],1,0,'C',$font);
            $this->fpdf->Ln();
        }
        $this->fpdf->Output();
        exit;
    }
}
