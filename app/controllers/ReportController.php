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

    public function laporanDonasiExcel() {
        require_once __DIR__ . '/../models/DonasiModel.php';
        $m = new DonasiModel();
        $data = $m->getAll();

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Laporan_Donasi_PawCare.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th colspan='6' style='font-size:16px; font-weight:bold; text-align:center;'>LAPORAN KEUANGAN & DONASI PAWCARE</th>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>Kode Donasi</th>";
        echo "<th>Nama Donatur</th>";
        echo "<th>Kategori</th>";
        echo "<th>Nominal</th>";
        echo "<th>Tanggal</th>";
        echo "<th>Status</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        if (empty($data)) {
            echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data transaksi donasi.</td></tr>";
        } else {
            foreach ($data as $d) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($d['kode_donasi'] ?? '—') . "</td>";
                echo "<td>" . htmlspecialchars($d['nama_donatur']) . "</td>";
                echo "<td>" . htmlspecialchars($d['kategori']) . "</td>";
                echo "<td>Rp " . number_format($d['nominal'], 0, ',', '.') . "</td>";
                echo "<td>" . htmlspecialchars($d['tanggal']) . "</td>";
                echo "<td>" . htmlspecialchars($d['status_konfirmasi']) . "</td>";
                echo "</tr>";
            }
        }
        echo "</tbody>";
        echo "</table>";
        exit;
    }
}
?>