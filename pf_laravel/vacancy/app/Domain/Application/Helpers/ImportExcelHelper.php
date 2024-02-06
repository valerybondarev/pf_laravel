<?php


namespace App\Domain\Application\Helpers;

use PhpOffice\PhpSpreadsheet\Reader\IReader;

/**
 * Class ImportExcelHelper
 * @package app\helpers
 * @property IReader $reader
 * @property string $filename
 */
class ImportExcelHelper
{
    public $reader;
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filename);
    }

    public function getListWorkSheets()
    {
        return $this->reader->listWorksheetInfo($this->filename);
    }

    public function getSpreadsheet($sheetName)
    {
        $this->reader->setLoadSheetsOnly($sheetName);
        $spreadsheet = $this->reader->load( $this->filename);
        return $spreadsheet->getActiveSheet()->toArray();
    }

    /**
     * @param $names
     * @param array $workSheetNames
     * @param bool $firstLineReturn Возвращать ли первую строку первого листа
     * @return array
     */
    public function getColumnsByNames($names, array $workSheetNames = [], bool $firstLineReturn = true)
    {
        $worksheetData = $this->getListWorkSheets();
        $result = [];
        foreach ($worksheetData as $indexSheet => $worksheet) {
            if (count($workSheetNames) == 0 || array_search($worksheet['worksheetName'], $workSheetNames) !== false) {
                $columnIndexes = $this->getColumnIndexesBySheetName($names, $worksheet['worksheetName']);
                $this->getColumnsInSheetByIndexes($result, $columnIndexes, $worksheet['worksheetName'], (($indexSheet == 0 && $firstLineReturn)));
            }
        }
        return $result;
    }

    public function getColumnIndexesBySheetName($names, $sheetName)
    {
        $table = $this->getSpreadsheet($sheetName);
        $indexes = [];
        if (isset($table[0]) && count($table[0]) > 0) {
            foreach ($table as $row) {
                foreach ($row as $indexCol => $col) {
                    if (array_search($col, $names) !== false) {
                        $indexes[] = $indexCol;
                    }
                }
            }
        }
        return $indexes;
    }

    public function getColumnsInSheetByIndexes(&$result, $indexes, $sheetName, $firstLineReturn = true)
    {
        $table = $this->getSpreadsheet($sheetName);
        $lastRow = count($result);
        foreach ($table as $rowIndex => $row) {
            if ($firstLineReturn || $rowIndex != 0) {
                foreach ($row as $indexCol => $col) {
                    if (in_array($indexCol, $indexes)) {
                        $result[$lastRow][] = $col;
                    }
                }
                if ($rowIndex + 1 != count($table)) {
                    $lastRow++;
                }
            }
        }
    }
}
