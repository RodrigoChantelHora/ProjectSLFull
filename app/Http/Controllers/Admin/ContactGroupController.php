<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Contact;
use App\Models\Admin\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ContactGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():Response
    {
        $groups = ContactGroup::paginate(10);
        $countGroups = ContactGroup::count();

        return Inertia::render('Admin/Contacts/Groups/GroupIndex', [
            'groups'    => $groups,
            'countGroups' => $countGroups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():Response
    {
        $groups = ContactGroup::paginate(10);
        $countGroups = ContactGroup::count();

        return Inertia::render('Admin/Contacts/Groups/GroupCreate', [
            'groups'    => $groups,
            'countGroups' => $countGroups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ContactGroup::create([
            'name'      => $request->name,
            'status'    => $request->status,
            'token'     => Str::random(64),
            'created_user' => Auth::user()->id,
        ]);

        return redirect()->route('contactGroup.index')->with('success', 'Grupo: ' . $request->name . ', cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactGroup $contactGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactGroup $contactGroup, $id, $token)
    {
        $group = ContactGroup::where('id', $id)->where('token', $token)->first();
        $countGroups = ContactGroup::count();

        return Inertia::render('Admin/Contacts/Groups/GroupEdit', [
            'group'    => $group,
            'countGroups' => $countGroups,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactGroup $contactGroup)
    {
        ContactGroup::where('id', $request->id)->where('token', $request->token)->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactGroup $contactGroup, $id, $token)
    {
        if(!ContactGroup::find($id)) {
            return redirect()->back()->with('error', 'Não foi possível identificar grupo a ser excluído.');
        }
        Contact::where('group_id', $id)->update([
            'group_id' => null
        ]);

        ContactGroup::where('id', $id)->where('token', $token)->delete();

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $query = ContactGroup::query();
        if($request->has('search') && !empty($request->search)){
            $term = $request->search;

            $query->where('name', 'LIKE', "%{$term}%");
        }

        $groups = $query->paginate(10);

        return Inertia::render('Admin/Contacts/Groups/GroupIndex', [
            'groups'    => $groups,
            'countGroups' => $groups->total(),
        ]);
    }

}
