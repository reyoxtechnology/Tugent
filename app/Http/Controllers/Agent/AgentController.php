<?php

namespace App\Http\Controllers\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Loan;
use App\Models\Wallet;

class AgentController extends Controller
{
   
    public function getAllAgent(){ 
        $users=Role::where('name','agent')->first()->users()->get();
        return response()->json([
            'user'=>$users
        ]);
    }
    public function ComeOnline(Request $request){
        $user=$request->user()->id;
        $active='online';
        $online=User::find($user);
        $online->status= $active;
        $online->save();
        $response=[
            "status"=>true,
            "message"=>'agent is online',
             "data"=>$user
        ];
        return response($response,201);
    }
    public function generateQuote(Request $request){
        $user=$request->user()->id;
        $email=DB::table('quotations')->where('user_id',$user)->value('email');
        $name=DB::table('users')->where('id',$user)->value('name');
        $amount= $request->amount;
        $commission= $request->commission;
      
        $attachment= $request->file('attachment');
        if(!empty($attachment))
            $attach='attachment sent';
        $sender="Fridayshegun@gmail.com";
        $subject = 'Hello '.$name.' this is your Quotation list';
        $data=[
            'users'=>$sender,
            'attachment'=>$attachment,
            'subject'=>$subject,
            'mail'=>$email

        ];
        
        Mail::send('message.email',['data'=>$data],function($message) use ($data){
            $message->to($data['mail']);
            $message->subject($data['subject']);
            $message->from($data['users']);    
                $message->attach($data['attachment']->getRealPath(), array(
                    'as'=> 'attachment.' .$data['attachment']->getClientOriginalExtension(),
                    'mime'=>$data['attachment']->getMimeType())
                );
            });

        DB::table('quotations')
                ->where('user_id',$user)
                ->update([
                    'amount'=>$amount,
                    'commission'=>$commission,
                    'attachment'=>$attach
                    ]);
       
       
        $response=[
            'status'=>true,
            'message'=>'Quotation was sucessfully generated'
        ];
        return response($response,201);
    }
    public function takeLoan(Request $request){

        $user=$request->user()->id;
        $loanAmount=$request->amount;
        $interest=$loanAmount/100;
        $interestRate=$interest * 10;
        $mimum=2000000;

        $ifLoanAvaliable =DB::table('loans')->where('user_id',$user)->exists();
        if(!empty($ifLoanAvaliable)){
            $response=[
                'status'=>true,
                'message'=>'You cant take loan because you havent finishing paying old loan'
            ];
            return response($response,401);
        }else{

            if($loanAmount >$mimum){
                $response=[
                    'status'=>true,
                    'message'=>'you cant take more than 200,0000'
                ];
                return response($response,401);
            }else{
    
                 $loan=new Loan();
                 $loan->user_id=$user;
                 $loan->amount=$loanAmount;  
                 $loan->interest=$interestRate;
                 $loan->save(); 
                $response=[
                    'status'=>true,
                    'message'=>'loan Request was Successful'
                ];
                return response($response,201);
            }
        }
      
    }


    public function loanPayback(Request $request){

        $user=$request->user()->id;
        $amountBrought=$request->amount;

        $loan=Loan::where('user_id',$user)->value('amount');
        $payback=Loan::where('user_id',$user)->value('payback');
        $id=Loan::where('user_id',$user)->value('id');

        $loanResult =$loan - $amountBrought;
        $interestRate=$loanResult /100;
        $newInterest=$interestRate * 10;
        
        //this function handle deduction from 
        $wallentAmount=Wallet::where('user_id',$user)->value('balance');
        $removeWallet=$wallentAmount - $amountBrought;
        $walletid=Wallet::where('user_id',$user)->value('id');
        $walletupdated=Wallet::find($walletid);
        $walletupdated->balance =$removeWallet;
        $walletupdated->save();

        if(!empty($payback)){
        
                //this checks if the users already make payment before
                $result=$payback + $amountBrought;
                $remainingAmount =$loan -$result;
                $interestRate=$result /100;
                $newInterest=$interestRate * 10;

                $loan=Loan::find($id);
                $loan->payback=$result;
                $loan->remainingAmount=$remainingAmount;
                $loan->interestLeft=$newInterest;
                $loan->save(); 

                if($payback ==$loan){
                    $db=Loan::find($id);
                    $db->delete();
    
                    $response=[
                        'status'=>true,
                        'message'=>'Loan payment Completed'
                    ];
                    return response($response,201);
                  
                }
        }else{
            //this method save first payment
            $loan=Loan::find($id);
            $loan->payback=$amountBrought;
            $loan->remainingAmount=$loanResult;
            $loan->interestLeft=$newInterest;
            $loan->save();
        };

        $response=[
            'status'=>true,
            'message'=>'Loan payment Successful'
        ];
        return response($response,201);
     
    }

}
