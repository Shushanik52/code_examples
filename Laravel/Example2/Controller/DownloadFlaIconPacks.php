<?php


namespace App\Http\Controllers;

use App\Jobs\GetFlatIcons;
use App\Pack;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackController extends Controller
{
    private $response;
    private $client;

    public function download(Request $request)
    {
        if (!$request->has('packId') || !is_numeric($request->input('packId'))) {
            return response()->json(['success' => false, 'message' => 'something went wrong']);
        }

        $token = json_decode($this->response->getBody())->data->token;

        if (!$token) {
            return response()->json(['success' => 'something went wrong']);
        }

        $ifExistPack = Pack::where('id', $request->input('packId'))->first();

        if ($ifExistPack) {
            return response()->json(['success' => 'already exist']);
        }

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];

        $pack = [];
        $getPacks = $client->request('GET', 'https://api.flaticon.com/v3/item/pack/' . $request->input('packId'), [
            'headers' => $headers,
        ]);


        $pack['id'] = json_decode($getPacks->getBody())->data->id;
        $pack['pack_name'] = json_decode($getPacks->getBody())->data->description;
        $pack['pack_style_name'] = json_decode($getPacks->getBody())->data->style_name;
        $pack['pack_items'] = json_decode($getPacks->getBody())->data->pack_items;
        $pack['tags'] = json_decode($getPacks->getBody())->data->tags;
        $pack['cover_image'] = json_decode($getPacks->getBody())->data->images->sprite;


        if (Pack::insert($pack)) {
            $this->dispatch(new GetFlatIcons($token, $request->input('packId'), $pack));
            return response()->json(['success' => 'downloading is in process']);
        }
    }

}