<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\KeyVal;
use App\Ttl;
use Illuminate\Http\Request;
use App\Helper\FilterKey;
use DB;

class KeyValueController extends Controller
{
    public function getValues(Request $request)
    {
        /*$e = "SELECT
                    key_vals.`key`,
                    key_vals.`value`
                FROM
                    key_vals
                WHERE TIMESTAMPDIFF(MINUTE,
                        key_vals.`last_store_time`,
                        now()) < (SELECT ttl from ttls);";*/

        $keys = $request->input('keys');

        $ttl = Ttl::select('ttl')->first();
        $results = KeyVal::select('key', 'value')
            ->where(DB::raw('TIMESTAMPDIFF(MINUTE, key_vals.`last_store_time`, now())'), '<=', $ttl->ttl);

        if($keys){
            $keys = explode(',', $keys);
            $results = $results->whereIn('key', $keys);
        }

        // get result
        $key_values = $results->get();

        // reset ttl
        $results->update([
                'last_store_time' => date("Y-m-d H:i:s")
            ]);

        // delete key values
        KeyVal::where(DB::raw('TIMESTAMPDIFF(MINUTE, key_vals.`last_store_time`, now())'), '>', $ttl->ttl)
            ->update([
                'deleted_at' => date("Y-m-d H:i:s")
            ]);

        return response()->json([
            'is_success' => true,
            'key_values' => $key_values
        ], 200);
    }

    public function addValues(Request $request)
    {
        //check content type application/json or not
        if($request->isJson()) {
            //check valid json format or not
            if(empty($request->json()->all())) {
                return response()->json([
                    'is_success' => false,
                    'error' => "Please add valid json data"
                ], 400);
            }


            // validation successfull

            foreach ($request->all() as $key => $value)
            {
                // check key already exists or not. if exist continue
                if(KeyVal::where('key', $key)->first())
                    continue;

                KeyVal::create([
                   'key'                => $key,
                   'value'              => $value,
                   'last_store_time'    => date("Y-m-d H:i:s")
                ]);
            }

            return response()->json([
                'is_success'    => true,
                'message'       => "Operation completed successfully"
            ], 201);
        }

        return response()->json([
            'is_success' => false,
            'error' => "content type application/json is needed"
        ], 400);
    }


    public function updateValues(Request $request)
    {
        //check content type application/json or not
        if($request->isJson()) {
            //check valid json format or not
            if(empty($request->json()->all())) {
                return response()->json([
                    'is_success' => false,
                    'error' => "Please add valid json data"
                ], 400);
            }


            // validation successfull
            foreach ($request->all() as $key => $value)
            {
                // check matching key
                $ttl = Ttl::select('ttl')->first();
                $row = KeyVal::where('key', $key)
                    ->where(DB::raw('TIMESTAMPDIFF(MINUTE, key_vals.`last_store_time`, now())'), '<=', $ttl->ttl)
                    ->first();
                if($row){
                    $row->update([
                        'value' => $value,
                        'last_store_time' => date("Y-m-d H:i:s")
                    ]);
                }
            }

            return response()->json([
                'is_success'    => true,
                'message'       => "Operation completed successfully"
            ], 200);
        }

        return response()->json([
            'is_success' => false,
            'error' => "content type application/json is needed"
        ], 400);
    }
}
