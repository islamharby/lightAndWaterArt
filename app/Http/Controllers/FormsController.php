<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact_us;
use App\MaintenanceQuotation;
use App\RequestQuotation;
use Validator;
use Socketlabs\SocketLabsClient;
use Socketlabs\Message\BasicMessage;
use Socketlabs\Message\EmailAddress;
use Socketlabs\Message\BulkMessage;
use Socketlabs\Message\BulkRecipient;
use App\Service;
use App\Product;
use Socketlabs\Message\Attachment;

class FormsController extends Controller
{
    public function contact_us(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $contact_us = new Contact_us;
        $contact_us->name = $request->name;
        $contact_us->email = $request->email;
        $contact_us->message = $request->message;
        $contact_us->save();

        $serverId = 29818;
        $injectionApiKey = "s8R7Tpf9B4Pgj6G5McZk";
        $client = new SocketLabsClient($serverId, $injectionApiKey);
        $message = new BasicMessage();
        $message->subject = 'Contact Us';

        $message->htmlBody = "<html>" . $request->message . " </html>";
        $message->from = new EmailAddress($request->email);

        // $message->addToAddress('safaa.saied@elabs-corp.com');
        $message->addToAddress('amira.hesham@light-water-art.com');
        $response = $client->send($message);
        return \response()->json(['message'=>'done']);
    }

    public function maintenance_quotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'product_id' => 'required|exists:products,id',
            'service_id' => 'required|exists:services,id',
            
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $maintenance = new MaintenanceQuotation;
        $maintenance->full_name = $request->full_name;
        $maintenance->phone_number = $request->phone_number;
        $maintenance->product_id = $request->product_id;
        $maintenance->service_id = $request->service_id;
        $maintenance->date_of_contract = $request->date_of_contract;
        $maintenance->type_of_complain = $request->type_of_complain;

        $fileNameWithExt = $request->file('file')->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('file')->getClientOriginalExtension();
        $fileNameToStore =  $fileName . '_' . time() . '.' . $extension;
        $file = $request->file;
        $file->move('Uploads/file/', $fileNameToStore);
        $maintenance->file = 'Uploads/file/' . $fileNameToStore;
        $maintenance->save();
        $service = Service::where('id', $request->service_id)->first();
        $product = Product::where('id', $request->product_id)->first();

        $service_name = $service->name;
        $product_name = $product->name;

        $serverId = 28435;
        $injectionApiKey = "Sk43PoAb9j8JCz5g2Z6L";
        $client = new SocketLabsClient($serverId, $injectionApiKey);

        $message = new BulkMessage();
        $message->subject = $request->full_name;
        $message->from =  new EmailAddress('noreply@light-and-water-art.com');

        $message->htmlBody = file_get_contents('./maintenance_quotation.html');

        // $recipient1 = new BulkRecipient('safaa.saied@elabs-corp.com', "Recipient #1");
        $recipient1 = new BulkRecipient('amira.hesham@light-water-art.com', "Recipient #1");

        $recipient1->addMergeData("full_name", $request->full_name);
        $recipient1->addMergeData("phone_number", $request->phone_number);
        $recipient1->addMergeData("product", ($product_name != null)?$product_name:' ');
        $recipient1->addMergeData("service", ($service_name != null)?$service_name:' ');
        $recipient1->addMergeData("date_of_contract", $request->date_of_contract);
        $recipient1->addMergeData("type_of_complain", $request->type_of_complain);
        $message->attachments[] = Attachment::createFromPath($maintenance->file);

        $message->addToAddress($recipient1);
        $response = $client->send($message);

        return \response()->json(['message'=>'done']);
    }

    public function request_quotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'email' => 'required|email',
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $maintenance = new RequestQuotation;
        $maintenance->full_name = $request->full_name;
        $maintenance->phone_number = $request->phone_number;
        $maintenance->email = $request->email;
        $maintenance->product_id = $request->product_id;
        $maintenance->type_client = $request->type_client;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        $product = Product::where('id', $request->product_id)->first();
        $product_name = $product->name;

        $serverId = 28435;
        $injectionApiKey = "Sk43PoAb9j8JCz5g2Z6L";
        $client = new SocketLabsClient($serverId, $injectionApiKey);

        $message = new BulkMessage();
        $message->subject = $request->full_name;
        $message->from =  new EmailAddress($request->email);

        $message->htmlBody = file_get_contents('./request_quotation.html');

        // $recipient1 = new BulkRecipient('safaa.saied@elabs-corp.com', "Recipient #1");
        $recipient1 = new BulkRecipient('amira.hesham@light-water-art.com', "Recipient #1");

        $recipient1->addMergeData("full_name", $request->full_name);
        $recipient1->addMergeData("phone_number", $request->phone_number);
        $recipient1->addMergeData("product", ($product_name != null)?$product_name:' ');
        $recipient1->addMergeData("email", $request->email);
        $recipient1->addMergeData("type_client", $request->type_client);
        $recipient1->addMergeData("notes", $request->notes);

        $message->addToAddress($recipient1);
        $response = $client->send($message);

        return \response()->json(['message'=>'done']);
    }
    public function get_all_contact()
    {
        $data = Contact_us::orderBy('id', 'asc')->get();
        return \response()->json(['data'=>$data]);
    }
    public function get_all_maintenance_quotation()
    {
        $data = MaintenanceQuotation::with('product')->with('service')->orderBy('id', 'asc')->get();
        return \response()->json(['data'=>$data]);
    }
    public function get_all_request_quotation()
    {
        $data = RequestQuotation::with('product')->orderBy('id', 'asc')->get();
        return \response()->json(['data'=>$data]);
    }

    public function action_contact(Request $request)
    {
        $data = Contact_us::where('id', $request->id)->first();
        if (!$data) {
            return \response()->json(['error'=>'not found'], 422);
        }
        $data->is_action = $request->is_action;
        $data->save();

        return \response()->json(['data'=>$data]);
    }
    public function action_maintenance_quotation(Request $request)
    {
        $data = MaintenanceQuotation::where('id', $request->id)->first();
        if (!$data) {
            return \response()->json(['error'=>'not found'], 422);
        }
        $data->is_action = $request->is_action;
        $data->save();
        return \response()->json(['data'=>$data]);
    }
    public function action_request_quotation(Request $request)
    {
        $data = RequestQuotation::where('id', $request->id)->first();
        if (!$data) {
            return \response()->json(['error'=>'not found'], 422);
        }
        $data->is_action = $request->is_action;
        $data->save();
        return \response()->json(['data'=>$data]);
    }
}
