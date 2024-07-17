<?php

namespace App\Http\Controllers;

use App\Models\MailConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailConfigController extends Controller
{
    public function store(Request $request)
    {
        $model = MailConfig::first();
        if ($model) {
            $model->mail_transport = $request->mail_transport;
            $model->mail_host = $request->mail_host;
            $model->mail_port = $request->mail_port;
            $model->mail_encryption = $request->mail_encryption;
            $model->mail_username = $request->mail_username;
            $model->mail_password = $request->mail_password;
            $model->mail_from = $request->mail_from;
            $model->save();
        } else {
            $model = new MailConfig();
            $model->mail_transport = $request->mail_transport;
            $model->mail_host = $request->mail_host;
            $model->mail_port = $request->mail_port;
            $model->mail_encryption = $request->mail_encryption;
            $model->mail_username = $request->mail_username;
            $model->mail_password = $request->mail_password;
            $model->mail_from = $request->mail_from;
            $model->save();
        }

        return response()->json([
            'status' => 200,
            'message' => "Configuration Saved."
        ]);
    }

    public function mail_sent(Request $request)
    {
        // Validation
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string',
                'attachment_file' => 'nullable|file|mimes:doc,png,jpeg,jpg,gif|max:2048'
            ]
        );

        // Validation error
        if ($validator->fails()) {
            return response()->json([
                'status' => '400',
                'errors' => $validator->errors(),
            ]);
        }

        // Mail data
        $messageContent = $request->message;
        $name = $request->name;
        $data['to_email'] = $request->email;
        $data['subject'] = "Practice mail sent with attachment";
        $data['userName'] = $name;
        $data['messageContent'] = $messageContent;

        // Send the email
        Mail::send('emails.test', $data, function ($message) use ($data, $request) {
            $message->to($data["to_email"])
                ->subject($data["subject"]);

            // Handle the attachment file
            if ($request->hasFile('attachment_file')) {
                $attachmentPath = $request->file('attachment_file')->getPathname();
                $attachmentName = $request->file('attachment_file')->getClientOriginalName();
                $attachmentMimeType = $request->file('attachment_file')->getMimeType();

                $message->attach($attachmentPath, [
                    'as' => $attachmentName,
                    'mime' => $attachmentMimeType,
                ]);
            }
        });

        return response()->json([
            'status' => 200,
            'message' => "Mail sent with attachment.",
        ]);
    }
}
