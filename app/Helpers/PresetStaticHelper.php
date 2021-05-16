<?php


namespace App\Helpers;


use League\Csv\Reader;
use function League\Csv\delimiter_detect;

class PresetStaticHelper
{
    /**
     * @param $request
     * @return \League\Csv\AbstractCsv|Reader
     * @throws \League\Csv\Exception
     */
    public static function getCSV($request): Reader
    {
        $reader = Reader::createFromPath($request->file('csv')->getPathname(), 'r');
        $delimiters = delimiter_detect($reader, [';', ',', '|'], 1);
        foreach ($delimiters as $key => $delimiter) {
            $delimiter !== 0 ? $reader->setDelimiter($key) : null;
        }
        $reader->setHeaderOffset(0);
        // Convent to UTF-8 if not
        $input_bom = $reader->getInputBOM();
        if ($input_bom === Reader::BOM_UTF16_LE || $input_bom === Reader::BOM_UTF16_BE) {
            $reader->appendStreamFilter('convert.iconv.UTF-16/UTF-8');
        }

        return $reader;
    }

    public static function findColumns($row, $request)
    {
        $errors = [];
        $needles = $request->only([
            'product_name',
            'day',
            'eating',
        ]);

        foreach ($needles as $name => $value) {
            $index = array_search(trim($value), $row->getHeader(), true);
            $index === false
                ? $errors[$name] = ucfirst($name) . ' not found!'
                : $needles[$name] = $value;
        }

        return count($errors)
            ? ['success' => false, 'errors' => $errors]
            : $needles;
    }
}
