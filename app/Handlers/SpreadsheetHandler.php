<?php
namespace App\Handlers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetHandler
{
    private $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    public function outputExcelInvoice($invoice)
    {
        $this->generateExcelInvoice($invoice);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Invoice_' . $invoice->invoice_no . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function generateExcelInvoice($invoice)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', strtoupper($invoice->company_name));
        $sheet->setCellValue('A2', "Mail Address: $invoice->company_mail_addr $invoice->company_mail_postal");
        $sheet->setCellValue('B3', "CELL: $invoice->company_contact_tel $invoice->company_contact_fname");
        // Todo: Add TEL a cell from CELL
        $sheet->setCellValue('B4', "TOLL FREE: $invoice->company_tollfree");
        // Todo: Add FAX a cell from TOLL FREE

        //return $this->spreadsheet;
    }

    public function __destruct()
    {
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
    }
}
