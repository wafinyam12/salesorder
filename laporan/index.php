<?php

    //require_once(dirname(__FILE__).'/../html2pdf/html2pdf.class.php');

    // get the HTML
	if(@$_GET['page']=='coba') {
	    ob_start();
	    include(dirname(__FILE__).'/isi/coba.php');
	    $content = ob_get_clean();
        $format = new HTML2PDF('P', 'A4', 'en');
        $nama_file = "coba.pdf";
	} else if(@$_GET['page']=='nota_penjualan') {
        ob_start();        
        include(dirname(__FILE__).'/isi/printlap.php');        
    } else if(@$_GET['page']=='nota_quotation') {
        ob_start();
        include(dirname(__FILE__).'/isi/printquotation.php');        
    } else if(@$_GET['page']=='relasi_map') {
        ob_start();        
        include(dirname(__FILE__).'/isi/relasimap.php');        
    } else if(@$_GET['page']=='export_excell') {
        ob_start();        
        include(dirname(__FILE__).'/isi/exportexcell.php');        
    } else if(@$_GET['page']=='export_excellall') {
        ob_start();        
        include(dirname(__FILE__).'/isi/exportexcellall.php');        
    }   

    // convert to PDF
    try
    {
        //$html2pdf = $format;
        //$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        //$html2pdf->Output($nama_file);
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
