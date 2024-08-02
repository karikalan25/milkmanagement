<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Breed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDO;

class RegisterUser extends Controller
{
    public function register(Request $request)
    {
        // Split the supply values into an array
        $supply = array_map('trim', explode(',', $request->input('supply'))); // Trim whitespace
        $litres = array_map('trim', explode(',',$request->input('litres')));
        $minimum_price=array_map('trim','explode'(',',$request->input('minimum_price')));
        $maximum_price=array_map('trim',explode(',',$request->input('maximum_price')));
        // dd($minimum_price);

        // Base validation rules
        $rules = [
            'name' => 'required|string',
            'role' => 'required|in:1,2',
            'gender' => 'required|in:1,2',
            'dob' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'supply'=>'required|string',
            'minimum_price'=>'required|string',
            'maximum_price'=>'required|string',
            'payload'=>'required',
            'password' => 'required|string',
        ];

        // Additional validation rules based on role and supply values
        if ($request->role == 1) { // Farmer

            unset($rules['payload']);

            if (in_array('1', $supply) && in_array('2', $supply)) {
                // Both cow and buffalo
                // dd(1);
                if (count($supply) > 2) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'enter only cow and buffalo.'], 422);
                }
                if (count($litres) < 2 && count($minimum_price) < 2 && count($maximum_price) < 2) {

                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'litres, minimum price, and maximum price fields should have values for both cow and buffalo.'], 422);
                }
                if (count($litres) < 2 ) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'litres fields should have values for both cow and buffalo.'], 422);
                }
                if (count($minimum_price) < 2) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum Price fields should have values for both cow and buffalo.'], 422);
                }
                if (count($maximum_price) < 2) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'maximum price fields should have values for both cow and buffalo.'], 422);
                }
                elseif(!in_array('1', $supply) && !in_array('2', $supply)){
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'supply values contain cow or buffalo.'], 422);
                }
                $rules['litres'] = 'required|string';
                $rules['minimum_price'] = 'required|string';
                $rules['maximum_price'] = 'required|string';
            }
            elseif (in_array('1', $supply)) {
                // Only cow
                // dd(1);
                if (count($supply) > 1) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'buffalo values required .'], 422);
                }
                if (count($litres) < 1 && count($minimum_price) < 1 && count($maximum_price) < 1) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'litres, minimum price, and maximum price fields should have values for cow.'], 422);
                }
                if (empty($litres[0])) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'litres fields are required for cow.'], 422);
                }
                if(empty($minimum_price[0]) ){
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum price fields are required for cow.'], 422);
                }
                if(empty($maximum_price[0]) ){
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'maximum price fields are required for cow.'], 422);
                }
                if (count($litres) > 1 || count($minimum_price) > 1 || count($maximum_price) > 1) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'buffalo supply required.'], 422);
                }

                $rules['litres'] = 'required|string';
                $rules['minimum_price'] = 'required|string';
                $rules['maximum_price'] = 'required|string';
            }
            elseif (in_array('2', $supply)) {
                // Only buffalo
                if (count($litres) < 1 && count($minimum_price) < 1 && count($maximum_price) < 1) {
                    return response()->json(['message' => 'litres, minimum Price, and maximum price fields should have values for buffalo.'], 422);
                }
                if (empty($litres[0])) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'litres fields are required for buffalo.'], 422);
                }
                if (empty($minimum_price[0])) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum price fields are required for buffalo.'], 422);
                }
                if (empty($maximum_price[0])) {
                    return response()->json(['result'=>'0', 'data'=>[], 'message' => 'maximum price fields are required for buffalo.'], 422);
                }
                if (count($litres) > 1 || count($minimum_price) > 1 || count($maximum_price) > 1) {

                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'buffalo fields only required.'], 422);
                }
                $rules['litres'] = 'required|string';
                $rules['minimum_price'] = 'required|string';
                $rules['maximum_price'] = 'required|string';
            }
            elseif(!in_array('1',$supply)){
                return response()->json(['result'=>'0', 'data'=>[],'message'=>'supply field must contain for cow']);
            }
            elseif(!in_array('2',$supply)){
                // dd(1);
                return response()->json(['result'=>'0', 'data'=>[],'message'=>'supply field must contain for buffalo']);
            }
        }
        if ($request->role == 2) { // Milkman
            $rules['payload']='required|in:1,2,3';

            if (in_array('1', $supply) && in_array('2', $supply)) {
                // Both cow and buffalo
                if (count($minimum_price) < 2 && count($maximum_price) < 2) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum price, and maximum price fields should have values for both cow and buffalo.'], 422);
                }
                if (count($minimum_price) < 2) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum Price fields should have values for both cow and buffalo.'], 422);
                }
                if (count($maximum_price) < 2) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'maximum Price fields should have values for both cow and buffalo.'], 422);
                }
                unset($rules['litres']);
                $rules['minimum_price'] = 'required|string';
                $rules['maximum_price'] = 'required|string';
            } elseif (in_array('1', $supply)) {
                // Only cow
                // dd(1);
                if (count($minimum_price) < 1 && count($maximum_price) < 1) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum Price, and maximum Price fields should have values for cow.'], 422);
                }
                if(empty($minimum_price[0]) ){
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum price fields are required for cow.'], 422);
                }
                if(empty($maximum_price[0]) ){
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'maximum price fields are required for cow.'], 422);
                }
                unset($rules['litres']);
                $rules['minimum_price'] = 'required|string';
                $rules['maximum_price'] = 'required|string';
            }
            elseif (in_array('2', $supply)) {
                // Only buffalo
                if (count($minimum_price) < 1 && count($maximum_price) < 1) {
                    return response()->json(['message' => 'minimum price, and maximum price fields should have values for buffalo.'], 422);
                }
                if (empty($minimum_price[0])) {
                    return response()->json(['result'=>'0', 'data'=>[],'message' => 'minimum price fields are required for buffalo.'], 422);
                }
                if (empty($maximum_price[0])) {
                    return response()->json(['result'=>'0', 'data'=>[], 'message' => 'maximum price fields are required for buffalo.'], 422);
                }
                unset($rules['litres']);
                $rules['minimum_price'] = 'required|string';
                $rules['maximum_price'] = 'required|string';
            }
            elseif(!in_array('1',$supply)){
                return response()->json(['result'=>'0', 'data'=>[],'message'=>'supply field must contain for cow']);
            }
            elseif(!in_array('2',$supply)){
                // dd(1);
                return response()->json(['result'=>'0', 'data'=>[],'message'=>'supply field must contain for buffalo']);
            }

        }


        // Validate the request
        $validator = Validator::make($request->all(), $rules);
        // dd($litres);

        $validator->after(function ($validator) use ($minimum_price, $maximum_price) {
            foreach ($minimum_price as $index => $minPrice) {
                if (isset($maximum_price[$index]) && $minPrice > $maximum_price[$index]) {
                    $validator->errors()->add('minimum_price', 'minimum price should not be greater than maximum price ' . $index);
                    $validator->errors()->add('maximum_price', 'maximum price should not be less than minimum price ' . $index);
                }
            }
        });

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                $errors[$field] = $messages[0];
            }
            return response()->json(['result' => '0', 'data' => [], 'message' => str_replace(",","|",implode(",",$validator->errors()->all()))], 422);
        }

        // Process the data
        $data = $request->all();
        if ($request->role == 1) {
            unset($data['payload']);
        }
        if ($request->role == 2) {
            unset($data['litres']);
        }

        //Process Role
        if($data['role'] == 1){
            $data['role']='Farmer';
        }elseif($data['role']==2){
            $data['role']='Milkman';
        }

        //Process Gender
        if($data['gender'] == 1){
            $data['gender']='Male';
        }elseif($data['gender']==2){
            $data['gender']='Female';
        }

        //Process Supply
        $breed=[];
        foreach ($supply as $supplies) {
            if ($supplies == 1) {
                $breed[] = 'Cow';
            } elseif ($supplies == 2) {
                $breed[] = 'Buffalo';
            }
        }
        $data['supply']=implode(',',$breed);



        // Payload
        if ($data['role'] == 'Milkman') {
            $payloadMapping = [
                1 => 'Weekly',
                2 => '15 Days',
                3 => 'Monthly',
            ];
            $data['payload'] = $payloadMapping[$data['payload']] ?? '';
        } else if($data['role'] =='Farmer') {
            $data['payload'] = '';
        }

        // Convert dob to timestamp if necessary
        if (!is_numeric($data['dob'])) {
            $timestamp = strtotime($data['dob']);
            $data['dob'] = (string)($timestamp * 1000);
        } else {
            // If already a timestamp, ensure it's in milliseconds
            if (strlen($data['dob']) == 10) {
                $data['dob'] = (string)($data['dob'] * 1000);
            }
        }

        $user=User::create([
            'name'=>$data['name'],
            'role'=>$data['role'],
            'gender'=>$data['gender'],
            'dob'=>$data['dob'],
            'address'=>$data['address'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
            'payload'=>$data['payload'],
            'password'=>Hash::make($data['password']),
        ]);

        foreach ($breed as $index => $breeds) {
            $breedData = [
                'user_id' => $user->id,
                'supply' => $breeds,
                'litres' => $litres[$index] ?? '',
                'minimum_price' => $minimum_price[$index] ?? '',
                'maximum_price' => $maximum_price[$index] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('breeds')->insert($breedData);
        }

        return response()->json(['result'=>'1','data'=>[$data],'message'=>'user registered']);
    }

    public function otp(Request $request){

        // dd($data);
        $validator=Validator::make($request->all(),[
            'phone'=>'required|digits:10'
        ]);
        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=> str_replace(",","|",implode(",",$validator->errors()->all()))]);
        }
        // dd($request->phone);
        $users=User::where('phone',$request->phone)->first();
        // dd($users);
        $otp = rand(1000, 9999);
        // dd($otp);
        if($users){
            $users->expires_at = Carbon::now()->addMinutes(5);
            $users->otp=$otp;
            $users->save();
        }
        $data = User::where('phone',$request->phone)
                ->where('otp',$otp)
                ->first();
        $response=[
            'otp'=>(string)$data['otp'],
        ];

        return response()->json(['result'=>'1','data'=>[$response],'message'=>'otp sent to registered mobile number']);
    }

    public function verification(Request $request){
        $data=$request->all();
        // dd($data);
        $validator=Validator::make($data,[
            'phone'=>'required|digits:10',
            'otp'=>'required|digits:4',
        ]);
        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=> str_replace(",","|",implode(",",$validator->errors()->all()))]);
        }

        $otp=$request->otp;
        $phone=$request->phone;

        $user=User::where('phone',$phone)
            ->where('otp',$otp)
            ->first();
            // dd($user->expires_at);

        if(!$user){
            return response()->json(['result'=>'0','data'=>[],'message'=>'invalid otp']);
        }

        if(Carbon::now()->gt($user->expires_at)){
            return response()->json(['result'=>'0','data'=>[],'message'=>'expired otp']);
        }

            $user->otp='0';
            $user->expires_at=null;
            $user->save();

            return response()->json(['result'=>'1','data'=>[],'message'=>'otp verified successfully']);

    }

    public function changepassword(Request $request){
        $data=$request->all();
        $validator=Validator::make($data,[
            'phone'=>'required|digits:10',
            'password'=>'required|min:3|confirmed',
        ]);
        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=> str_replace(",","|",implode(",",$validator->errors()->all()))]);
        }

        $user=User::where('phone',$request->phone)->first();
        if($user){
            $user->password=Hash::make($request->password);
            $user->expires_at=null;
            $user->save();
            return response()->json(['result'=>'1','data'=>[],'message'=>'password changed']);
        }elseif(!$user){
            return response()->json(['result'=>'0','data'=>[],'message'=>'user not found']);
        }
    }

    public function login(Request $request){
        $data=$request->all();

        $validator=Validator::make($data,[
            'phone'=>'required|digits:10',
            'password'=>'required|min:3'
        ]);

        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=> str_replace(",","|",implode(",",$validator->errors()->all()))]);
        }
        if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){
            $user=Auth::user();
            // dd($user);
            $response=[
                'name'=>$user->name,
                'role'=>$user->role
            ];
            return response()->json(['result'=>'1','data'=>[$response],'message'=>'you are loggedin successfully']);
        }
        else{
            return response()->json(['result'=>'0','data'=>[],'message'=>'invalid credentials']);
        }
    }

    public function user(Request $request){
        $data=$request->all();
        $id=$data['user_id'];

        $user=User::with('breeds')->findOrFail($id);
        // dd($user);
        $breeds=Breed::where('user_id',$id)->get();
        // dd($breeds);
        $supplies = [];
        foreach ($breeds as $breed) {
            $supplies[] = $breed->supply; // Accessing the 'supply' attribute
        }

        $supply = implode(',',$supplies);
        // dd($supply);
        $litre=[];
        foreach($breeds as $breed){
            $litre[]=$breed->litres;
        }
        $litres= implode(',',$litre);
        // dd($litres);

        $minimum_prices=[];
        foreach($breeds as $breed){
            $minimum_prices[]=$breed->minimum_price;
        }
        $minimum_price= implode(',',$minimum_prices);
        // dd($minimum_price);

        $maximum_prices=[];
        foreach($breeds as $breed){
            $maximum_prices[]=$breed->maximum_price;
        }
        $maximum_price= implode(',',$maximum_prices);
        // dd($maximum_price);

        $response=[
            'name'=>$user->name,
            'role'=>$user->role,
            'gender'=>$user->gender,
            'dob'=>$user->dob,
            'address'=>$user->address,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'payload'=>$user->payload,
            'supply'=>$supply,
            'litres'=>$litres,
            'minimum_price'=>$minimum_price,
            'maximum_price'=>$maximum_price
        ];

        return response()->json(['result'=>'1','data'=>[$response],'message'=>'user fetched']);
    }
}
