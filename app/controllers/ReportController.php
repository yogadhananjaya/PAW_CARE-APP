<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;

class ReportController {
    // Fungsi umum untuk generate PDF
    public function generatePdf($view_path, $data, $filename) {
        $dompdf = new Dompdf();
        
        // Mengaktifkan buffer untuk menangkap output HTML dari file view
        ob_start();
        extract($data);
        include __DIR__ . '/../../views/Reports/' . $view_path;
        $html = ob_get_clean();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename . ".pdf", ["Attachment" => 0]); // 0 agar buka di browser
    }

    public function laporanDonasi() {
        require_once __DIR__ . '/../models/DonasiModel.php';
        $m = new DonasiModel();
        $this->generatePdf('laporan_donasi.php', ['data' => $m->getAll()], 'Laporan_Donasi_PawCare');
    }
}
?>