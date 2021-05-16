<?php


namespace App\Helpers;


use App\Http\Requests\Store\FillStore;
use App\Jobs\ProcessImages;
use App\Product;
use App\Store;
use Illuminate\Support\Facades\Log;
use League\Csv\AbstractCsv;
use League\Csv\Exception;
use League\Csv\Reader;
use function League\Csv\delimiter_detect;

trait StoreHelper
{
    /**
     * Check product values.
     *
     * @param array $product
     * @param array $columns
     * @param Store $store
     * @return void
     */
    private function check($product, $columns, $store)
    {
        $result = [];
        $result['name'] = mb_substr(trim($product[$columns['name']]), 0, 127);
        $result['serving_size'] = mb_substr(trim($product[$columns['serving_size']]), 0, 127);
        $result['package_size'] = mb_substr(trim($product[$columns['package_size']]), 0, 127);
        $result['original_url'] = trim($product[$columns['original_url']]);
        $result['cost'] = (float)preg_replace("/[^-0-9.]/", "", $product[$columns['cost']]);
        $result['calories'] = (int)$product[$columns['calories']];
        $result['image'] = $product[$columns['image']];
        // Fix for negative values
        if ($result['calories'] < 0) {
            $result['calories'] *= -1;
        }
        if ($result['cost'] < 0) {
            $result['cost'] *= -1;
        }
        // Check
        $check = $result['name'] !== ''
        && $result['serving_size'] !== ''
//        && $result['image'] !== ''
        && $result['cost'] >= 0
        && $result['cost'] <= 100
        && $result['calories'] >= 0
            ? $result
            : false;
        // Update or create
        if ($check) {
            $this->processStore($result, $store);
        }
    }

    /**
     * Save or update product.
     *
     * @param array $product
     * @param Store $store
     * @return void
     */
    private function processStore($product, $store): void
    {
        try {
            if ($oldProduct = Product::where('original_url', $product['original_url'])->first()) {
                $oldProduct->update($product);
                $oldProduct->save();
            } else {
                $newProduct = $store->products()->create($product);
                if(isset($product['image'])) {
                    ProcessImages::dispatch($newProduct, $product['image']);
                }
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Set progress status for the store.
     *
     * @param Store $store
     * @param bool $value
     * @return void
     */
    private function inProgress($store, $value): void
    {
        $store->in_progress = $value;
        $store->save();
    }

    /**
     * Get columns id.
     *
     * @param array|AbstractCsv|Reader $row
     * @param FillStore $request
     * @return array
     */
    private function findColumns($row, $request): array
    {
        $errors = [];
        $needles = $request->only([
            'name', 'serving_size', 'package_size', 'image', 'cost', 'calories', 'original_url', 'package_size'
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

    /**
     * Convert CSV to array.
     *
     * @param FillStore $request
     * @return AbstractCsv|Reader
     * @throws Exception
     */
    private function getCSV($request)
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
}
