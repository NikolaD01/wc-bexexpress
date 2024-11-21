<?php

namespace WC_BE\Core\Traits;

trait CSVReader
{
    protected function readCSV(string $filename): array
    {
        $filepath = WC_BE_PLUGIN_DIR . "public/assets/csv/{$filename}";
        if (!file_exists($filepath)) {
            throw new \RuntimeException("CSV file {$filename} not found.");
        }

        $rows = [];
        if (($handle = fopen($filepath, 'r')) !== false) {
            $headers = fgetcsv($handle, 0, ',', '"', '\\'); // Explicit escape parameter
            if (!$headers) {
                throw new \RuntimeException("Unable to read headers from {$filename}.");
            }

            while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                if (count($headers) !== count($data)) {
                    throw new \RuntimeException("Row length mismatch in {$filename}.");
                }
                $rows[] = array_combine($headers, $data);
            }

            fclose($handle);
        } else {
            throw new \RuntimeException("Unable to open CSV file {$filename}.");
        }

        return $rows;
    }
}