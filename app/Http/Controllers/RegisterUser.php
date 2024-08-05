<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Breed;
use App\Models\Record;
use App\Models\Supply;
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

    public function record(Request $request){

        $data=$request->all();


        $breed=array_map('trim',explode(',',$data['breed']));
        $morning=array_map('trim',explode(',',$data['morning']));
        $evening=array_map('trim',explode(',',$data['evening']));
        $price=array_map('trim',explode(',',$data['price']));

        $rules=[
            'morning'=>'required|integer',
            'evening'=>'required|integer',
            'price'=>'required|integer'
        ];

        if(in_array('1',$breed) && in_array('2',$breed)){
            if(count($breed)>2 || count($morning)>2 || count($evening)>2 || count($price)>2){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter only cow and buffalo records']);
            }
            if(count($breed)<2 || count($morning)<2 || count($evening)<2 || count($price)<2){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter both cow and buffalo records']);
            }
            elseif(!in_array('1',$breed) && !in_array('2',$breed)){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter both cow and buffalo']);
            }
            $rules['morning']='required|string';
            $rules['evening']='required|string';
            $rules['price']='required|string';
        }
        elseif(in_array('1',$breed)){
            if(count($breed)>1 || count($morning)>1 || count($evening)>1 || count($price)>1){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter only cow records']);
            }
            if(empty($morning[0]) || empty($evening[0]) || empty($price[0])){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter cow records']);
            }
            elseif(!in_array('1',$breed)){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter breed']);
            }
            $rules['morning']='required|string';
            $rules['evening']='required|string';
            $rules['price']='required|string';
        }
        elseif(in_array('2',$breed)){
            if(count($breed)>1 || count($morning)>1 || count($evening)>1 || count($price)>1){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter only buffalo records']);
            }
            if(empty($morning[0]) || empty($evening[0]) || empty($price[0])){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter buffalo records']);
            }
            elseif(!in_array('2',$breed)){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter breed']);
            }
            $rules['morning']='required|string';
            $rules['evening']='required|string';
            $rules['price']='required|string';
        }
        $validator=Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=> str_replace(",","|",implode(",",$validator->errors()->all()))]);
        }

        $user=User::with('breeds')->findOrFail($data['user_id']);
        // dd($user);

             // Access properties of the breed model
        // }
        // $breeds=Breed::where('user_id',$data['user_id'])->first();
        // dd($breed);
        // foreach($breeds as $animal){
        //     $id[]=$animal->id;
        // }
        // dd($breeds->id);

        // dd($morning);
        // dd(count($breed));
        foreach ($breed as $index => $breedType) {
            $breedRecord = $user->breeds->firstWhere('supply', $breedType == '1' ? 'Cow' : 'Buffalo');
            // dd($breedRecord);
            if($breedRecord){
                $today = now()->startOfDay();
                $existingRecord = Record::where('user_id',$data['user_id'])
                                ->where('breed_id',$breedRecord->id)
                                ->whereDate('created_at', $today)
                                ->first();

                if($existingRecord){
                    $days=now()->diffInDays($existingRecord->created_at);

                    if($days>15){
                        return response()->json(['result'=>'0','data'=>[],'message'=>'you can update the records only for 15 days']);
                    }
                    $existingRecord->update([
                        'morning' => $morning[$index] ?? $existingRecord->morning,
                        'evening' => $evening[$index] ?? $existingRecord->evening,
                        'price' => $price[$index] ?? $existingRecord->price
                    ]);
                }
                else{
                    Record::create([
                        'user_id' => $data['user_id'],
                        'breed_id' => $breedRecord->id,
                        'morning' => $morning[$index] ?? '',
                        'evening' => $evening[$index] ?? '',
                        'price' => $price[$index] ?? ''
                    ]);
                }
            }
        }
        return response()->json(['result' => '1', 'data' => [$data], 'message' => 'Records saved']);
    }

    public function notes(Request $request) {
        $data = $request->all();

        $breed = array_map('trim', explode(',', $data['breed']));
        $notes = array_map('trim', explode(',', $data['notes']));

        // Validate input data
        $rules = [
            'user_id' => 'required|integer|exists:users,id',
            'breed' => 'required|string',
            'notes' => 'required|string'
        ];

        if (!in_array('1', $breed) && !in_array('2', $breed)) {
            return response()->json(['result' => '0', 'data' => [], 'message' => 'Enter a valid breed'], 422);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'data' => [], 'message' => str_replace(",", "|", implode(",", $validator->errors()->all()))], 422);
        }

        $user = User::with('breeds', 'records')->where('id', $data['user_id'])->firstOrFail();

        foreach ($breed as $index => $breedType) {
            $supplyType = $breedType == '1' ? 'Cow' : 'Buffalo';
            $breedRecord = $user->breeds->firstWhere('supply', $supplyType);

            if ($breedRecord) {
                $today = now()->startOfDay();
                $existingRecord = Record::where('user_id', $data['user_id'])
                                        ->where('breed_id', $breedRecord->id)
                                        ->whereDate('created_at', $today)
                                        ->first();

                if ($existingRecord) {
                    // Update existing notes
                    $existingRecord->update([
                        'notes' => $notes[$index] ?? $existingRecord->notes
                    ]);
                } else {
                    // Optionally, create a new record if it doesn't exist
                    Record::create([
                        'user_id' => $data['user_id'],
                        'breed_id' => $breedRecord->id,
                        'notes' => $notes[$index] ?? ''
                    ]);
                }
            } else {
                return response()->json(['result' => '0', 'data' => [], 'message' => "Breed record for $supplyType not found for user"], 422);
            }
        }

        $breeds=[];
        foreach ($breed as $supplies) {
            if ($supplies == 1) {
                $breeds[] = 'Cow';
            } elseif ($supplies == 2) {
                $breeds[] = 'Buffalo';
            }
        }
        $data['breed']=implode(',',$breeds);

        return response()->json(['result' => '1', 'data' => [$data], 'message' => 'Notes saved or updated']);
    }

    public function farmerdetails(Request $request){
        $role='Farmer';
        $user=User::with('breeds')->where('role',$role)->get();
        return response()->json(['result'=>'1','data'=>[$user],'message'=>'Fetched']);
    }

    public function milkmandetails(Request $request){
        $role='Milkman';
        $user=User::with('breeds')->where('role',$role)->get();
        return response()->json(['result'=>'1','data'=>[$user],'message'=>'Fetched']);
    }

    public function milksupply(Request $request){
        $data=$request->all();
        $farmer_id=$data['farmer_id'];
        $milkman_id=$data['milkman_id'];
        $morning = array_map('trim', explode(',', $data['morning']));
        $evening = array_map('trim', explode(',', $data['evening']));
        $price = array_map('trim', explode(',', $data['price']));
        $breed=array_map('trim',explode(',',$data['breed']));
        $rules=[
            'morning'=>'required',
            'evening'=>'required',
            'price'=>'required'
        ];
        if(in_array('1',$breed)){
            $rules['morning']='required';
            $rules['evening']='required';
            $rules['price']='required';
        }
        if(in_array('2',$breed)){
            $rules['morning']='required';
            $rules['evening']='required';
            $rules['price']='required';
        }

        $validator=Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(['result' => '0', 'data' => [], 'message' => str_replace(",", "|", implode(",", $validator->errors()->all()))], 422);
        }
        // $user = User::with('breeds', 'records')->where('id', $data['user_id'])->firstOrFail();

        foreach($breed as $index => $breedType){
            $supplyType = $breedType == '1' ? 'cow' : 'Buffalo';
            $breedRecord = Breed::where('supply', $supplyType)->firstorFail();
            if($breedRecord){
                $today = now()->startOfDay();
                $existingRecord=Supply::where('farmer_id',$data['farmer_id'])
                                        ->where('milkman_id',$data['milkman_id'])
                                        ->where('breed_id',$breedRecord->id)
                                        ->whereDate('created_at',$today)
                                        ->first();

            $morningValue = (string) isset($morning[$index]) ? (float) $morning[$index] : 0;
            $eveningValue =(string) isset($evening[$index]) ? (float) $evening[$index] : 0;
            $priceValue = (string) isset($price[$index]) ? (float) $price[$index] : 0;

            $totalValue = $morningValue + $eveningValue; // Calculate total

            if ($existingRecord) {
                $days = now()->diffInDays($existingRecord->created_at);

                if ($days > 15) {
                    return response()->json([
                        'result' => '0',
                        'data' => [],
                        'message' => 'You can update the records only for 15 days'
                    ]);
                }

                $existingRecord->update([
                    'morning' => $morningValue,
                    'evening' => $eveningValue,
                    'price' => $priceValue,
                    'total' => $totalValue // Update total
                ]);
            } else {
                // dd($breedRecord->id);
                Supply::create([
                    'farmer_id' => $data['farmer_id'],
                    'milkman_id' => $data['milkman_id'],
                    'breed_id' => $breedRecord->id,
                    'morning' => $morningValue,
                    'evening' => $eveningValue,
                    'price' => $priceValue,
                    'total' => $totalValue // Store total
                ]);
            }
        }
        }
        $breeds='';
        foreach ($breed as $supplies) {
            if ($supplies == 1) {
                $breeds = 'Cow';
            } elseif ($supplies == 2) {
                $breeds= 'Buffalo';
            }
        }
        $data['supply']=$breeds;

        $response=[
        'breed'=>$data['supply'],
        'morning' => $morningValue,
        'evening' => $eveningValue,
        'price' => $priceValue,
        'total' => $totalValue
        ];

        return response()->json([
            'result' => '1',
            'data' => [$response],
            'message' => 'Supply records updated successfully'
        ], 200);
    }

    public function withdraw(Request $request){
        $data=$request->all();
        dd($data);
    }

}
