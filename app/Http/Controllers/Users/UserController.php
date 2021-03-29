<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //

    public function getAlluser(){
        $users=Role::where('name','user')->first()->users()->get();
        return response()->json([
            'user'=>$users
        ]);
    }


    public function listOfOnlineAgent(){
        $users=Role::where('name','agent')->first()->users()->where('status','online')->get();
        return response()->json([
            'user'=>$users
        ]);
    }

    public function attachmentRole(Request $request, $id){
        $user= $request->user()->id;
        $email=$request->user()->email; 
        $agent_result=User::where('id',$id)->pluck('email')->first();
        $agent = new Quotation();
        $agent->user_id=$user;
        $agent->email=$email;
        $agent->agent_email=$agent_result;
        $agent->agent_id=(int)($id);
        $agent->save();
      
        return response()->json([
            'status'=>true,
             'data'=>$agent
        ],201);
        
    }

    
    public function DeclineTransaction(Request $request){
        $user= $request->user()->id;
         $agent = Quotation::where('user_id',$user);
         $agent->delete();
         return response()->json([
             'status'=>true,
              'data'=>$agent
         ],201);
         
     }

    
   
    public function checkQuoatation(Request $request){
        $user=$request->user()->id;
        $quoat=Quotation::where('user_id',$user)->get();
        $response=[
            'status'=>true,
            'data'=>$quoat
        ];
        return response($response,201);
        
    }

    public function acceptQuotation(Request $request){
        $user=$request->user()->id;
        $quoat=Quotation::where('user_id',$user)->value('user_id');
        $accepted='accepted';
        $newQuote=DB::update("update quotations set quoatStatus='accepted' where id=?",[$quoat]);
        $response=[
            'status'=>true,
             'message'=>'quote accepted by client',
            'data'=>$newQuote
        ];
        return response($response,201);

    }

    public function getUserById(Request $request){
        $user = User::where('id',$request->id)->first();
        return response()->json([
             'status'=>true,
              'data'=>$user
         ],200);
    }

    public function declineQuoation(Request $request){
        $user=$request->user()->id;
        
    }
    public function paymentByWallet(Request $request){
        $user=$request->user()->id;
        
    }
    public function paymentHistory(Request $request){
        $user=$request->user()->id;

    }
    public function paymentByCard(){

    }
    
}
