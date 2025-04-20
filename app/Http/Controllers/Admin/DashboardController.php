<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Contact;
use App\Models\Admin\ContactGroup;
use App\Models\Admin\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $contacts = Contact::get();
        $contactActiveCount = $contacts->where('status', true)->count();
        $contactInactiveCount = $contacts->where('status', false)->count();

        $groups = ContactGroup::get();
        $groupActiveCount = $groups->where('status', true)->count();
        $groupInactiveCount = $groups->where('status', false)->count();

        $reports = Report::get();
        $reportActiveCount = $reports->where('status', true)->count();
        $reportInactiveCount = $reports->where('status', false)->count();

        return Inertia::render('Dashboard', [
            'contacts'            => $contacts,
            'contactActiveCount'  => $contactActiveCount,
            'contactInactiveCount'=> $contactInactiveCount,
            'groups'              => $groups,
            'groupActiveCount'    => $groupActiveCount,
            'groupInactiveCount'  => $groupInactiveCount,
            'reports'             => $reports,
            'reportActiveCount'   => $reportActiveCount,
            'reportInactiveCount' => $reportInactiveCount,
        ]);
    }
}
