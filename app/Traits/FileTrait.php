<?php

/**
 * @author Douglas Vicentini Ferreira
 * @email dvferreira@bartofil.com.br
 * @Agency Bartofil Distribuidora SA
 * @link https://www.bartofil.com.br
 * @date 11/03/2024
 */
declare(strict_types = 1);

namespace App\Traits;

trait FileTrait
{
    protected function exportToExcel(array $data, string $fileName): string
    {
        $fileName .= '.xlsx';

        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag    = false;
        $content = '';
        foreach ($data as $row) {
            if (!$flag) {
                $content .= implode("\t", array_keys($row)) . "\n";
                $flag    = true;
            }
            array_walk($row, 'self::filterData');
            $content .= implode("\t", array_values($row)) . "\n";
        }
        return $content;
    }

    private function filterData(&$str): void
    {
        $str = preg_replace("/\t/", "\\t", (string)$str);
        $str = preg_replace("/\r?\n/", "\\n", (string)$str);
        if (str_contains($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }
}