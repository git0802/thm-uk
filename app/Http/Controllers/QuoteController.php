<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quotes\CreateQuote;
use App\Http\Requests\Quotes\UpdateQuote;
use App\Quote;
use App\Helpers\UserHelper;
use Exception;
use Illuminate\Http\Response;

/**
 * Quote Controller
 *
 * @package App\Http\Controllers
 */
class QuoteController extends Controller
{
    use UserHelper;

    /**
     * Quote controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get all quotes for admin page.
     *
     * @return Response
     */
    public function index()
    {
        if (!$this->isAdmin()) {
            return response([
                'success' => false,
                'quote' => 'You have no rights to do this!',
            ]);
        }

        return response([
            'success' => true,
            'quote' => Quote::all(),
        ]);
    }

    /**
     * Show random quote.
     *
     * @return Response
     */
    public function show()
    {
        return response([
            'success' => true,
            'quote' => Quote::all()->random()->quote
        ]);
    }

    /**
     * Create new quote.
     *
     * @param CreateQuote $request
     * @return Response
     */
    public function create(CreateQuote $request)
    {
        $quote = Quote::create($request->only(['quote']));

        return response([
            'success' => true,
            'message' => 'Quote created successfully!',
            'quote'   => $quote,
        ]);
    }

    /**
     * Update quote.
     *
     * @param Quote $quote
     * @param UpdateQuote $request
     * @return Response
     */
    public function update(Quote $quote, UpdateQuote $request)
    {
        $quote->update($request->only(['quote']));

        return response([
            'success' => true,
            'message' => 'Quote updated successfully!'
        ]);
    }

    /**
     * Remove quote.
     *
     * @param Quote $quote
     * @return Response
     * @throws Exception
     */
    public function remove(Quote $quote)
    {
        $quote->delete();

        return response([
            'success' => true,
            'message' => 'Quote deleted successfully!'
        ]);
    }
}
