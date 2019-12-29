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
    public function getValues()
    {
        /*$e = "SELECT
                    key_vals.`key`,
                    key_vals.`value`
                FROM
                    key_vals
                WHERE TIMESTAMPDIFF(MINUTE,
                        key_vals.`last_store_time`,
                        now()) < (SELECT ttl from ttls);";*/

        $ttl = Ttl::select('ttl')->first();
        // get result
        $results = KeyVal::select('key', 'value')
            ->where(DB::raw('TIMESTAMPDIFF(MINUTE, key_vals.`last_store_time`, now())'), '<=', $ttl->ttl)->get();

        // reset ttl
        KeyVal::where(DB::raw('TIMESTAMPDIFF(MINUTE, key_vals.`last_store_time`, now())'), '<=', $ttl->ttl)
            ->update([
                'last_store_time' => date("Y-m-d H:i:s")
            ]);

        // delete key values
        KeyVal::where(DB::raw('TIMESTAMPDIFF(MINUTE, key_vals.`last_store_time`, now())'), '>', $ttl->ttl)
            ->update([
                'deleted_at' => date("Y-m-d H:i:s")
            ]);

        return response()->json([
            'is_success' => true,
            'key_values' => $results
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
            ], 200);
        }

        return response()->json([
            'is_success' => false,
            'error' => "content type application/json is needed"
        ], 400);
    }
}
