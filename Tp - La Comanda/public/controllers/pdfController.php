<?php
require_once __DIR__ . '/Archivo.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/HortalizaController.php';

// use App\Models\Hortaliza;
use Fpdf\Fpdf;

class pdfController{

    public function DescargaHortalizas($request, $response, $args)
    {
        $lista = Hortaliza::all();
        if (count($lista) > 0) {
            FileManager::guardarJson($lista, './archivos/hortalizas.csv');
            $payload = json_encode(array("mensaje" => "archivo guardado /archivos/hortalizas.csv"));
        } else {
            $payload = json_encode(array("mensaje" => "No hubo movimientos"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function DescargaPDF($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        // $id = $parametros['id'];

        // $dato = Hortaliza::where('id', '=', $idHortaliza)->first();//aca hay q buscar obj x id trae un bool
        $lista = Venta::obtenerTodos();
        if(count($lista)> 0){
            $fecha = new datetime("now");
            $pdf = new FPDF();
            $pdf->AddPage();

            $pdf->SetFont('Helvetica', 'B', 25);
            $pdf->Cell(160, 15, 'Segundo Parcial Programacion', 1, 3, 'L');
            $pdf->Ln(3);

            $pdf->SetFont('Helvetica', '', 15);
            $pdf->Cell(60, 4, 'Nombre: Vizgarra Livon Daniel', 0, 1, 'L');
            $pdf->Cell(20, 0, '', 'T');
            $pdf->Ln(3);
            
            $pdf->Cell(60, 4, 'Listado: VENTAS', 0, 1, 'L');
            $pdf->Cell(15, 0, '', 'T');
            $pdf->Ln(5);

            // $hortaliza = Hortaliza::where('id', '=', $idHortaliza)->get();//este trae el obj
            $header = array('ID', 'CANTIDAD', 'ID PRODUCTO', 'ID USUARIO', 'FECHA');
            $pdf->SetFillColor(255, 0, 0);
            $pdf->SetTextColor(255);
            $pdf->SetDrawColor(128, 0, 0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('Helvetica', 'B', 8);
            $w = array(20, 30, 30, 60, 40);
            for ($i = 0; $i < count($header); $i++) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }           
            $pdf->Ln();
            // Restauración de colores y fuentes
            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            // Datos
            $fill = false;

            // Cabecera
            foreach ($lista as $obj) {
                $pdf->Cell($w[0], 6, $obj->id, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 6, $obj->cantidad, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 6, $obj->idProducto, 'LR', 0, 'C', $fill);
                // $pdf->Cell($w[3], 6, $obj->foto, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 6, $obj->idUsuario, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 6, $obj->fecha, 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');

            $pdf->Output('F', './archivosPDF/ListadoVentas' .$fecha->format("Y-m-d").'.pdf', 'I');
            $payload = json_encode(array("mensaje" => 'archivo generado ./archivosPDF/ListadoVentas' . $fecha->format("Y-m-d") .'.pdf'));
        } else {
            $payload = json_encode(array("error" => 'Producto no encontrado'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    static function FancyTable($header, $data)
    {
        // Colores, ancho de línea y fuente en negrita
        $pdf = new FPDF('P', 'mm', array(80, 150));
        $pdf->AddPage();

        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128, 0, 0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Helvetica', 'B');
        // Cabecera
        $w = array(40, 35, 45, 40);
        for ($i = 0; $i < count($header); $i++)
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $pdf->Ln();
        // Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Datos
        $fill = false;
        foreach ($data as $obj) {
            $pdf->Cell($w[0], 6, $obj[0], 'LR', 0, 'L', $fill);
            $pdf->Cell($w[1], 6, $obj[1], 'LR', 0, 'L', $fill);
            $pdf->Cell($w[2], 6, number_format($obj[2]), 'LR', 0, 'R', $fill);
            $pdf->Cell($w[3], 6, number_format($obj[3]), 'LR', 0, 'R', $fill);
            $pdf->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }
}
