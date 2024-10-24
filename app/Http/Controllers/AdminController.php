<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $outlet = Outlet::all();
        $selectedOutletId = $request->query('outlet_id', $outlet->first()->id ?? null);

        // Cek apakah ada data outlet
        if ($outlet->isNotEmpty()) {
            // Ambil outlet_id dari outlet pertama
            $outlet_id = $outlet->first()->id;

            // Redirect ke URL dengan parameter outlet_id jika ada outlet
            return redirect()->route('history', ['outlet_id' => $outlet_id]);
        } else {
            $apiOutlet = $this->getOutletData();
            return view('stok.outlet', compact('outlet', 'selectedOutletId', 'apiOutlet'));
        }
    }

    public function getOutletData()
    {
        $apiToken = '92|BN2EvdcWabONwrvbSIbFgSZyPoEoFwjsRwse7li6';
        $apiUrl = 'https://pos.lakesidefnb.group/api/outlet'; // Menyesuaikan URL API

        $client = new Client();

        try {
            $response = $client->request('GET', $apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['data']) && is_array($responseData['data'])) {
                return $responseData['data'];
            } else {
                return [];
            }
        } catch (\Exception $e) {
            // Log::error('API Request Error: ' . $e->getMessage());
            return [];
        }
    }
}