<?php


namespace App\Jobs;

use App\Icon;
use App\Pack;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;

class GetFlatIcons implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $token;
    protected $packId;
    protected $pack;

    /**
     * Create a new job instance.
     *
     * @param $token
     * @param $packId
     * @param $pack
     */
    public function __construct($token, $packId, $pack)
    {
        $this->token = $token;
        $this->packId = $packId;
        $this->pack = $pack;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $client = new Client();
            $headers = [
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
            ];
            $iconArray = [];
            $icon = [];
            for ($i = 1; $i <= ceil($this->pack['pack_items'] / 15); $i++) {
                $hash = sha1(json_encode([
                    $this->packId,
                    $this->pack['pack_name'],
                    []
                ]));
                $filename = $hash;
                $img = \Intervention\Image\Facades\Image::make($this->pack['cover_image'])->stream();
                Pack::getStorage()->put('public/thumbs/stickers/' . $this->packId . '/' . $filename . '.png', $img, 'public');
                $coverLink = 'https://' . env('AWS_BUCKET') . '.s3.us-east-2.amazonaws.com/public/thumbs/stickers/' . $this->packId . '/' . $filename . '.png';
                Pack::find($this->packId)->update(['cover_image' => $coverLink]);

                $getPackIcons = $client->request('GET', 'https://api.flaticon.com/v3/items/icons?packId=' . $this->packId . '&limit=15&page=' . $i, [
                    'headers' => $headers,
                ]);

                foreach (json_decode($getPackIcons->getBody())->data as $icons) {
                    $icon['id'] = $icons->id;
                    $icon['pack_id'] = $icons->pack_id;
                    $icon['tags'] = $icons->tags;
                    $icon['png_size_1_link'] = $icons->images->png->{512};

                    $iconS3 = \Intervention\Image\Facades\Image::make($icon['png_size_1_link'])->stream();
                    Icon::getStorage()->put('public/thumbs/stickers/' . $this->packId . '/' . $icon['id'] . '.png', $iconS3, 'public');
                    array_push($iconArray, $icon);
                }
            }

            $ifExistPackId = Icon::where('pack_id', $this->packId)->count();
            if (!$ifExistPackId) {
                foreach ($iconArray as &$icon) {
                    $icon['png_size_1_link'] = 'https://' . env('AWS_BUCKET') . '.s3.us-east-2.amazonaws.com/public/thumbs/stickers/' . $icon['pack_id'] . '/' . $icon['id'] . '.png';
                }
                Icon::insert($iconArray);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}