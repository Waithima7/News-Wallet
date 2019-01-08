<?php

namespace App\Console\Commands;

use App\Events\BidSubmitted;
use App\Models\Core\Bid;
use App\Models\Core\BidButler;
use App\Models\Core\Product;
use App\Repositories\StatusRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MonitorButlers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:butlers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while (1) {
            $this->checkActiveButlers();
            sleep(1);
        }
    }

    function checkActiveButlers()
    {

        $products = Product::where([
                ['products.deadline', '<=', Carbon::now()->addSeconds(3)],
                ['products.status', '=', StatusRepository::getProductStatus('active')],
                ['products.current_price', '>', 0],
            ])->get();
        foreach ($products as $product) {
            $butlers = $product->butlers()->where([
                ['bids','>',0]
            ])->inRandomOrder()->get();
            $this->info("Found: " . $butlers->count().' Butlers');
            foreach($butlers as $butler){
                if($butler->bids>0){
                    $this->bid($product,$butler);
                }
            }
        }
    }

    public function bid($product,$butler){
        $user = $butler->user;
        $deadline = Carbon::createFromTimestamp(strtotime($product->deadline));
//        if ($butler->bids < 1) {
//            $this->info("Insufficient bids for butler#",$butler->id);
//            return false;
//        }
        if ($deadline->isFuture() && $product->bid_enabled == 1 && $product->status == 1) {
            $bid = Bid::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'agent'=>'butler'
            ]);
            $diff = $deadline->diffInSeconds();
            if ($diff < 15) {
                $secs = 15 - $diff;
                $product->deadline = $deadline->addSeconds($secs)->toDateTimeString();
            }
            $product->current_price += 1;
            $product->update();
            $butler->bids -= 1;
            $butler->update();
            event(new BidSubmitted($bid, $user, $product));
            $this->info(time().": Butler#".$butler->id.' Successfully bidded on product#'.$product->id);
            return true;
        }
    }
}
