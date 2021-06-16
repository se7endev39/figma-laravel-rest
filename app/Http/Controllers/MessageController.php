<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Message;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MessageNotification;
use App\Utilities\Overrider;
use Validator;

class MessageController extends Controller
{
    /**
     * Show the Settings Page.
     *
     * @return Response
     */

    public function __construct()
    {
        date_default_timezone_set(get_option('timezone'));
    }

    /*
    Show Inbox Page
     */
    public function inbox()
    {
        $user_id               = Auth::id();
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
        $data['unread_item_count'] = Conversation::get_unread_inbox_count();
        return view('backend.messaging.inbox', $data);

    }

    /*
        Show Outbox Page
    */
    public function outbox()
    {
        $user_id               = Auth::id();
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
        $data['unread_item_count'] = Conversation::get_unread_inbox_count();

        return view('backend.messaging.outbox', $data);
    }

    /*
        View Inbox Message Details
     */
    public function view_inbox($id)
    {
        DB::beginTransaction();
        $user_id              = Auth::id();
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
                                                            ->get();
                                                    }, 'sender', 'receiver',
                                                ]
                                            )
                                            ->whereRaw('(receiver_id = ? OR sender_id = ?)', [$user_id, $user_id])
                                            ->where('conversations.id', $id)
                                            ->first();
        $data['unread_item_count'] = Conversation::get_unread_inbox_count();

        //Mark as read
        Message::join('conversations','messages.conversation_id','conversations.id')
                          ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id = $user_id)")
                          ->where('messages.user_id','!=',$user_id)
                          ->where('messages.conversation_id',$id)
                          ->select('messages.*')
                          ->update(['is_seen' => 1]);

        DB::commit();

        return view('backend.messaging.view', $data);
    }

     /*
        View OutBox Message Details
     */
    public function view_outbox($id)
    {
        $user_id              = Auth::id();
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
        $data['unread_item_count'] = Conversation::get_unread_inbox_count();

        return view('backend.messaging.view', $data);
    }

    /*
    Show Compose Page
     */
    public function compose()
    {
        $data                      = array();
        $data['unread_item_count'] = Conversation::get_unread_inbox_count();
        return view('backend.messaging.compose', $data);
    }

    /*
    Send Message
    */
    public function send(Request $request)
    {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        $validator = Validator::make($request->all(), [
            'receiver'   => 'required',
            'subject'    => 'required',
            'message'    => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlxs,zip|max:5120',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)
                    ->withInput();
            }
        }

        $attachment = '';
        if ($request->hasFile('attachment')) {
            $image     = $request->file('attachment');
            $file_name = "attachment_" . time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/media/'), $file_name);
            $attachment = $file_name;
        }

        DB::beginTransaction();

        $sender_id = Auth::id();

        foreach ($request->receiver as $receiver => $receiver_id) {

            //Create Conversation
            $conversation              = new Conversation();
            $conversation->subject     = $request->subject;
            $conversation->sender_id   = $sender_id;
            $conversation->receiver_id = $receiver_id;
            $conversation->status      = 1;
            $conversation->save();

            //Create Message
            $message                  = new Message();
            $message->message         = $request->message;
            $message->attachment      = $attachment;
            $message->user_id         = $sender_id;
            $message->conversation_id = $conversation->id;
            $message->save();

        }
		
		//Send Email Notifications
		Overrider::load("Settings");
		$users = User::whereIn('id', $request->receiver)->get();
		//$delay = now()->addSecond(2);
		//Notification::send($users, (new MessageNotification($message))->delay($delay));
		
		try{
			Notification::send($users, new MessageNotification($message));
		}catch(\Exception $e){
			//Nothing
		}

        DB::commit();

        return back()->with('success', _lang('Message Send Sucessfully'));

    }


     /*
      Reply Message
    */
    public function reply_message(Request $request)
    {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        $validator = Validator::make($request->all(), [
            'conversation_id'    => 'required',
            'message'            => 'required',
            'attachment'         => 'nullable|mimes:jpeg,jpg,JPG,png,PNG,pdf,doc,docx,xls,xlxs,zip|max:5120',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)
                    ->withInput();
            }
        }

        $attachment = '';
        if ($request->hasFile('attachment')) {
            $image     = $request->file('attachment');
            $file_name = "attachment_" . time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/media/'), $file_name);
            $attachment = $file_name;
        }

        DB::beginTransaction();

        $sender_id = Auth::id();

        //Create Message
        $message                  = new Message();
        $message->message         = $request->message;
        $message->attachment      = $attachment;
        $message->user_id         = $sender_id;
        $message->conversation_id = $request->conversation_id;
        $message->save();

        $message->conversation->touch();
		
		//Send Email Notifications
		Overrider::load("Settings");
		$receiver_id = $message->conversation->sender_id != $message->user_id ? $message->conversation->sender_id : $message->conversation->receiver_id;
		$user = User::find($receiver_id);
		//$delay = now()->addMinutes(2);
		
		try{
			$user->notify(new \App\Notifications\MessageNotification($message));
		}catch(\Exception $e){
			//Nothing
		}
		
        DB::commit();

        return back()->with('success', _lang('Message Send Sucessfully'));

    }

    /*  Remove Message By Id */
    public function remove($message_id){
        $user_id = Auth::id();
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

        return back()->with('success', _lang('Message Removed'));             
    }


    public function bulk_action(Request $request){
         $user_id = Auth::id();
         
         if ( $request->action == 'mark_as_read' ){
             Message::join('conversations','messages.conversation_id','conversations.id')
                              ->whereRaw("(conversations.sender_id = $user_id OR conversations.receiver_id = $user_id)")
                              ->where('messages.user_id','!=',$user_id)
                              ->whereIn('messages.conversation_id', $request->conversation_id)
                              ->select('messages.*')
                              ->update(['is_seen' => 1]);
         } else if ( $request->action == 'move_to_trash' ){
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

         return back(); 
    }

}
