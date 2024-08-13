<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Breed;
use App\Models\Connection;
use App\Models\Farmersupply;
use App\Models\Milkmansupply;
use App\Models\Record;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\WithdrawSupply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterUser extends Controller
{   #1
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

         // Set default image based on gender
            $defaultImage = $data['gender'] == 'Male'
            ? 'male.jpg'
            : 'female.jpg';
        $data['profile_image'] = asset('storage/profile_images/' . $defaultImage);
        // dd($data['profile_image']);
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
            'profile_image' => $defaultImage,
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
    #2
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
    #3
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
    #4
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
    #5
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
    #6
    public function record(Request $request){

        $data=$request->all();


        $breed=array_map('trim',explode(',',$data['breed']));
        $morning=array_map('trim',explode(',',$data['morning']));
        $evening=array_map('trim',explode(',',$data['evening']));
        $price=array_map('trim',explode(',',$data['price']));

        $rules=[
            'morning'=>'required|integer',
            'evening'=>'required|integer',
            'price'=>'nullable|integer'
        ];

        if(in_array('1',$breed) && in_array('2',$breed)){
            if(count($breed)>2 || count($morning)>2 || count($evening)>2 ){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter only cow and buffalo records']);
            }
            if(count($breed)<2 || count($morning)<2 || count($evening)<2 ){
                dd(count($breed)<2);
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter both cow and buffalo records']);
            }
            elseif(!in_array('1',$breed) && !in_array('2',$breed)){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter both cow and buffalo']);
            }

            $rules['morning']='required|string';
            $rules['evening']='required|string';
            $rules['price']='nullable|string';
        }
        elseif(in_array('1',$breed)){
            if(count($breed)>1 || count($morning)>1 || count($evening)>1){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter only cow records']);
            }
            if(empty($morning[0]) || empty($evening[0])){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter cow records']);
            }
            elseif(!in_array('1',$breed)){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter breed']);
            }
            $rules['morning']='required|string';
            $rules['evening']='required|string';
            $rules['price']='nullable|string';
        }
        elseif(in_array('2',$breed)){
            if(count($breed)>1 || count($morning)>1 || count($evening)>1){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter only buffalo records']);
            }
            if(empty($morning[0]) || empty($evening[0]) ){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter buffalo records']);
            }
            elseif(!in_array('2',$breed)){
                return response()->json(['result'=>'0','data'=>[],'message'=>'Enter breed']);
            }
            $rules['morning']='required|string';
            $rules['evening']='required|string';
            $rules['price']='nullable|string';
        }
        $validator=Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=> str_replace(",","|",implode(",",$validator->errors()->all()))]);
        }
        $breeds=[];
        foreach($breed as $breedtype){
            if($breedtype=='1'){
                $breeds[]='Cow';
            }
            if($breedtype=='2'){
                $breeds[]='Buffalo';
            }
        }

        $user=User::with('breeds')->findOrFail($data['user_id']);
        // dd($user);
        foreach ($breed as $index => $breedType) {

            $breedRecord = $user->breeds->firstWhere('supply', $breeds[$index]);
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
                        'price' => isset($price[$index]) && $price[$index] !== '' ? (int) $price[$index] : $existingRecord->price
                    ]);
                    $recordSummary = [
                        'breed' => $breeds[$index],
                        'morning' => $morning[$index],
                        'evening' => $evening[$index],
                        'price' =>(string) isset($price[$index]) && $price[$index] !== '' ? (string) $price[$index] : (string) $existingRecord->price
                    ];
                }
                else{
                    // dd($breedType);
                    $default=Breed::where('user_id',$data['user_id'])
                        ->where('supply',$breeds[$index])->first();
                    Record::create([
                        'user_id' => $data['user_id'],
                        'breed_id' => $breedRecord->id,
                        'morning' => $morning[$index] ?? '',
                        'evening' => $evening[$index] ?? '',
                        'price' => (string) isset($price[$index]) && $price[$index] !== '' ? (string) $price[$index] : (string) $default->maximum_price
                    ]);
                    $recordSummary = [
                        'breed' => $breeds[$index],
                        'morning' => $morning[$index],
                        'evening' => $evening[$index],
                        'price' => (string) isset($price[$index]) && $price[$index] !== '' ? (string) $price[$index] : (string) $default->maximum_price
                    ];
                }
            }
            elseif(!$breedRecord){
                return response()->json(['result' => '0', 'data' => [], 'message' => 'No Records found']);
            }
        }
        return response()->json(['result' => '1', 'data' => [$recordSummary], 'message' => 'Records saved']);
    }
    #7
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
    // #8
    // public function farmerdetails(Request $request){
    //     $user_id=$request->user_id;
    //     $auth_user=User::where('id',$user_id)->first();
    //     $location=$auth_user->address;
    //     // Normalize the address to handle cases, extra spaces, etc.
    //     $normalizedAddress = strtolower(trim(preg_replace('/\s+/', ' ', $location)));

    //     if (preg_match('/,\s*([^,]+?)\s*,\s*\d{6}/', $normalizedAddress, $matches)) {
    //         $district = ucwords(trim($matches[1]));
    //     } else {
    //         return response()->json(['result' => '0', 'message' => 'no users found']);
    //     }
    //     if($auth_user->role=='Milkman'){
    //         $role='Farmer';
    //     $nearbyUsers = User::with('breeds')
    //     ->where('role', $role)
    //     ->where('address', 'LIKE', '%' . $district . '%') // Assuming address contains the pincode
    //     ->get();
    //     // dd($nearbyUsers);

    //     $nearbyUsers->transform(function ($user) {
    //         $user->profile_image = $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : null;
    //         return $user;
    //     });

    //     return response()->json(['result'=>'1','data'=>[$nearbyUsers],'message'=>'Fetched']);
    //     }
    //     return response()->json(['result'=>'0','data'=>[],'message'=>'not a valid user']);
    // }
    #8
    public function farmerandmilkmandetails(Request $request){
        $user_id=$request->user_id;
        $auth_user=User::where('id',$user_id)
                    ->first();
        if(!$auth_user){
            return response()->json(['result'=>'0','data'=>[],'message'=>'not a valid user']);
        }
        $location=$auth_user->address;
        // Normalize the address to handle cases, extra spaces, etc.
        $normalizedAddress = strtolower(trim(preg_replace('/\s+/', ' ', $location)));

        if (preg_match('/,\s*([^,]+?)\s*,\s*\d{6}/', $normalizedAddress, $matches)) {
            $district = ucwords(trim($matches[1]));
        } else {
            return response()->json(['result' => '0', 'message' => 'no users found']);
        }
        if($auth_user->role=='Farmer'){
            $role='Milkman';
        $nearbyUsers = User::with('breeds')
        ->where('role', $role)
        ->where('address', 'LIKE', '%' . $district . '%') // Assuming address contains the pincode
        ->get();
        // dd($nearbyUsers);

        $nearbyUsers->transform(function ($user) {
            $user->profile_image = $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : null;
            return $user;
        });

        return response()->json(['result'=>'1','data'=>[$nearbyUsers],'message'=>'Fetched']);
        }
        if($auth_user->role=='Milkman'){
            $role='Farmer';
        $nearbyUsers = User::with('breeds')
        ->where('role', $role)
        ->where('address', 'LIKE', '%' . $district . '%') // Assuming address contains the pincode
        ->get();
        // dd($nearbyUsers);

        $nearbyUsers->transform(function ($user) {
            $user->profile_image = $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : null;
            return $user;
        });

        return response()->json(['result'=>'1','data'=>[$nearbyUsers],'message'=>'Fetched']);
        }
        return response()->json(['result'=>'0','data'=>[],'message'=>'not a valid user']);
    }
    // #9
    // public function milkmandetails(Request $request){
    //     $user_id=$request->user_id;
    //     $auth_user=User::where('id',$user_id)->first();
    //     $location=$auth_user->address;
    //     $normalizedAddress = strtolower(trim(preg_replace('/\s+/', ' ', $location)));

    //     if (preg_match('/,\s*([^,]+?)\s*,\s*\d{6}/', $normalizedAddress, $matches)) {
    //         $district = ucwords(trim($matches[1]));
    //     } else {
    //         return response()->json(['result' => '0', 'message' => 'no users found']);
    //     }
    //     if($auth_user->role=='Farmer'){
    //         $role='Milkman';
    //     $user=User::with('breeds')
    //         ->where('role',$role)
    //         ->where('address','LIKE','%'.$district.'%')
    //         ->get();
    //     $user->transform(function ($user) {
    //         $user->profile_image = $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : null;
    //         return $user;
    //     });
    //     return response()->json(['result'=>'1','data'=>[$user],'message'=>'Fetched']);
    //     }
    //     return response()->json(['result'=>'0','data'=>[],'message'=>'not a valid user']);
    // }
    #9
    public function filterusers(Request $request){
        // dd($request->all());
        $user_id=$request->user_id;
        $breed=array_map('trim',explode(',',$request->breed));
        $minimum_price=array_map('trim',explode(',',$request->minimum_price));
        $maximum_price=array_map('trim',explode(',',$request->maximum_price));
        $litres=array_map('trim',explode(',',$request->litres));
        $payout=array_map('trim',explode(',',$request->payout));

        $breeds=[];
        foreach ($breed as $supply){
            if($supply=='1'){
                $breeds[]='Cow';
            }
            if($supply=='2'){
                $breeds[]='Buffalo';
            }
        }
        $payload=[];
        foreach ($payout as $cycle){
            if($cycle=='1'){
                $payload[]='Weekly';
            }
            if($supply=='2'){
                $payload[]='15 Days';
            }
            if($supply=='3'){
                $payload[]='Monthly';
            }
        }
        $auth_user=User::with('breeds')->where('id',$user_id)
                    ->first();
        if(!$auth_user){
            return response()->json(['result'=>'0','data'=>[],'message'=>'not a valid user']);
        }
        $location=$auth_user->address;
        // Normalize the address to handle cases, extra spaces, etc.
        $normalizedAddress = strtolower(trim(preg_replace('/\s+/', ' ', $location)));

        if (preg_match('/,\s*([^,]+?)\s*,\s*\d{6}/', $normalizedAddress, $matches)) {
            $district = ucwords(trim($matches[1]));
        } else {
            return response()->json(['result' => '0', 'message' => 'no users found']);
        }
        if($auth_user->role=='Farmer'){
        $role='Milkman';
        $query = User::with(['breeds' => function ($q) use ($breeds, $minimum_price, $maximum_price) {
            if (!empty($breeds)) {
                $q->whereIn('supply', $breeds);
            }
            if (!empty($minimum_prices)) {
                $q->where(function ($subquery) use ($minimum_price) {
                    foreach ($minimum_price as $index => $min_price) {
                        if (!empty($min_price)) {
                            $subquery->orWhere('minimum_price', '>=', $min_price);
                        }
                    }
                });
            }
            if (!empty($maximum_price)) {
                $q->where(function ($subquery) use ($maximum_price) {
                    foreach ($maximum_price as $index => $max_price) {
                        if (!empty($max_price)) {
                            $subquery->orWhere('maximum_price', '<=', $max_price);
                        }
                    }
                });
            }
        }])
        ->where('role', $role)
        ->where('address', 'LIKE', '%' . $district . '%');

        if (!empty($payout)) {
            $query->whereIn('payload', $payload);
        }

        $nearbyUsers = $query->get();
        // dd($nearbyUsers);

        // dd($nearbyUsers);
        $nearbyUsers->transform(function ($user) {
            $user->profile_image = $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : null;
            return $user;
        });

        if ($nearbyUsers->isEmpty()) {
            return response()->json(['result' => '0', 'data' => [], 'message' => 'No users found']);
        }

        return response()->json(['result' => '1', 'data' => [$nearbyUsers], 'message' => 'Fetched']);
        }
        if($auth_user->role=='Milkman'){
            $role='Farmer';
            $query = User::with(['breeds' => function ($q) use ($breeds, $minimum_price, $maximum_price,$litres) {
                if (!empty($breeds)) {
                    $q->whereIn('supply', $breeds);
                }
                if (!empty($minimum_prices)) {
                    $q->where(function ($subquery) use ($minimum_price) {
                        foreach ($minimum_price as $index => $min_price) {
                            if (!empty($min_price)) {
                                $subquery->orWhere('minimum_price', '>=', $min_price);
                            }
                        }
                    });
                }
                if (!empty($maximum_price)) {
                    $q->where(function ($subquery) use ($maximum_price) {
                        foreach ($maximum_price as $index => $max_price) {
                            if (!empty($max_price)) {
                                $subquery->orWhere('maximum_price', '<=', $max_price);
                            }
                        }
                    });
                }
                if (!empty($litres)) {
                    $q->where(function ($subquery) use ($litres) {
                        foreach ($litres as $index => $litre) {
                            if (!empty($litre)) {
                                $subquery->orWhere('litres', '<=', $litre);
                            }
                        }
                    });
                }
            }])
            ->where('role', $role)
            ->where('address', 'LIKE', '%' . $district . '%');

            $nearbyUsers = $query->get();
        // dd($nearbyUsers);

        $nearbyUsers->transform(function ($user) {
            $user->profile_image = $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : null;
            return $user;
        });


        if ($nearbyUsers->isEmpty()) {
            return response()->json(['result' => '0', 'data' => [], 'message' => 'No users found']);
        }

        return response()->json(['result' => '1', 'data' => [$nearbyUsers], 'message' => 'Fetched']);
        }
    }
    #10
    public function farmrecords(Request $request){
        $data = $request->all();
        $ids = array_map('trim', explode(',', $data['user_id']));
        $supply = array_map('trim', explode(',', $data['breed']));
        $users = User::with('breeds')->whereIn('id', $ids)->first();

        if (!$users) {
            return response()->json([
                'result' => '0',
                'data' => [],
                'message' => 'not found'
            ]);
        }

        $breeds = $users->breeds;
        $maximum_price = $breeds->max('maximum_price');

        $breed = [];
        foreach ($supply as $breedId) {
            if ($breedId == '1') {
                $breed[] = 'Cow';
            }
            if ($breedId == '2') {
                $breed[] = 'Buffalo';
            }
        }

        $perPage = 7;
        $currentDate = now();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $weeks = [];

        for ($date = $startOfMonth; $date->lessThanOrEqualTo($endOfMonth); $date->addWeek()) {
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek()->min($endOfMonth);

            // Fetch records for this week
            $records = Record::with('breed')
                ->whereIn('user_id', $ids)
                ->whereHas('breed', function($query) use ($breed) {
                    $query->whereIn('supply', $breed);
                })
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->paginate($perPage);

            $weeks[] = $records;
        }

        // Prepare the response for each week
        $response = [];
        foreach ($weeks as $weekRecords) {
            $morningTotal = $weekRecords->sum('morning');
            $eveningTotal = $weekRecords->sum('evening');
            $totalLitres = $morningTotal + $eveningTotal;
            $totalPrice = $totalLitres * $maximum_price;

            $data = $weekRecords->items();
            foreach ($data as $index => $recordings) {
                $response[] = [
                    'supply' => $breed[$index],
                    'user_id' => (string) $recordings->user_id,
                    'breed_id' => (string) $recordings->breed_id,
                    'morning' => (string) $recordings->morning,
                    'evening' => (string) $recordings->evening,
                    'price' => (string) $recordings->price,
                    'notes' => (string) $recordings->notes,
                    'morningTotal' => (string) $morningTotal,
                    'eveningTotal' => (string) $eveningTotal,
                    'totalLitres' => (string) $totalLitres,
                    'totalPrice' => (string) $totalPrice,
                ];
            }
        }

        if (!empty($response)) {
            return response()->json([
                'result' => '1',
                'data' => $response,
                'message' => 'fetched'
            ]);
        } else {
            return response()->json([
                'result' => '0',
                'data' => [],
                'message' => 'not found'
            ]);
        }
    }
    #11
    public function sendrequest(Request $request){
        $follower_id=$request->follower_id;
        $following_id=$request->following_id;
        $data=$request->all();
        $validator=Validator::make($data,[
            'follower_id'=>'required',
            'following_id'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=>str_replace(',','|',implode(',',$validator->errors()->all()))]);
        }
        $connection=Connection::create([
            'follower_id'=>$follower_id,
            'following_id'=>$following_id,
            'status'=>'pending'
        ]);
        $respone=[
            'connection_id'=>$connection->id
        ];
        return response()->json(['result'=>'1','data'=>[$respone],'message'=>'request sent successfully']);
    }
    #12
    public function acceptrequest(Request $request){
        $connection=Connection::findorFail($request->connection_id);
        // $onlySoftDeleted = Connection::onlyTrashed()->get();
        // dd($onlySoftDeleted);
        $connection->update([
            'status'=>'accepted'
        ]);
        // dd($connection->following_id);
        $mutualconnection=Connection::where('follower_id',$connection->follower_id)
                        ->where('following_id',$connection->following_id)
                        ->where('status','accepted')
                        ->exists();
                        // dd($connection->following_id, $connection->follower_id, $mutualconnection);

        if($mutualconnection){
            return response()->json(['result'=>'1','data'=>[],'message'=>'request accepted successfully']);
        }

    }
    #13
     public function farmersupply(Request $request){
        $data=$request->all();
        $supply_id=$data['supply_id'];
        $reciever_id=$data['reciever_id'];
        $morning = $data['morning'];
        $evening = $data['evening'];
        $price =$data['price'];
        $breed=array_map('trim',explode(',',$data['breed']));
        $rules=[
            'morning'=>'required',
            'evening'=>'required',
            'price'=>'required',
        ];
        if(in_array('1',$breed)){
            $rules['morning']='required';
            $rules['evening']='required';
            $rules['price']='nullable';
        }
        if(in_array('2',$breed)){
            $rules['morning']='required';
            $rules['evening']='required';
            $rules['price']='nullable';
        }
        $validator=Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(['result' => '0', 'data' => [], 'message' => str_replace(",", "|", implode(",", $validator->errors()->all()))], 422);
        }
        $breeds='';
        foreach ($breed as $breedtype){
            if($breedtype == '1'){
                $breeds='Cow';
            }
            if($breedtype == '2'){
                $breeds='Buffalo';
            }
        }
        // dd($breeds);
        $today=now()->startOfDay();
        $existingRecord=Farmersupply::where('supply_id',$supply_id)
                        ->where('reciever_id',$reciever_id)
                        ->where('breed',$breeds)
                        ->whereDate('created_at',$today)
                        ->first();
        // dd($existingRecord);
        if($existingRecord){
            $morninglitre=(string)$morning ? (float)$morning :0;
            $eveninglitre=(string)$evening ? (float)$evening :0;
            $totallitre=(string) $morninglitre + $eveninglitre;
            $totalprice=$price && $price !== '' ? (int) $price : $existingRecord->price;
            $amount = $totallitre * $totalprice;

            $days=now()->diffInDays($existingRecord->created_at);

            if($days>15){
                return response()->json(['result'=>'0','data'=>[],'message'=>'You can update the records only for 15 days']);
            }
            $existingRecord->update([
                'morning'=>$morninglitre,
                'evening'=>$eveninglitre,
                'total'=>$totallitre,
                'price'=>$amount
            ]);
            $response = [
                'breed' => $breeds,
                'morning' =>(string) $morninglitre,
                'evening' =>(string) $eveninglitre,
                'price' => (string) $amount
            ];
            return response()->json(['result'=>'1','data'=>[$response],'message'=>'Supply records updated']);
        }
        else{
            $default=Record::where('user_id',$supply_id)->first();
            $morninglitre=(string)$morning ? (float)$morning :0;
            $eveninglitre=(string)$evening ? (float)$evening :0;
            $totallitre=(string) $morninglitre + $eveninglitre;
            $totalprice= (string) $price && $price !== '' ? (int) $price : $default->price;
            $amount = $totallitre * $totalprice;

            Farmersupply::create([
                'supply_id'=>$supply_id,
                'reciever_id'=>$reciever_id,
                'breed'=>$breeds,
                'morning'=>$morninglitre,
                'evening'=>$eveninglitre,
                'total'=>$totallitre,
                'price'=>$amount
            ]);
            $response = [
                    'breed' => $breeds,
                    'morning' =>(string) $morninglitre,
                    'evening' =>(string) $eveninglitre,
                    'price' => (string) $amount
                ];
                return response()->json(['result'=>'1','data'=>[$response],'message'=>'Supply records created']);
            }
    }
    #14
    public function milkmansupply(Request $request){
        $data=$request->all();
        $supply_id=$data['supply_id'];
        $reciever_id=$data['reciever_id'];
        $morning = $data['morning'];
        $evening = $data['evening'];
        $price =$data['price'];
        $breed=array_map('trim',explode(',',$data['breed']));
        $rules=[
            'morning'=>'required',
            'evening'=>'required',
            'price'=>'required',
        ];
        if(in_array('1',$breed)){
            $rules['morning']='required';
            $rules['evening']='required';
            $rules['price']='nullable';
        }
        if(in_array('2',$breed)){
            $rules['morning']='required';
            $rules['evening']='required';
            $rules['price']='nullable';
        }
        $validator=Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(['result' => '0', 'data' => [], 'message' => str_replace(",", "|", implode(",", $validator->errors()->all()))], 422);
        }
        $breeds='';
        foreach ($breed as $breedtype){
            if($breedtype == '1'){
                $breeds='Cow';
            }
            if($breedtype == '2'){
                $breeds='Buffalo';
            }
        }
        // dd($breeds);
        $today=now()->startOfDay();
        $existingRecord=Milkmansupply::where('reciever_id',$reciever_id)
                        ->where('supply_id',$supply_id)
                        ->where('breed',$breeds)
                        ->whereDate('created_at',$today)
                        ->first();
        // dd($existingRecord);
        if($existingRecord){
            $morninglitre=(string)$morning ? (float)$morning :0;
            $eveninglitre=(string)$evening ? (float)$evening :0;
            $totallitre=(string) $morninglitre + $eveninglitre;
            $totalprice=$price && $price !== '' ? (int) $price : $existingRecord->price;
            $amount = $totallitre * $totalprice;

            $days=now()->diffInDays($existingRecord->created_at);

            if($days>15){
                return response()->json(['result'=>'0','data'=>[],'message'=>'You can update the records only for 15 days']);
            }
            $existingRecord->update([
                'morning'=>$morninglitre,
                'evening'=>$eveninglitre,
                'total'=>$totallitre,
                'price'=>$amount
            ]);
            $response = [
                'breed' => $breeds,
                'morning' =>(string) $morninglitre,
                'evening' =>(string) $eveninglitre,
                'price' => (string) $amount
            ];
            return response()->json(['result'=>'1','data'=>[$response],'message'=>'Supply records updated']);
        }
        else{
            $default=Record::where('user_id',$supply_id)->first();
            $morninglitre=(string)$morning ? (float)$morning :0;
            $eveninglitre=(string)$evening ? (float)$evening :0;
            $totallitre=(string) $morninglitre + $eveninglitre;
            $totalprice= (string) $price && $price !== '' ? (int) $price : $default->price;
            $amount = $totallitre * $totalprice;

            Milkmansupply::create([
                'reciever_id'=>$reciever_id,
                'supply_id'=>$supply_id,
                'breed'=>$breeds,
                'morning'=>$morninglitre,
                'evening'=>$eveninglitre,
                'total'=>$totallitre,
                'price'=>$amount
            ]);
            $response = [
                    'breed' => $breeds,
                    'morning' =>(string) $morninglitre,
                    'evening' =>(string) $eveninglitre,
                    'price' => (string) $amount
                ];
                return response()->json(['result'=>'1','data'=>[$response],'message'=>'Supply records created']);
            }
    }
    #15
    public function supplyrecords(Request $request) {
        $data = $request->all();
        $user1 = array_map('trim', explode(',', $data['user_id_1']));
        $user2 = array_map('trim', explode(',', $data['user_id_2']));
        $supply = array_map('trim', explode(',', $data['breed']));
        $breed = [];
        foreach ($supply as $breeds) {
            if ($breeds == '1') {
                $breed[] = 'Cow';
            }
            if ($breeds == '2') {
                $breed[] = 'Buffalo';
            }
        }

        $perPage = 7;
        $currentDate = now();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $weeks = [];
        // Calculate the start date for the last 7 days of the current month
        $startDate = $startOfMonth->copy()->subDays(7)->startOfDay();
        $endDate = $endOfMonth->copy()->endOfDay();

        $user=User::where('id',$user1)->first();
        if($user->role=='Farmer'){
            // dd(1);
            for ($date = $startOfMonth; $date->lessThanOrEqualTo($endOfMonth); $date->addWeek()) {
                $startOfWeek = $date->copy()->startOfWeek();
                $endOfWeek = $date->copy()->endOfWeek()->min($endOfMonth);
                // Fetch records for this week
                $farmerrecords = Farmersupply::where('supply_id', $user1)
                        ->where('reciever_id', $user2)
                        ->where('breed',$breed)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->paginate($perPage);
                $farmerweeks[] = $farmerrecords;
                $milkmanrecords = Milkmansupply::where('reciever_id', $user2)
                        ->where('supply_id', $user1)
                        ->where('breed',$breed)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->paginate($perPage);
                $milkmanweeks[] = $milkmanrecords;
            }
                if ($farmerrecords->isEmpty()) {
                    return response()->json(['result' => '0', 'data' => [], 'message' => 'No records found'], 404);
                }
                if($milkmanrecords->isEmpty()){
                    $response=[];
                    foreach ($farmerweeks as $farmerweekRecords){
                        $farmerdatas=$farmerweekRecords->items();
                        foreach($farmerdatas as $farmerrecordings){
                            $response=[
                                'breed' => $farmerrecordings->breed,
                                    'farmer'=>[
                                    'morning' => (string) $farmerrecordings->morning,
                                    'evening' => (string) $farmerrecordings->evening,
                                    'total' => (string) $farmerrecordings->total,
                                    'price' => (string) $farmerrecordings->price,
                                    'date' => $farmerrecordings->created_at->format('Y-m-d'),
                                    ]
                            ];
                            return response()->json(['result' => '1', 'data' => $response, 'message' => 'Supply records retrieved successfully']);
                        }
                    }
                }
                if($farmerrecords && $milkmanrecords){
                    $response = [];
                foreach ($farmerweeks as $farmerweekRecords){
                    $farmerdatas=$farmerweekRecords->items();
                    foreach($milkmanweeks as $milkmanweekRecords){
                        $milkmandatas=$milkmanweekRecords->items();
                        foreach($farmerdatas as $farmerrecordings){
                            foreach($milkmandatas as $milkmanrecordings){
                                $response=[
                                    'breed' => $farmerrecordings->breed,
                                    'farmer'=>[
                                    'morning' => (string) $farmerrecordings->morning,
                                    'evening' => (string) $farmerrecordings->evening,
                                    'total' => (string) $farmerrecordings->total,
                                    'price' => (string) $farmerrecordings->price,
                                    'date' => $farmerrecordings->created_at->format('Y-m-d'),
                                    ],
                                    'milkman'=>[
                                        'morning'=>$milkmanrecordings->morning,
                                        'evening' => (string) $milkmanrecordings->evening,
                                        'total' => (string) $milkmanrecordings->total,
                                        'price' => (string) $milkmanrecordings->price,
                                        'date' => $milkmanrecordings->created_at->format('Y-m-d'),
                                    ]
                                    ];
                            }
                        }
                    }
                }
                return response()->json(['result' => '1', 'data' => $response, 'message' => 'Supply records retrieved successfully']);
                }
        }
        if($user->role=='Milkman'){
            // dd(2);
            for ($date = $startOfMonth; $date->lessThanOrEqualTo($endOfMonth); $date->addWeek()) {
                $startOfWeek = $date->copy()->startOfWeek();
                $endOfWeek = $date->copy()->endOfWeek()->min($endOfMonth);
                // Fetch records for this week
                $milkmanrecords = Milkmansupply::where('reciever_id', $user1)
                        ->where('supply_id', $user2)
                        ->where('breed',$breed)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->paginate($perPage);
                $milkmanweeks[] = $milkmanrecords;

                $farmerrecords = Farmersupply::where('supply_id', $user2)
                        ->where('reciever_id', $user1)
                        ->where('breed',$breed)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->paginate($perPage);

                $farmerweeks[] = $farmerrecords;
            }
            // dd($records);
                if ($milkmanrecords->isEmpty()) {
                    return response()->json(['result' => '0', 'data' => [], 'message' => 'No records found'], 404);
                }
                if($farmerrecords->isEmpty()){
                    $response=[];
                    foreach ($milkmanweeks as $milkmanweekRecords){
                        $milkmandatas=$milkmanweekRecords->items();
                        foreach($milkmandatas as $milkmanrecordings){
                            $response=[
                                'breed' => $milkmanrecordings->breed,
                                    'milkman'=>[
                                    'morning' => (string) $milkmanrecordings->morning,
                                    'evening' => (string) $milkmanrecordings->evening,
                                    'total' => (string) $milkmanrecordings->total,
                                    'price' => (string) $milkmanrecordings->price,
                                    'date' => $milkmanrecordings->created_at->format('Y-m-d'),
                                    ]
                            ];
                            return response()->json(['result' => '1', 'data' => $response, 'message' => 'Supply records retrieved successfully']);
                        }
                    }
                }
                if($farmerrecords && $milkmanrecords){
                    $response = [];
                foreach ($milkmanweeks as $weekRecords){
                        $milkmandatas=$weekRecords->items();
                    foreach ($farmerweeks as $farmerweekrecords){
                        $farmerdatas=$farmerweekrecords->items();
                        foreach($milkmandatas as $index => $recordings){
                            foreach($farmerdatas as $farmerrecordings){
                                $response=[
                                    'breed' => $recordings->breed,
                                    'milkman'=>[
                                    'morning' => (string) $recordings->morning,
                                    'evening' => (string) $recordings->evening,
                                    'total' => (string) $recordings->total,
                                    'price' => (string) $recordings->price,
                                    'date' => $recordings->created_at->format('Y-m-d'),
                                    ],
                                    'farmer'=>[
                                        'morning'=>$farmerrecordings->morning,
                                        'evening' => (string) $farmerrecordings->evening,
                                        'total' => (string) $farmerrecordings->total,
                                        'price' => (string) $farmerrecordings->price,
                                        'date' => $recordings->created_at->format('Y-m-d'),
                                    ]
                                    ];
                            }
                        }
                    }
                }
                if (!empty($response)) {
                    return response()->json([
                        'result' => '1',
                        'data' => [$response],
                        'message' => 'fetched'
                    ]);
                } else {
                    return response()->json([
                        'result' => '0',
                        'data' => [],
                        'message' => 'not found'
                    ]);
                }
        }

                }

        // if (!empty($response)) {
        //     return response()->json([
        //         'result' => '1',
        //         'data' => $response,'total_litres' => (string) $overall_total,'total_price' => (string) $total_price,
        //         'message' => 'fetched'
        //     ]);
        // } else {
        //     return response()->json([
        //         'result' => '0',
        //         'data' => [],
        //         'message' => 'not found'
        //     ]);
        // }
    }
    #16
    public function requestwithdraw(Request $request){
        $data=$request->all();
        // dd($data);
        $withdraw=array_map('trim',explode(',',$data['withdraw']));
        $rules=[
            'date'=>'required',
            'withdraw'=>'required',
            'description'=>'required',
        ];

        $validator = Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(['result'=>'0','data'=>[],'message'=>str_replace(',','|',implode(',',$validator->errors()->all()))]);
        }
        $withdraw_reason=[];
        foreach($withdraw as $withdraws){
            if($withdraws=='1'){
                $withdraw_reason[]='Payment Issues';
            }
            if($withdraws=='2'){
                $withdraw_reason[]='Harsh or Mishbehaving';
            }
            if($withdraws=='3'){
                $withdraw_reason[]='Cheating or fraud';
            }
            if($withdraws=='4'){
                $withdraw_reason[]='Others';
            }
        }
        $data['withdraw']=implode(',',$withdraw_reason);
        if($data['withdraw']){
            WithdrawSupply::create([
                'user_1_id'=>$data['user_1'],
                'user_2_id'=>$data['user_2'],
                'date'=>$data['date'],
                'withdraw'=>$data['withdraw'],
                'description'=>$data['description'],
                'status'=>'pending'
            ]);
        }

        return response()->json(['result'=>'1','data'=>[$data],'message'=>'request sent']);
    }
    #17
    public function acceptwithdraw(Request $request){
        $withdraw=WithdrawSupply::findorFail($request->withdraw_id);
        $withdraw->update([
            'status'=>'withdrawed',
        ]);
        $user=User::where('id',$withdraw->user_1_id)->first();
        // dd($user);
        if($user->role=='Farmer'){
            // dd(1);
            $farmer=Farmersupply::where('supply_id',$withdraw->user_1_id)
                        ->where('reciever_id',$withdraw->user_2_id)
                        ->delete();
             $milkman=Milkmansupply::where('reciever_id',$withdraw->user_2_id)
                    ->where('supply_id',$withdraw->user_1_id)
                    ->delete();
            $connection=Connection::where('follower_id',$withdraw->user_1_id)->first();
            // dd($connection);
            if($connection===null){
                $farmerconnection=Connection::where('following_id',$withdraw->user_2_id)->delete();
           }
           elseif($connection){
                Connection::where('follower_id',$withdraw->user_1_id)->delete();
           }
        }
        if($user->role=='Milkman'){
            $milkman=Milkmansupply::where('reciever_id',$withdraw->user_1_id)
                    ->where('supply_id',$withdraw->user_2_id)
                    ->delete();
            $farmer=Farmersupply::where('supply_id',$withdraw->user_2_id)
                        ->where('reciever_id',$withdraw->user_1_id)
                        ->delete();
            $connection=Connection::where('follower_id',$withdraw->user_1_id)->first();
               if($connection===null){
                    $farmerconnection=Connection::where('follower_id',$withdraw->user_2_id)->delete();
               }
               elseif($connection){
                Connection::where('following_id',$withdraw->user_1_id)->delete();
               }
        }
        $connection->update([
            'status'=>'withdrawed',
        ]);
        return response()->json(['result'=>'0','data'=>[$withdraw],'message'=>'successfully withdrawed']);
    }
    #18
    public function rejectwithdraw(Request $request){
        $withdraw=WithdrawSupply::findorFail($request->withdraw_id);
        $withdraw->update([
            'status'=>'rejected',
        ]);
        return response()->json(['result'=>'1','data'=>[],'message'=>'request rejected']);
    }
    #19
    public function updateuser(Request $request,){
        // Split the supply values into an array
        $data=$request->all();
        $id=$data['user_id'];
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
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|string|unique:users,phone,'.$id,
            'supply'=>'required|string',
            'minimum_price'=>'required|string',
            'maximum_price'=>'required|string',
            'payload'=>'required',
            'photo' => 'nullable|string',
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

        $users=User::with('breeds')->find($id);
        if(!$users){
            return response()->json(['result'=>'0','data'=>[],'message'=>'user not found']);
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
        // dd($data['supply']);

        // Handle profile image
        $base64image = $data['photo'] ?? null;
        if ($base64image) {
            // Delete old photo if it exists
            if ($users->profile_image && $users->profile_image !== 'male.jpg' && $users->profile_image !== 'female.jpg') {
                $oldImagePath = storage_path('app/public/profile_images/' . $users->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = base64_decode($base64image);
            $fileName = 'profile_' .'user'. $users->id . '_' . '.jpg';

            $filePath = storage_path('app/public/profile_images/' . $fileName);
            if (file_put_contents($filePath, $image)) {
                $imageUrl = asset('storage/profile_images/' . $fileName);
            } else {
                return response()->json(['result' => '0', 'data' => [], 'message' => 'Failed to save the image'], 500);
            }
        } else {
            $defaultImage = $data['gender'] == 'Male' ? 'male.jpg' : 'female.jpg';
            $imageUrl = asset('storage/profile_images/' . $defaultImage);
            $fileName = $defaultImage;
        }
        // dd($fileName);

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
        // dd($fileName);
        // / Update user details
        $users->update([
            'name' => $request->input('name'),
            'role' => $data['role'],
            'gender' => $data['gender'],
            'dob' => $data['dob'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'payload'=>$data['payload'],
            'profile_image'=>$fileName
        ]);

        // dd($users->breeds);
        foreach($breed as $index => $breeding){
            $breeddata=[
                'user_id' => $users->id,
                'supply' => $breeding,
                'litres' => $litres[$index] ?? '',
                'minimum_price' => $minimum_price[$index] ?? '',
                'maximum_price' => $maximum_price[$index] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $existingbreed=Breed::where('user_id',$id)->where('supply',$breeding)->first();
            if($existingbreed){
                $existingbreed->update($breeddata);
            }
            else{
                Breed::create($existingbreed);
            }
        }
        $response=[
            'name' => $users->name,
            'role' => $users->role,
            'gender' => $users->gender,
            'dob' => $users->dob,
            'address' => $users->address,
            'email' => $users->email,
            'phone' => $users->phone,
            'payload'=>$users->payload,
            'supply'=>$data['supply'],
            'minimum_price'=>$data['maximum_price'],
            'maximum_price'=>$data['minimum_price'],
            'payload'=>$data['payload'],
            'profile_image'=>$imageUrl

        ];

        return response()->json([
            'result' => '1',
            'data' => [$response],
            'message' => 'User updated successfully'
        ]);

    }
    #20
    public function review(Request $request){
        $data=$request->all();
        $user_id=$data['user_id'];
        $reviewer_id=$data['reviewer_id'];
        $rating=$data['rating'];
        $feedback=$data['feedback'];

        $user=User::where('id',$user_id)->first();
        $reviewer=User::where('id',$reviewer_id)->first();
        if($rating && $feedback){
            $validator=Validator::make($data,[
                'rating'=>'required|between:1,2,3,4,5',
                'feedback'=>'required|min:5'
            ]);
            if($validator->fails()){
                return response()->json(['result'=>'0','data'=>[],'message'=>str_replace(",","|",implode(",",$validator->errors()->all()))], 422);
            }
            $reviews=Review::create([
                'user_id'=>$user->id,
                'reviewer_id'=>$reviewer->id,
                'ratings'=>$rating,
                'feedback'=>$feedback
            ]);
            return response()->json(['result'=>'1','data'=>[$data],'message'=>'review submitted']);
        }
        else{
            $reviews = Review::with('reviewer')->where('reviewer_id', $reviewer_id)->get();
            foreach($reviews as $review){
                    $response[]=[
                        'user_id'=>$review->user_id,
                        'reviewer_id'=>$review->reviewer_id,
                        'name'=>$review->reviewer->name,
                        'ratings'=>$review->ratings,
                        'feedback'=>$review->feedback
                    ];
            }
            return response()->json(['result'=>'1','data'=>$response,'message'=>'all reviews']);
        }
    }
}
