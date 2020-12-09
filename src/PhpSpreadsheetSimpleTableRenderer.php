<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

use CoreExtensions\ReportsBundle\Exception\ReportRenderingException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Renders report as a simple table with header and body without merged columns.
 * Requires that the result is {@see ReportSimpleTableResultInterface}
 */
class PhpSpreadsheetSimpleTableRenderer implements ReportRendererInterface
{
    /**
     * @var Worksheet
     */
    private $sheet;

    /**
     * @var IWriter
     */
    private $writer;

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    /**
     * @var array
     */
    private $headerStyle;

    private $columnOffset;
    private $rowOffset;

    /**
     * @param IWriter $writer
     * @param Spreadsheet $spreadsheet
     * @param array|null $headerStyle
     * @param int $rowOffset
     * @param int $columnOffset
     */
    public function __construct(
        ?IWriter $writer,
        ?Spreadsheet $spreadsheet,
        ?array $headerStyle = null,
        $rowOffset = 1,
        $columnOffset = 1
    ) {
        $this->writer = $writer;
        $this->spreadsheet = $spreadsheet;
        $this->headerStyle = $headerStyle ?? $this->getDefaultHeaderStyle();
        $this->rowOffset = $rowOffset;
        $this->columnOffset = $columnOffset;
    }

    /**
     * @param ReportInterface $report
     * @return Response
     * @throws ReportRenderingException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function render(ReportInterface $report): Response
    {
        /**
         * @var null|string
         */
        $format = $report->getRendererConfiguration()['format'] ?? null;

        $this->spreadsheet = $this->spreadsheet ?? new Spreadsheet();
        $this->writer = $this->writer ?? $this->buildWriter($format);

        /**
         * @var ReportSimpleTableResultInterface $result
         */
        $result = $report->getData();

        $this->sheet = $this->spreadsheet->getActiveSheet();

        $this->writeHeader($result->getHeaderColumns());
        $this->writeBody($result->getRows());
        $this->setColumnsAutoSize();

        $response = new Response();

        $filename = ($report->getName() ?? 'report').($format ? '.'.$format : '');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        ob_start();
        $this->writer->save('php://output');
        $content = ob_get_clean();

        $response = new Response($content);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    private function buildWriter(?string $format): IWriter
    {
        switch ($format) {
            case 'xlsx':
                $this->writer = new Xlsx($this->spreadsheet);
                break;
            case 'xls':
                $this->writer = new Xls($this->spreadsheet);
                break;
            default:
                throw new ReportRenderingException(sprintf('Wrong rendering format "%s"', $format));
        }
    }

    /**
     * @param array $labels
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function writeHeader(array $labels): void
    {
        $columnIndex = $this->columnOffset;

        foreach ($labels as $label) {
            $this->sheet->setCellValueByColumnAndRow($columnIndex, $this->rowOffset, $label);
            $columnIndex++;
        }

        $highestColumn = $this->sheet->getHighestColumn();
        $this->sheet->getStyle('A1:'.$highestColumn.'1')->applyFromArray($this->headerStyle);

        ++$this->rowOffset;
    }

    protected function writeBody(array $rows): void
    {
        foreach ($rows as $i => $row) {
            $columnIndex = $this->columnOffset;

            foreach ($row as $value) {
                $this->sheet->setCellValueByColumnAndRow($columnIndex, $this->rowOffset, $value);
                $columnIndex++;
            }

            ++$this->rowOffset;
        }
    }

    private function getDefaultHeaderStyle(): array
    {
        return [
            'font' => [
                'bold' => true,
            ],
        ];
    }

    private function setColumnsAutoSize(): void
    {
        foreach ($this->sheet->getColumnIterator() as $column) {
            $column_index = $column->getColumnIndex();

            if (null !== ($dimension = $this->sheet->getColumnDimension($column_index))) {
                $dimension->setAutoSize(true);
            }
        }
    }
}
