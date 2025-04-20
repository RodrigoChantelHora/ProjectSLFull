<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Contact;
use App\Models\Admin\ContactGroup;
use App\Models\Admin\EmailStatus;
use App\Models\Admin\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class EmailStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $failedJobs = DB::table('failed_jobs')
        ->orderBy('id', 'desc') // Ordena pela data de falha, mais recente primeiro
        ->paginate(10)
        ->map(function ($job) {
            $payload = json_decode($job->payload, true);

            return [
                'id'         => $job->id,
                'uuid'       => $job->uuid,
                'connection' => $job->connection,
                'queue'      => $job->queue,
                'exception'  => $job->exception,
                'failed_at'  => $job->failed_at,
                'displayName'=> $payload['displayName'] ?? null,
                'jobName'    => $payload['data']['commandName'] ?? null,
                'rawPayload' => $payload,
            ];
        });

        $pendingJobs = DB::table('jobs')->orderBy('id', 'desc')->paginate(10)
        ->map(function ($jobPending) {

            return [
                'id'        => $jobPending->id,
                'queue'     => $jobPending->queue,
                'payload'   => $jobPending->payload,
            ];
        });

        $batchesJobs = DB::table('job_batches')->orderBy('id', 'desc')->paginate(10)
        ->map( function ($jobBatches) {
            return [
                'name'      => $jobBatches->name,
                'total_jobs'    => $jobBatches->total_jobs,
                'pending_jobs' => $jobBatches->pending_jobs,
                'failed_jobs'   => $jobBatches->failed_jobs,
            ];
        });

        return Inertia::render('Admin/EmailStatus/EmailStatusIndex', [
            'pendingJobs' => $pendingJobs,
            'failedJobs'  => $failedJobs,
            'batches'     => $batchesJobs,
            'pendingJobsTotal' => DB::table('jobs')->count(),
            'failedJobsTotal'  => DB::table('failed_jobs')->count(),
            'batchesTotal'     => DB::table('job_batches')->count()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailStatus $emailStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailStatus $emailStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailStatus $emailStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailStatus $emailStatus)
    {
        //
    }
}
