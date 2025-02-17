<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
class ExcelController extends Controller
{

    public function export(Request $request)
    {
        $date = $request->tahun.'-'.$request->bulan;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $pengajuan = new PengajuanModel();
        $data = $pengajuan->pengajuan()
            ->where('pengajuan_surats.status', '=', 'Selesai')
            ->where('pengajuan_surats.created_at', 'like', '%'.$date.'%')
            ->get();


        $sheet->setCellValue('A1', 'Nomor Surat');
        $sheet->setCellValue('B1', 'Tempat, Tgl Lahir');
        $sheet->setCellValue('C1', 'Jenis Kelamin');
        $sheet->setCellValue('D1', 'Kebangsaan');
        $sheet->setCellValue('E1', 'Agama');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Pekerjaan');
        $sheet->setCellValue('H1', 'NIK');
        $sheet->setCellValue('I1', 'Alamat');
        $sheet->setCellValue('J1', 'RT');
        $sheet->setCellValue('K1', 'RW');
        $sheet->setCellValue('L1', 'Tgl Surat');
        $sheet->setCellValue('M1', 'Keperluan');
        $row = 2;
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E8217'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Mengatur style pada seluruh data
        $sheet->getStyle('A2:M2'.$row)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Mengatur style pada kolom tertentu (misalnya kolom A dan H)
        $sheet->getStyle('A2:A'.$row)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
        $sheet->getStyle('H2:H'.$row)->getNumberFormat()->setFormatCode('0000000000000000');

        // Mengatur lebar kolom otomatis
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);

        foreach ($data as $item) {
            $sheet->setCellValue('A'.$row, $item->nomor_surat);
            $sheet->setCellValue('B'.$row, $item->tempat_lahir.', '.$item->tgl_lahir);
            $sheet->setCellValue('C'.$row, $item->jenis_kelamin);
            $sheet->setCellValue('D'.$row, $item->kewarganegaraan);
            $sheet->setCellValue('E'.$row, $item->agama);
            $sheet->setCellValue('F'.$row, $item->status_perkawinan);
            $sheet->setCellValue('G'.$row, $item->pekerjaan);
            $sheet->setCellValueExplicit('H'.$row, $item->nik, DataType::TYPE_STRING);
            $sheet->setCellValue('I'.$row, $item->alamat);
            $sheet->setCellValue('J'.$row, $item->rt);
            $sheet->setCellValue('K'.$row, $item->rw);
            $sheet->setCellValue('L'.$row, $item->created_at->format('d-m-Y'));
            $sheet->setCellValue('M'.$row, $item->keterangan);
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'Rekapan Pengajuan Bulan_'.$request->bulan.'_'.$request->tahun.'.xlsx';
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

}
