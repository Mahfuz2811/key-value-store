<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\KeyVal;
use App\Ttl;
use Illuminate\Http\Request;
use Validator;

class TtlController extends Controller
{

    public function getTtl()
    {
        $ttl = Ttl::first();

        if($ttl)
        {
            return response()->json([
                'is_success'    => true,
                'ttl'           => $ttl->ttl
            ], 201);
        }

        return response()->json([
            'is_success'    => true,
            'ttl'           => ''
        ], 400);

    }


    public function updateTtl(Request $request)
    {
        $data = $request->all();

        if($request->isJson()) {
            //check valid json format or not
            if(empty($request->json()->all())) {
                return response()->json([
                    'is_success' => false,
                    'error' => "Please add valid json data"
                ], 400);
            }


            // validation successfull

            $validator = Validator::make($data, [
                'ttl' => 'required|integer|min:5|max:250'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 200);
            }

            $ttl = Ttl::first();
            if($ttl)
            {
                $ttl->update([
                   'ttl' => $data['ttl']
                ]);

                return response()->json([
                    'is_success'    => true,
                    'message'       => "Operation completed successfully"
                ], 201);
            }

            return response()->json([
                'is_success'    => false,
                'message'       => "Model not found"
            ], 400);
        }

        return response()->json([
            'is_success' => false,
            'error' => "content type application/json is needed"
        ], 400);
    }
}
