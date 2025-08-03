<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')->get();
        return view('support.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);
        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);
        SupportTicket::create([
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('support-tickets.index')->with('success', 'تم إضافة تذكرة الدعم بنجاح');
    }

    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $validated = $request->validate([
            'admin_reply' => 'required|string',
        ]);
        $ticket->admin_reply = $validated['admin_reply'];
        $ticket->replied_at = now();
        $ticket->status = 'in_progress';
        $ticket->save();
        return redirect()->back()->with('success', 'تم الرد على التذكرة');
    }

    public function changeStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);
        $ticket->status = $validated['status'];
        $ticket->save();
        return redirect()->back()->with('success', 'تم تغيير حالة التذكرة');
    }

    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('support-tickets.index')->with('success', 'تم حذف التذكرة بنجاح');
    }
}
