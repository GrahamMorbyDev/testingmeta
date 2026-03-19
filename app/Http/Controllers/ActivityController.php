<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities with basic filters.
     *
     * Accepts optional query parameters: user_id, action, date_from, date_to
     *
     * If a Blade view resources/views/activities/index.blade.php exists it will be used,
     * otherwise JSON will be returned for API-style consumption.
     */
    public function index(Request $request)
    {
        $query = Activity::query()->with('user')->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $activities = $query->paginate(20)->appends($request->query());

        if (view()->exists('activities.index')) {
            return view('activities.index', compact('activities'));
        }

        return response()->json($activities);
    }

    /**
     * Store a newly created activity. Intended to be a simple, programmatic endpoint
     * for recording events, but also usable from forms.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'action' => 'required|string|max:255',
            'metadata' => 'nullable|array',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        $user = $request->user() ?? ($data['user_id'] ?? null);

        $activity = Activity::record($data['action'], $data['metadata'] ?? null, $user);

        if ($request->wantsJson()) {
            return response()->json($activity, 201);
        }

        return redirect()->back()->with('success', 'Activity recorded.');
    }
}
