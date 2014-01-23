<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();

    // Include the main TCPDF library (search for installation path).
   require_once('tcpdf/tcpdf.php');
   require_once('tcpdf/examples/tcpdf_include.php');
   
   
   // Extend the TCPDF class to create custom Header and Footer
   class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.PDF_HEADER_LOGO;
        $this->Image($image_file, 10, 10, 190, 25, 'PNG', '', 'T', true, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Add new line
        $this->Ln('', false);
        // Title
        $this->Cell(0, 25, 'Sistema Vernier', 0, 1, 'C', false, '', 0, false, 'T', 'C');
    }

    // Page footer
    public function Footer() {
        // Position at 30 mm from bottom
        $this->SetY(-30);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        $image_cebolla = K_PATH_IMAGES.'cebolla_70_45.jpg';
        $this->Image($image_cebolla, 10, '', 0, 0, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
        $htmlFooter ='
                        <div class="span11" style="font-size: 12px; line-weight:100%;">
                            <a href="http://www.usb.ve/home/node/68">e-virtual</a> | <a href="https://webmail.usb.ve/" target="_blank">Correo</a> |<a href="http://www.usb.ve/buscador.php" target="_blank"> </a><a href="https://esopo.usb.ve">esopo</a> |<a href="http://www.usb.ve/buscador.php" target="_blank"> </a><a href="http://www.youtube.com/canalusb" target="_blank">canalUSB</a> | <a href="http://www.usb.ve/agenda.php" target="_blank">Agenda Cultural</a> | <a href="http://usbnoticias.info/" target="_blank">USBnoticias</a> |<span> </span><span> </span><a href="http://www.usb.ve/home/node/55">Calendario</a>
                            <br>Sede Sartenejas, Baruta, Edo. Miranda - Apartado 89000 - Cable Unibolivar - Caracas Venezuela. Tel&eacute;fono +58 0212-9063111
                            <br>Sede Litoral, Camur&iacute; Grande, Edo. Vargas Parroquia Naiguat&aacute;. Tel&eacute;fono +58 0212-9069000
                        </div>';
        $this->writeHTMLCell(0, 0, '', '', $htmlFooter, 0, 1, 0, true, '', true);
        // Page number
        $this->Cell(0, 10, 'Pág. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
   }

   // create new PDF document
   $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

   // set document information
   $pdf->SetCreator(PDF_CREATOR);
   $pdf->SetAuthor(PDF_AUTHOR);
   $pdf->SetTitle('Encuesta Nombre');
   $pdf->SetSubject('TCPDF Tutorial');
   $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

   // set default header data
   $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
   $pdf->setFooterData(array(0,64,0), array(0,64,128));

   // set header and footer fonts
   $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
   $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

   // set default monospaced font
   $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

   // set margins
   $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

   // set auto page breaks
   $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

   // set image scale factor
   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

   // ---------------------------------------------------------

   // set default font subsetting mode
   $pdf->setFontSubsetting(true);

   // Set font
   // dejavusans is a UTF-8 Unicode font, if you only need to
   // print standard ASCII chars, you can use core fonts like
   // helvetica or times to reduce file size.
   $pdf->SetFont('dejavusans', '', 14, '', true);

   // set text shadow effect
   $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

   // ---------------------------------------------------------------------
   
   if (isset($_GET['id_encuesta'])){
   
      //Obtención de la familia de cargos asociada a la encuesta
      $sql ="SELECT nombre ";
      $sql.="FROM FAMILIA_CARGO WHERE id='".$_GET['id_fam']."'";
      $atts = array("nombre");
      $FAMILIA_CARGO_EVALUADO= obtenerDatos($sql, $conexion, $atts, "Fam");
      
      //Lista de preguntas
      $sql ="SELECT id_encuesta, id_encuesta_ls, id_pregunta, id_pregunta_ls, titulo, peso, seccion ";
      $sql.="FROM PREGUNTA WHERE id_encuesta='".$_GET['id_encuesta']."' AND id_pregunta_root_ls IS NULL";        
      $atts = array("id_encuesta", "id_encuesta_ls", "id_pregunta", "id_pregunta_ls", "titulo", "peso", "seccion");
      
      $LISTA_PREGUNTAS= obtenerDatos($sql, $conexion, $atts, "Preg");
      
      for($i=0; $i<$LISTA_PREGUNTAS[max_res]; $i++){
         //Lista de subpreguntas de la pregunta correspondiente
         $sql ="SELECT id_encuesta, id_encuesta_ls, id_pregunta, id_pregunta_ls, titulo, peso, seccion ";
         $sql.="FROM PREGUNTA WHERE id_encuesta='".$_GET['id_encuesta']."' AND id_pregunta_root_ls='".$LISTA_PREGUNTAS['Preg']['id_pregunta_ls'][$i]."'";        
         $atts = array("id_encuesta", "id_encuesta_ls", "id_pregunta", "id_pregunta_ls", "titulo", "peso", "seccion");
         
         $LISTA_SUBPREGUNTAS= obtenerDatos($sql, $conexion, $atts, "Preg");
         
         // Add a page
         // This method has several options, check the source code documentation for more information.
         $pdf->AddPage();
         
         $pdf->Ln(10, false);
         $pdf->SetFont('helvetica', 'BI', 12);
         $texto_encuesta="ENCUESTA PARA ".$FAMILIA_CARGO_EVALUADO['Fam']['nombre'][0];
         $pdf->Cell(10, '', $texto_encuesta, 0, 1, '');
         
         $pdf->SetFont('helvetica', '', 12);
         
         if(strcmp($LISTA_PREGUNTAS['Preg']['seccion'][$i],"competencia")==0){
            $texto_seccion="Sección de Competencias";
         }else{
            $texto_seccion="Sección de Factores";
         }
         $pdf->Cell(10, '', $texto_seccion, 0, 1, '');
         
         $pdf->Ln(5, false);        
         $htmlPregunta =$LISTA_PREGUNTAS['Preg']['titulo'][$i];
         $pdf->writeHTML($htmlPregunta, true, false, true, true, '');
         $pdf->Ln(5, false);
         
         $pdf->SetFont('helvetica', '', 10);         
         
         if($LISTA_SUBPREGUNTAS[max_res]>0){
            $tbl ='
            <table border="1" cellpadding="2" cellspacing="2" align="center">
               <tr nobr="true">
                  <th colspan="5"></th>
               </tr>
               <tr nobr="true">
                  <td></td>
                  <td>Nunca</td>
                  <td>Pocas veces</td>
                  <td>Casi siempre</td>
                  <td>Siempre</td>
               </tr>';
         
            for($j=0; $j<$LISTA_SUBPREGUNTAS[max_res]; $j++){
               $htmlSubPregunta =$LISTA_SUBPREGUNTAS['Preg']['titulo'][$j];
               
               $tbl.='
                  <tr nobr="true">
                     <td>'.$LISTA_SUBPREGUNTAS['Preg']['titulo'][$j].'</td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>';
            }
            
            $tbl.='</table>';
            
            $pdf->writeHTML($tbl, true, false, false, true, '');
         
         }
      }
      
      
      
   }
   

   
   
   //----------------------------------------------------------------------

   // cleaning the buffer before Output()
   ob_clean();

   // Close and output PDF document
   // This method has several options, check the source code documentation for more information.
   $pdf->Output('prueba.pdf', 'I');
   //============================================================+
   // END OF FILE
   //============================================================+
php?>