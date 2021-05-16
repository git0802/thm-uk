<?php

namespace App\Jobs;

use App\Helpers\ImageHelper;
use App\Product;
use Exception;
use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use GuzzleHttp;

/**
 * Upload Images job.
 *
 * @package App\Jobs
 */
class ProcessImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImageHelper;

    /**
     * Product instance.
     *
     * @var Product
     */
    protected $product;

    /**
     * Image url.
     *
     * @var string
     */
    protected $url;

    /**
     * Create a new job instance.
     *
     * @param Product $product
     * @param string $url
     */
    public function __construct($product, $url)
    {
        $this->product = $product;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

            $client = new GuzzleHttp\Client(['verify' => false]);

            $res = $client->request('GET', $this->url, [
                'headers' => [
                    'User-Agent' => $agent,
                ]
            ]);

            $mime = $res->getHeaderLine('Content-Type');

            if ($mime === "application/octet-stream") {
                $extension = $this->getImageMimeType( (string) $res->getBody());
            } else {
                $extension = explode("/", $mime)[1];
            }

//            Log::info($extension);
            if (in_array($extension, ['jpeg', 'jpg', 'gif', 'png', 'webp'])) {
                $filename = Str::random(40) . "." . $extension;
                Storage::put("images/products/" . $filename, $res->getBody());
                $this->product->image()->create([
                    'path' => "images/products/" . $filename
                ]);
            } else {
                Log::info("Wrong extension: " . $extension);
                throw new Exception('Wrong exception.');
            }
        } catch (Exception $exception) {
            Log::info("Fails to upload image: " . $exception->getMessage() . PHP_EOL . "URL: " . $this->url);
//            $this->product->delete();
        }
    }


    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::info("Fails to upload image: " . $exception->getMessage());
//        $this->product->delete();
    }

}
