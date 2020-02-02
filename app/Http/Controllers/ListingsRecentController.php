<?php
/**
 * File: ListingsRecentController.php
 * Date: 10/16/19
 * Time: 10:10 AM
 *
 * @package rummagecity.dev
 * @author Michael Dahlke <mdahlke@wisnet.com>
 */

namespace App\Http\Controllers;

use App\Http\Middleware\Cors;
use App\Http\Services\MapboxService;
use App\Listing;
use App\ListingDate;
use App\ListingImage;
use App\MapboxFeature;
use App\Notifications\ListingCreated;
use App\Providers\MapboxProvider;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ListingsRecentController extends Controller {

    public function index(Request $request, $location = null) {
        /** @var Builder $listings */
        $builder = Listing::query()
            ->with(['activeDate' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->with('image')
            ->whereHas('activeDate')
            ->limit(4);

        $listings = $builder->get();

        return response()->json([
            'listings' => $listings,
        ]);
    }

}
