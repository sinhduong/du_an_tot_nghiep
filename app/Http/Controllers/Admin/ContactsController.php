<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactsController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:contacts_list')->only(['index']);
        $this->middleware('permission:contacts_detail')->only(['show']);
        $this->middleware('permission:contacts_reply')->only(['reply']);
    }
    public function index()
    {
        $contacts = Contacts::latest()->paginate(10);
        return view('admins.contacts.index', compact('contacts'));
    }

    public function show(Contacts $contact)
    {
        return view('admins.contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contacts $contact)
    {
        $request->validate([
            'reply_content' => 'required|string',
        ]);

        try {
            Mail::raw($request->reply_content, function ($message) use ($contact) {
                $message->to($contact->email)
                    ->subject('Phản hồi từ Admin - ' . $contact->title)
                    ->from('admin@example.com', 'Admin');
            });

            $contact->update(['status' => 'approved']);

            return redirect()->route('admin.contacts.show', $contact->id)
                ->with('success', 'Phản hồi đã được gửi qua email thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage());
        }
    }
}
