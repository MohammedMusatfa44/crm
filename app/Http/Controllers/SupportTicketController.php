<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupportTicketController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Super admin sees all tickets, others see only their own
        if ($user->hasRole('super_admin')) {
            $tickets = SupportTicket::with('user')->latest()->get();
        } else {
            $tickets = SupportTicket::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        Log::info('SupportTicketController@index', [
            'user_id' => $user->id,
            'user_role' => $user->getRoleNames()->first(),
            'tickets_count' => $tickets->count()
        ]);

        return view('support.index', compact('tickets'));
    }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $ticket = SupportTicket::with('user')->findOrFail($id);
        $ticketCreator = $ticket->user;

        Log::info('SupportTicketController@show - Attempting to view ticket', [
            'current_user_id' => $user->id,
            'current_user_role' => $user->getRoleNames()->first(),
            'ticket_id' => $ticket->id,
            'ticket_creator_id' => $ticketCreator->id,
            'ticket_creator_role' => $ticketCreator->getRoleNames()->first(),
            'ticket_creator_created_by' => $ticketCreator->created_by
        ]);

        // Check if user has permission to view this ticket based on hierarchical system
        $canView = false;
        $reason = '';

        if ($user->hasRole('super_admin')) {
            // Super admin can view any ticket
            $canView = true;
            $reason = 'super_admin_can_view_all';
        } elseif ($user->hasRole('admin')) {
            // Admin can view tickets created by employees they created
            if ($ticketCreator->hasRole('employee') && $ticketCreator->created_by === $user->id) {
                $canView = true;
                $reason = 'admin_viewing_their_employee_ticket';
            } else {
                $canView = false;
                $reason = 'admin_cannot_view_this_ticket';
            }
        } elseif ($user->hasRole('employee')) {
            // Employees can only view their own tickets
            if ($ticket->user_id === $user->id) {
                $canView = true;
                $reason = 'employee_viewing_own_ticket';
            } else {
                $canView = false;
                $reason = 'employee_cannot_view_other_tickets';
            }
        }

        Log::info('SupportTicketController@show - Permission check result', [
            'can_view' => $canView,
            'reason' => $reason
        ]);

        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض هذه التذكرة'
            ], 403);
        }

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

        $ticket = SupportTicket::create([
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'user_id' => Auth::id(),
        ]);

        Log::info('SupportTicketController@store - Ticket created', [
            'ticket_id' => $ticket->id,
            'user_id' => $ticket->user_id,
            'subject' => $ticket->subject
        ]);

        return redirect()->route('support-tickets.index')->with('success', 'تم إضافة تذكرة الدعم بنجاح');
    }

    public function reply(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $ticket = SupportTicket::with('user')->findOrFail($id);
        $ticketCreator = $ticket->user;

        Log::info('SupportTicketController@reply - Attempting reply', [
            'current_user_id' => $user->id,
            'current_user_role' => $user->getRoleNames()->first(),
            'ticket_id' => $ticket->id,
            'ticket_creator_id' => $ticketCreator->id,
            'ticket_creator_role' => $ticketCreator->getRoleNames()->first(),
            'ticket_creator_created_by' => $ticketCreator->created_by
        ]);

        // Check if user has permission to reply to this ticket based on hierarchical system
        $canReply = false;
        $reason = '';

        if ($user->hasRole('super_admin')) {
            // Super admin can reply to any ticket
            $canReply = true;
            $reason = 'super_admin_can_reply_to_all';
        } elseif ($user->hasRole('admin')) {
            // Admin can only reply to tickets created by employees they created
            if ($ticketCreator->hasRole('employee') && $ticketCreator->created_by === $user->id) {
                $canReply = true;
                $reason = 'admin_replying_to_their_employee';
            } else {
                $canReply = false;
                $reason = 'admin_cannot_reply_to_this_ticket';
            }
        } elseif ($user->hasRole('employee')) {
            // Employees cannot reply to any tickets
            $canReply = false;
            $reason = 'employee_cannot_reply_to_tickets';
        }

        Log::info('SupportTicketController@reply - Permission check result', [
            'can_reply' => $canReply,
            'reason' => $reason
        ]);

        if (!$canReply) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية للرد على هذه التذكرة');
        }

        $validated = $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $ticket->update([
            'admin_reply' => $validated['admin_reply'],
            'replied_at' => now(),
        ]);

        Log::info('SupportTicketController@reply - Reply added successfully', [
            'ticket_id' => $ticket->id,
            'replied_by' => $user->id,
            'reply_reason' => $reason
        ]);

        return redirect()->back()->with('success', 'تم إضافة الرد بنجاح');
    }

    public function changeStatus(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $ticket = SupportTicket::with('user')->findOrFail($id);
        $ticketCreator = $ticket->user;

        Log::info('SupportTicketController@changeStatus - Attempting to change status', [
            'current_user_id' => $user->id,
            'current_user_role' => $user->getRoleNames()->first(),
            'ticket_id' => $ticket->id,
            'ticket_creator_id' => $ticketCreator->id,
            'ticket_creator_role' => $ticketCreator->getRoleNames()->first(),
            'ticket_creator_created_by' => $ticketCreator->created_by
        ]);

        // Check if user has permission to change status based on hierarchical system
        $canChangeStatus = false;
        $reason = '';

        if ($user->hasRole('super_admin')) {
            // Super admin can change status of any ticket
            $canChangeStatus = true;
            $reason = 'super_admin_can_change_all';
        } elseif ($user->hasRole('admin')) {
            // Admin can change status of tickets created by employees they created
            if ($ticketCreator->hasRole('employee') && $ticketCreator->created_by === $user->id) {
                $canChangeStatus = true;
                $reason = 'admin_changing_their_employee_ticket';
            } else {
                $canChangeStatus = false;
                $reason = 'admin_cannot_change_this_ticket';
            }
        } elseif ($user->hasRole('employee')) {
            // Employees cannot change status of any tickets
            $canChangeStatus = false;
            $reason = 'employee_cannot_change_tickets';
        }

        Log::info('SupportTicketController@changeStatus - Permission check result', [
            'can_change_status' => $canChangeStatus,
            'reason' => $reason
        ]);

        if (!$canChangeStatus) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية لتغيير حالة هذه التذكرة');
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket->update([
            'status' => $validated['status']
        ]);

        Log::info('SupportTicketController@changeStatus - Status changed successfully', [
            'ticket_id' => $ticket->id,
            'changed_by' => $user->id,
            'new_status' => $ticket->status,
            'change_reason' => $reason
        ]);

        return redirect()->back()->with('success', 'تم تغيير حالة التذكرة');
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $ticket = SupportTicket::with('user')->findOrFail($id);
        $ticketCreator = $ticket->user;

        Log::info('SupportTicketController@destroy - Attempting to delete ticket', [
            'current_user_id' => $user->id,
            'current_user_role' => $user->getRoleNames()->first(),
            'ticket_id' => $ticket->id,
            'ticket_creator_id' => $ticketCreator->id,
            'ticket_creator_role' => $ticketCreator->getRoleNames()->first(),
            'ticket_creator_created_by' => $ticketCreator->created_by
        ]);

        // Check if user has permission to delete based on hierarchical system
        $canDelete = false;
        $reason = '';

        if ($user->hasRole('super_admin')) {
            // Super admin can delete any ticket
            $canDelete = true;
            $reason = 'super_admin_can_delete_all';
        } elseif ($user->hasRole('admin')) {
            // Admin can delete tickets created by employees they created
            if ($ticketCreator->hasRole('employee') && $ticketCreator->created_by === $user->id) {
                $canDelete = true;
                $reason = 'admin_deleting_their_employee_ticket';
            } else {
                $canDelete = false;
                $reason = 'admin_cannot_delete_this_ticket';
            }
        } elseif ($user->hasRole('employee')) {
            // Employees cannot delete any tickets
            $canDelete = false;
            $reason = 'employee_cannot_delete_tickets';
        }

        Log::info('SupportTicketController@destroy - Permission check result', [
            'can_delete' => $canDelete,
            'reason' => $reason
        ]);

        if (!$canDelete) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية لحذف هذه التذكرة');
        }

        $ticket->delete();

        Log::info('SupportTicketController@destroy - Ticket deleted successfully', [
            'ticket_id' => $id,
            'deleted_by' => $user->id,
            'delete_reason' => $reason
        ]);

        return redirect()->back()->with('success', 'تم حذف التذكرة بنجاح');
    }
}
