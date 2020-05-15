<?php
namespace App\Handlers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SpreadsheetHandler
{
    private $spreadsheet;
    private $rowNumbers;
    private $defaultRowHeight = 15.75;
    private $defaultColWidth = 6.3;
    private $defaultMultiplier = 1.4;
    private $colWidths;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->rowNumbers = [
            'first_order_row' => 16
        ];
        foreach(range('A', 'H') as $col){
            $this->colWidths[$col] = $this->defaultColWidth;
        }
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
        // 1. Fill values into appropriate cells
        $this->fillCells($invoice);
        // 2. Set cell styles
        $this->setCellStyles();
    }

    private function fillCells($invoice)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        // Fill invoice info
        $sheet->setCellValue('A12', 'TAX REG. NO.');
        $sheet->setCellValue('C12', 'DATE');
        $sheet->setCellValue('D12', 'SALES REP');
        $sheet->setCellValue('F12', 'PO NO.');
        $sheet->setCellValue('G12', 'TERMS');
        $sheet->setCellValue('H12', 'VIA');
        $sheet->setCellValue('A13', strtoupper($invoice->company_tax_reg));
        $sheet->setCellValue('C13', strtoupper($invoice->createDateFormat()));
        $sheet->setCellValue('D13', ($invoice->sales_rep ? strtoupper($invoice->sales_rep) : ''));
        $sheet->setCellValue('F13', strtoupper($invoice->po_no));
        $sheet->setCellValue('G13', strtoupper($invoice->terms));
        $sheet->setCellValue('H13', strtoupper($invoice->via));
        // Fill orders info (except product name)
        $sheet->setCellValue('A15', "QUANTITY");
        $sheet->setCellValue('C15', "DESCRIPTION");
        $sheet->setCellValue('F15', "PRICE");
        $sheet->setCellValue('G15', "DISCOUNT");
        $sheet->setCellValue('H15', "AMOUNT");
        $first_row_num = $this->rowNumbers['first_order_row'];
        $row_num = $first_row_num;
        foreach($invoice->orders as $order){
            $sheet->setCellValue("A$row_num", $order->quantity);
            //$sheet->setCellValue("B$row_num", strtoupper($order->product));
            $sheet->setCellValue("F$row_num", $order->price);
            $sheet->setCellValue("G$row_num", ($order->discount ? $order->discount : '0.00'));
            $sheet->setCellValue("H$row_num", "=(A$row_num*F$row_num)-G$row_num");
            $row_num++;
        }
        $this->rowNumbers['last_order_row'] = $row_num;
        $small_total_row = $row_num + 1;
        $this->rowNumbers['small_total_row'] = $small_total_row;
        $tax_row = $small_total_row + 1;
        $this->rowNumbers['tax_row'] = $tax_row;
        $freight_row = $tax_row + 1;
        $this->rowNumbers['freight_row'] = $freight_row;
        $total_row = $freight_row + 1;
        $this->rowNumbers['total_row'] = $total_row;
        $sheet->setCellValue("G$small_total_row", "AMOUNT");
        $sheet->setCellValue("H$small_total_row", "=SUM(H$first_row_num:H$row_num)");
        $sheet->setCellValue("G$tax_row", "$invoice->tax_rate% $invoice->tax_description");
        $sheet->setCellValue("H$tax_row", "=H$small_total_row*" . ($invoice->tax_rate / 100));
        $sheet->setCellValue("G$freight_row", "FREIGHT");
        $sheet->setCellValue("H$freight_row", $invoice->freight);
        $sheet->setCellValue("G$total_row", "TOTAL");
        $sheet->setCellValue("H$total_row", "=SUM(H$small_total_row:H$freight_row)");
        // Set column widths
        $this->setAutoSize(range('A', 'H'), true);
        $this->setAutoSize(range('A', 'H'), false);
        // Fill products for invoice orders
        $product_width = $this->colWidths['B'] + $this->colWidths['C'] + $this->colWidths['D'] + $this->colWidths['E'];
        foreach($invoice->orders as $order_idx => $order){
            $product = strtoupper($order->product);
            $rows = $this->getRowsFromVal($product, $product_width);
            $height = $this->defaultRowHeight * $rows;
            $row = $this->rowNumbers['first_order_row'] + $order_idx;
            $sheet->setCellValue("B$row", $product);
            $sheet->getRowDimension($row)->setRowHeight($height);
            $sheet->getStyle("B$row:E$row")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->mergeCells("B$row:E$row");
            $sheet->getStyle("B$row:E$row")->getAlignment()->setWrapText(true);
        }
        // Fill invoice headers
        $sheet->setCellValue('A1', strtoupper($invoice->company_name));
        $sheet->setCellValue('A2', "Mail Address: $invoice->company_mail_addr, $invoice->company_mail_postal");
        $contact_cellnum = $invoice->company_contact_cell ? $invoice->company_contact_cell : $invoice->company_contact_tel;
        $sheet->setCellValue('B3', "CELL: $contact_cellnum " . strtoupper("$invoice->company_contact_fname"));
        $sheet->mergeCells("B3:D3");
        //$sheet->getStyle("B3")->getAlignment()->setWrapText(true);
        $sheet->setCellValue('F3', "TEL: $invoice->company_tel");
        $sheet->mergeCells("F3:H3");
        $sheet->setCellValue('B4', "TOLL FREE: " . ($invoice->company_tollfree ? $invoice->company_tollfree : ''));
        $sheet->mergeCells("B4:D4");
        $sheet->setCellValue('F4', "FAX: " . ($invoice->company_fax ? $invoice->company_fax : ''));
        $sheet->mergeCells('F4:H4');
        // Fill customer info
        $customer_info = [
            'A6' => [
                'val'=>"SOLD TO: ". strtoupper($invoice->bill_name),
                'merge'=>"A6:D6",
                'width'=>$this->colWidths['A']+$this->colWidths['B']+$this->colWidths['C']+$this->colWidths['D']
            ],
            'B8' => [
                'val'=>strtoupper($invoice->bill_addr),
                'merge'=>"B8:D8",
                'width'=>$this->colWidths['B']+$this->colWidths['C']+$this->colWidths['D']
            ],
            'B9' => [
                'val'=>strtoupper($invoice->bill_city) . ', ' . strtoupper($invoice->bill_prov),
                'merge'=>"B9:D9",
                'width'=>$this->colWidths['B']+$this->colWidths['C']+$this->colWidths['D']
            ],
            'B10' => [
                'val'=>strtoupper($invoice->bill_postal),
                'merge'=>"B10:D10",
                'width'=>$this->colWidths['B']+$this->colWidths['C']+$this->colWidths['D']
            ],
            'F6' => [
                'val'=>"SHIP TO: " . strtoupper($invoice->ship_name),
                'merge'=>"F6:H6",
                'width'=>$this->colWidths['F']+$this->colWidths['G']+$this->colWidths['H']
            ],
            'G7' => [
                'val'=>strtoupper($invoice->ship_addr),
                'merge'=>"G7:H7",
                'width'=>$this->colWidths['G']+$this->colWidths['H']
            ],
            'G8' => [
                'val'=>strtoupper($invoice->ship_city) . ', ' . strtoupper($invoice->ship_prov),
                'merge'=>"G8:H8",
                'width'=>$this->colWidths['G']+$this->colWidths['H']
            ],
            'F9' => [
                'val'=>"TEL: " . $invoice->customer_tel . ($invoice->customer_fax ? " FAX: $invoice->customer_fax" : ""),
                'merge'=>"F9:H9",
                'width'=>$this->colWidths['F']+$this->colWidths['G']+$this->colWidths['H']
            ],
            'F10' => [
                'val'=>"CONTACT: " . strtoupper($invoice->customer_contact1) . ($invoice->customer_contact2 ? " OR " . strtoupper($invoice->customer_contact2) : ""),
                'merge'=>"F10:H10",
                'width'=>$this->colWidths['F']+$this->colWidths['G']+$this->colWidths['H']
            ],
        ];
        foreach($customer_info as $result_cell => $info){
            $rows = $this->getRowsFromVal($info['val'], $info['width']);
            $height = $this->defaultRowHeight * $rows;
            $row = $sheet->getCell($result_cell)->getRow();
            $sheet->setCellValue($result_cell, $info['val']);
            $sheet->getRowDimension($row)->setRowHeight($height);
            $sheet->getStyle($info['merge'])->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->mergeCells($info['merge']);
            $sheet->getStyle($info['merge'])->getAlignment()->setWrapText(true);
        }
        // Fill invoice number
        $sheet->setCellValue('A11', "INVOICE #$invoice->invoice_no");
        // Fill invoice footers
        $footer_row = $total_row + 1;
        $this->rowNumbers['footer_start_row'] = $footer_row;
        $sheet->setCellValue("A" . ($footer_row++), "E-mail: $invoice->company_email");
        if($invoice->company_website){
            $sheet->setCellValue("A" . ($footer_row++), "Website: $invoice->company_website");
        }
        $sheet->setCellValue("A" . ($footer_row++), "Warehouse Address: $invoice->company_ware_addr, $invoice->company_ware_postal");
        $this->rowNumbers['footer_end_row'] = $footer_row;
    }

    private function setCellStyles()
    {
        Cell::setValueBinder(new AdvancedValueBinder());
        $sheet = $this->spreadsheet->getActiveSheet();
        // Style currency cells
        $sheet->getStyle("F".$this->rowNumbers['first_order_row'].":H".$this->rowNumbers['last_order_row'])
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        $sheet->getStyle("H".$this->rowNumbers['last_order_row'].":H".$this->rowNumbers['total_row'])
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        // Set font size and bold format for cells
        $sheet->getStyle("A1")->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle("A2")->getFont()->setBold(true);
        $sheet->getStyle("A11")->getFont()->setBold(true)->setSize(20);
        // Set borders to customer info cells
        $sheet->getStyle("A6:D10")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F6:H10")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        // Set borders to invoice info cells
        $sheet->getStyle("A12:H13")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A12:H12")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("C12:C13")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("D12:D13")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F12:F13")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("G12:G13")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("H12:H13")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        // Set borders to order cells
        $sheet->getStyle("A15:H".$this->rowNumbers['total_row'])->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A15:H15")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A".$this->rowNumbers['small_total_row'].":H".$this->rowNumbers['small_total_row'])
            ->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A".$this->rowNumbers['tax_row'].":H".$this->rowNumbers['tax_row'])
            ->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A".$this->rowNumbers['freight_row'].":H".$this->rowNumbers['freight_row'])
            ->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B15:B".$this->rowNumbers['total_row'])->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F15:F".$this->rowNumbers['total_row'])->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("G15:G".$this->rowNumbers['total_row'])->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("H15:H".$this->rowNumbers['total_row'])->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        // Center align company name and invoice number, and merge row cells for company info
        $sheet->mergeCells("A1:H1");
        $sheet->mergeCells("A2:H2");
        $sheet->mergeCells("A11:H11");
        $sheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A11")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function setAutoSize($cols, $setBool)
    {
        foreach($cols as $col){
            $this->spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize($setBool);
        }
        if($setBool){
            $this->spreadsheet->getActiveSheet()->calculateColumnWidths();
            foreach($cols as $col){
                $newWidth = (int)$this->spreadsheet->getActiveSheet()->getColumnDimension($col)->getWidth() * $this->defaultMultiplier;
                $this->colWidths[$col] = ($newWidth > 0) ? $newWidth : $this->defaultColWidth;
                $this->spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth($this->colWidths[$col]);
            }
        }
    }

    private function getWidthFromCellVal($val)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('I1', $val);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->calculateColumnWidths();
        $width = (int)$sheet->getColumnDimension('I')->getWidth() * $this->defaultMultiplier;
        $sheet->getColumnDimension('I')->setAutoSize(false);
        $sheet->removeColumn('I');
        return $width;
    }

    private function getRowsFromVal($val, $fixedWidth)
    {
        return (int)ceil($this->getWidthFromCellVal($val) / $fixedWidth);
    }

    public function __destruct()
    {
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
    }
}
