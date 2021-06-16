<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Conversation;
use App\Message;
use Validator;
use DB;

class MessageController extends Controller
{
	
	public $successStatus = 200;
	public $errorStatus = 401;
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /*
    Show Inbox Page
     */
    public function inbox()
    {
        $user_id               = auth('api')->user()->id;
        $data                  = array();
        $data['conversations'] = Conversation::with(
                                                [
                                                    'messages' => function ($query) use ($user_id) {
                                                        return $query->where(
                                                                function ($query) use ($user_id) {
                                                                    $query->where('user_id', '!=', $user_id);
                                                                    $query->where('deleted_from_receiver', 0);
                                                                }
                                                            )->latest();
                                                    }, 'sender', 'receiver',
                                                ]
                                            )
                                            ->whereHas('messages', function ($query)  use ($user_id){
                                                 $query->where('user_id', '!=', $user_id);
                                                 $query->where('deleted_from_receiver', 0);
                                            })
                                            //->orWhere('sender_id', $user_id)
                                            ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id = $user_id)")
                                            ->orderBy('updated_at','desc')
                                            ->paginate(10);
        $data['unread_item_count'] = Conversation::get_unread_inbox_count($user_id);
		
        return response()->json($data, $this->successStatus);

    }

    /*
       Show Outbox Page
    */
    public function outbox()
    {
        $user_id               = auth('api')->user()->id;
        $data                  = array();
        $data['conversations'] = Conversation::with(
                                                [
                                                    'messages' => function ($query) use ($user_id) {
                                                        return $query->where(
                                                                function ($query) use ($user_id) {
                                                                    $query->where('user_id', $user_id);
                                                                    $query->where('deleted_from_sender', 0);
                                                                }
                                                            )->latest();
                                                    }, 'sender', 'receiver',
                                                ]
                                            ) 
                                            ->whereHas('messages', function ($query)  use ($user_id){
                                                 $query->where('user_id', $user_id);
                                                 $query->where('deleted_from_sender', 0);
                                            })
                                            ->where('sender_id', $user_id)
                                            ->orderBy('created_at','desc')
                                            ->paginate(10);
        $data['unread_item_count'] = Conversation::get_unread_inbox_count($user_id);

        return response()->json($data, $this->successStatus);
    }

    /*
        View Inbox Message Details
     */
    public function view_inbox($id)
    {
        DB::beginTransaction();
        $user_id              = auth('api')->user()->id;
        $data                 = array();
        $data['conversation'] = Conversation::with(
                                                [
                                                    'messages' => function ($query) use ($user_id) {
                                                        return $query->where(
                                                                function ($query) use ($user_id) {
                                                                    $query->where('user_id', '!=', $user_id);
                                                                    $query->where('deleted_from_receiver', 0);
                                                                }
                                                            )
                                                        ->orWhere(
                                                                   function ($query) use ($user_id) {
                                                                        $query->where('user_id', $user_id);
                                                                        $query->where('deleted_from_sender', 0);
                                                                    }
                                                                )
															->with('sender')
                                                            ->get();
                                                    }
                                                ]
                                            )
                                            ->whereRaw('(receiver_id = ? OR sender_id = ?)', [$user_id, $user_id])
                                            ->where('conversations.id', $id)
                                            ->first();

        //Mark as read
        Message::join('conversations','messages.conversation_id','conversations.id')
                          ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id = $user_id)")
                          ->where('messages.user_id','!=',$user_id)
                          ->where('messages.conversation_id',$id)
                          ->select('messages.*')
                          ->update(['is_seen' => 1]);

        DB::commit();

        return response()->json($data, $this->successStatus);
    }

     /*
        View OutBox Message Details
     */
    public function view_outbox($id)
    {
        $user_id              = auth('api')->user()->id;
        $data                 = array();
        $data['conversation'] = Conversation::with(
                                                [
                                                    'messages' => function ($query) use ($user_id) {
                                                        return $query->where(
                                                                function ($query) use ($user_id) {
                                                                    $query->where('user_id', $user_id);
                                                                    $query->where('deleted_from_sender', 0);
                                                                }
                                                            )
                                                            ->orWhere(
                                                                   function ($query) use ($user_id) {
                                                                        $query->where('user_id', '!=', $user_id);
                                                                        $query->where('deleted_from_receiver', 0);
                                                                    }
                                                                )
                                                            ->get();
                                                    }, 'sender', 'receiver',
                                                ]
                                            )
                                            ->whereRaw('(receiver_id = ? OR sender_id = ?)', [$user_id, $user_id])
                                            ->where('conversations.id', $id)
                                            ->first();
											
        return response()->json($data, $this->successStatus);
    }


    /*
    Send Message
    */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver'   => 'required',
            'subject'    => 'required',
            'message'    => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlxs,zip|max:5120',
        ]);

        if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}

        $attachment = '';
        if ($request->hasFile('attachment')) {
            $image     = $request->file('attachment');
            $file_name = "attachment_" . time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/media/'), $file_name);
            $attachment = $file_name;
        }

        DB::beginTransaction();

        $sender_id = auth('api')->user()->id;


		//Create Conversation
		$conversation              = new Conversation();
		$conversation->subject     = $request->subject;
		$conversation->sender_id   = $sender_id;
		$conversation->receiver_id = $request->receiver;
		$conversation->status      = 1;
		$conversation->save();

		//Create Message
		$message                  = new Message();
		$message->message         = $request->message;
		$message->attachment      = $attachment;
		$message->user_id         = $sender_id;
		$message->conversation_id = $conversation->id;
		$message->save();


        DB::commit();


		$data['result']  = true;
		$data['message'] = _lang('Message Send Sucessfully');
		
		return response()->json($data, $this->successStatus);
    }


     /*
      Reply Message
    */
    public function reply_message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id'    => 'required',
            'message'            => 'required',
            'attachment'         => 'nullable|mimes:jpeg,jpg,JPG,png,PNG,pdf,doc,docx,xls,xlxs,zip|max:5120',
        ]);

        if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}

        $attachment = '';
        if ($request->hasFile('attachment')) {
            $image     = $request->file('attachment');
            $file_name = "attachment_" . time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/media/'), $file_name);
            $attachment = $file_name;
        }

        DB::beginTransaction();

        $sender_id = auth('api')->user()->id;


        //Create Message
        $message                  = new Message();
        $message->message         = $request->message;
        $message->attachment      = $attachment;
        $message->user_id         = $sender_id;
        $message->is_seen         = 0;
        $message->conversation_id = $request->conversation_id;
        $message->save();

        $message->conversation->touch();

        DB::commit();

		$data['result']  = true;
		$data['data']  =  $message;
		$data['message'] = _lang('Message Send Sucessfully');
		
		return response()->json($data, $this->successStatus);

    }

    /*  Remove Message By Id */
    public function remove($message_id){
        $user_id = auth('api')->user()->id;
        $message = Message::join('conversations','messages.conversation_id','conversations.id')
                          ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id=$user_id)")
                          ->where('messages.id',$message_id)
                          ->select('messages.*')
                          ->first();

        if($message->user_id == $user_id){
            $message->deleted_from_sender = 1;
        }else{
            $message->deleted_from_receiver = 1;
        }   
        $message->save();

		$data['result']  = true;
		$data['message'] = _lang('Message Removed');
		
		return response()->json($data, $this->successStatus);           
    }


    public function bulk_action(Request $request){
        $user_id = auth('api')->user()->id;
         
        if ( $request->action == 'mark_as_read' ){
             Message::join('conversations','messages.conversation_id','conversations.id')
                              ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id = $user_id)")
                              ->where('messages.user_id','!=',$user_id)
                              ->whereIn('messages.conversation_id', $request->conversation_id)
                              ->select('messages.*')
                              ->update(['is_seen' => 1]);
        } else if ( $request->action == 'move_to_trash' ){
            
			//return $request->conversation_id;
			
			DB::beginTransaction();
            
            $message = Message::join('conversations','messages.conversation_id','conversations.id')
                              ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id = $user_id)")
                              ->where('messages.user_id','!=',$user_id)
                              ->whereIn('messages.conversation_id', $request->conversation_id)
                              ->select('messages.*')
                              ->update(['deleted_from_receiver' => 1]);            

            Message::join('conversations','messages.conversation_id','conversations.id')
                              ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id = $user_id)")
                              ->where('messages.user_id',$user_id)
                              ->whereIn('messages.conversation_id', $request->conversation_id)
                              ->select('messages.id as id','messages.*','users.*')
                              ->update(['deleted_from_sender' => 1]);                 


            DB::commit();              
        }

        $data['result']  = true;		
		return response()->json($data, $this->successStatus);  
    }
	
	public function receiver_list(){
		$users = \App\User::where('id','!=',auth('api')->user()->id)->get();
		return response()->json($users, $this->successStatus);
	}

}