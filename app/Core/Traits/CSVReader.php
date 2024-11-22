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
            if (function_exists('mb_convert_encoding')) {
                $file_content = file_get_contents($filepath);
                $file_content = mb_convert_encoding($file_content, 'UTF-8', 'auto');
                $handle = fopen('php://memory', 'r+');
                fwrite($handle, $file_content);
                fseek($handle, 0);
            }

            $headers = fgetcsv($handle, 0, ',', '"', '\\');
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