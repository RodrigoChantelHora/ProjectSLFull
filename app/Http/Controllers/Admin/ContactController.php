<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetContactRequest;
use App\Models\Admin\Contact;
use App\Models\Admin\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():Response
    {
        $contacts = Contact::with('group')->paginate(10);
        $countContacts = Contact::count();

        return Inertia::render('Admin/Contacts/Contacts/ContactIndex', [
            'contacts'    => $contacts,
            'countContacts' => $countContacts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contacts = Contact::with('group')->paginate(10);
        $countContacts = Contact::count();
        $groups = ContactGroup::get();

        return Inertia::render('Admin/Contacts/Contacts/ContactCreate', [
            'contacts'    => $contacts,
            'countContacts' => $countContacts,
            'groups'        => $groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SetContactRequest $request)
    {
        $newContact = Contact::create([
            'name'          => $request->name,
            'status'        => $request->status,
            'email'         => $request->email,
            'whatsapp'      => $request->contact,
            'group_id'      => $request->group,
            'description'   => $request->description,
            'token'         => Str::random(64),
            'created_user'  => Auth::user()->id,
        ]);

        $newContact->save();

        return redirect()->route('contact.index')->with('success', 'Contato ' . $request->name . ' cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact, $id, $token)
    {
        $contact = Contact::where('id', $id)->where('token', $token)->first();
        $countContacts = Contact::count();
        $groups = ContactGroup::get();

        return Inertia::render('Admin/Contacts/Contacts/ContactEdit', [
            'contact'    => $contact,
            'countContacts' => $countContacts,
            'groups'        => $groups,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SetContactRequest $request, Contact $contact)
    {
        Contact::where('id', $request->id)->where('token', $request->token)->update([
            'name'          => $request->name,
            'status'        => $request->status,
            'email'         => $request->email,
            'whatsapp'      => $request->contact,
            'group_id'      => $request->group,
            'description'   => $request->description,
        ]);

        return redirect()->back()->with('success', 'Contato atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact, $id, $token)
    {
        Contact::where('id', $id)->where('token', $token)->delete();

        return redirect()->back()->with('success', $id . ' deletado com sucesso!');
    }

    public function search(Request $request)
    {
        $contacts = Contact::with('group')->paginate(10);

        if($request->search) {
            $contacts = Contact::with('group')
            ->where('name', 'like', '%' . $request->search . '%')
            ->paginate(10);
        }

        return Inertia::render('Admin/Contacts/Contacts/ContactIndex', [
            'contacts'    => $contacts,
            'countContacts' => $contacts->total(),
        ]);
    }
}
