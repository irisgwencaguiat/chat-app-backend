<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index($id)
    {
        $chats = Chat::where("room_id", $id)
            ->whereNull("deleted_at")
            ->get();

        return customResponse()
            ->data($chats)
            ->message("Chats successfully found.")
            ->success()
            ->generate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "room_id" => "required",
            "user_id" => "required",
            "message" => "required|string",
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $chat = Chat::create([
            "room_id" => (int) $request->input("room_id"),
            "user_id" => (int) $request->input("user_id"),
            "message" => $request->input("message"),
        ]);

        return customResponse()
            ->data(Chat::find($chat->id))
            ->message("Chat has been created.")
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $chat = Chat::where("id", $id)
            ->whereNull("deleted_at")
            ->get()
            ->first();

        return customResponse()
            ->data($chat)
            ->message("Chat successfully found.")
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $chat = Chat::find($id);
        if ($chat) {
            $chat->delete();
            return customResponse()
                ->data($chat)
                ->message("Chat successfully deleted.")
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message("Chat not found.")
            ->notFound()
            ->generate();
    }

    public function storeImage(Request $request)
    {
        $image = $request->file("image");
        $fileName = $image->getClientOriginalName();
        $image->storeAs("/images", $fileName, "s3");
        return customResponse()
            ->data($image)
            ->message("Image successfully created.")
            ->success()
            ->generate(); // test
    }
}
