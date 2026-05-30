<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Recurrence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use PHPUnit\Framework\Attributes\Ticket;

class DashboardController extends Controller
{
    public function index()
    {


        // Get the authenticated user
        $user = auth()->user();

        // Get the latest todos created by the authenticated user
        $today = now()->toDateString(); //getting todays date

        $todos = Todo::where('user_id', $user->id)
            ->where('completed', false)
            ->where(function ($query) use ($today) {
                $query->whereDate('due_date', '>=', $today)
                    ->orWhereNull('due_date');
            })->orderByRaw("CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC")
            ->latest()->limit(5)
            ->get();

        foreach ($todos as $todo) {
            // Use parent_id if it exists, otherwise use todo's id
            $recId = !empty($todo->parent_id) ? $todo->parent_id : $todo->id;

            // Retrieve recurrence based on the recId
            $recurrence = Recurrence::where('task_id', $recId)->first(); // find() instead of findOrFail() to avoid exception if not found

            // Assign rec_id based on whether recurrence exists
            $todo->rec_id = $recurrence ? $recurrence->id : 0;
        }


        // Get the completed todos
        $completedTodos = Todo::where('user_id', $user->id)->where('completed', true)->where(function ($query) use ($today) {
            $query->whereDate('due_date', '>=', $today)->orWhereNull('due_date');
        })->orderByRaw("CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC")->latest()->limit(5)->get();

        foreach ($completedTodos as $todo) {
            // Use parent_id if it exists, otherwise use todo id
            $recId = !empty($todo->parent_id) ? $todo->parent_id : $todo->id;

            // Retrieve recurrence based on the recId
            $recurrence = Recurrence::where('task_id', $recId)->first(); // find() instead of findOrFail() to avoid exception if not found

            // Assign rec_id based on whether recurrence exists
            $todo->rec_id = $recurrence ? $recurrence->id : 0;
        }

        // $today = date('Y-m-d');
        // $yesterday = date('Y-m-d', strtotime('-1 day'));

        // //sale
        // $sale = getValues('/reports/day_totals_branch_payment?from_date=' . $yesterday . '&to_date=' . $today . '&pstt_id=2&beb_id=1');
        // if (isset($sale[1]) && $sale[1]->pmt_doc_date == $today) {
        //     $todayTotal = $sale[1]->days_total ?? 0;
        // } elseif (isset($sale[0]) && $sale[0]->pmt_doc_date == $today) {
        //     $todayTotal = $sale[0]->days_total ?? 0;
        // } else {
        //     $todayTotal = 0;
        // }

        // if (isset($sale[0]) && $sale[0]->pmt_doc_date == $yesterday) {
        //     $yesterdayTotal = $sale[0]->days_total;
        // } else {
        //     $yesterdayTotal = 0;
        // }

        // if ($yesterdayTotal != 0) {
        //     $percentageChange = (($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100;
        // } else {
        //     // Avoid division by zero, show 100% if yesterday was 0 and today is not
        //     $percentageChange = $todayTotal > 0 ? 100 : 0;
        // }

        // // Log::info('Sale Percentage Change: ' . round($percentageChange, 2) . '%');
        // $report = [];
        // $report['sale'] = $todayTotal;
        // $report['saleper'] = round($percentageChange, 2);

        // //saleOrder
        // $saleOrder = getValues('/reports/day_totals_branch_payment?from_date=' . $yesterday . '&to_date=' . $today . '&pstt_id=5&beb_id=1');
        // if (isset($saleOrder[1]) && $saleOrder[1]->pmt_doc_date == $today) {
        //     $todayTotal = $saleOrder[1]->days_total ?? 0;
        // } elseif (isset($saleOrder[0]) && $saleOrder[0]->pmt_doc_date == $today) {
        //     $todayTotal = $saleOrder[0]->days_total ?? 0;
        // } else {
        //     $todayTotal = 0;
        // }

        // if (isset($saleOrder[0]) && $saleOrder[0]->pmt_doc_date == $yesterday) {
        //     $yesterdayTotal = $saleOrder[0]->days_total;
        // } else {
        //     $yesterdayTotal = 0;
        // }
        // if ($yesterdayTotal != 0) {
        //     $percentageChange = (($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100;
        // } else {
        //     $percentageChange = $todayTotal > 0 ? 100 : 0;
        // }
        // $report['saleorder'] = $todayTotal;
        // $report['saleorderper'] = round($percentageChange, 2);


        // //purchase
        // $purchase = getValues('/reports/day_totals_branch_payment?from_date=' . $yesterday . '&to_date=' . $today . '&pstt_id=1&beb_id=1');
        // if (isset($purchase[1]) && $purchase[1]->pmt_doc_date == $today) {
        //     $todayTotal = $purchase[1]->days_total ?? 0;
        // } elseif (isset($purchase[0]) && $purchase[0]->pmt_doc_date == $today) {
        //     $todayTotal = $purchase[0]->days_total ?? 0;
        // } else {
        //     $todayTotal = 0;
        // }

        // if (isset($purchase[0]) && $purchase[0]->pmt_doc_date == $yesterday) {
        //     $yesterdayTotal = $purchase[0]->days_total;
        // } else {
        //     $yesterdayTotal = 0;
        // }
        // if ($yesterdayTotal != 0) {
        //     $percentageChange = (($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100;
        // } else {
        //     $percentageChange = $todayTotal > 0 ? 100 : 0;
        // }
        // $report['purchase'] = $todayTotal;
        // $report['purchaseper'] = round($percentageChange, 2);

        // //purchaseOrder
        // $purchaseOrder = getValues('/reports/day_totals_branch_payment?from_date=' . $yesterday . '&to_date=' . $today . '&pstt_id=6&beb_id=1');
        // if (isset($purchaseOrder[1]) && $purchaseOrder[1]->pmt_doc_date == $today) {
        //     $todayTotal = $purchaseOrder[1]->days_total ?? 0;
        // } elseif (isset($purchaseOrder[0]) && $purchaseOrder[0]->pmt_doc_date == $today) {
        //     $todayTotal = $purchaseOrder[0]->days_total ?? 0;
        // } else {
        //     $todayTotal = 0;
        // }

        // if (isset($purchaseOrder[0]) && $purchaseOrder[0]->pmt_doc_date == $yesterday) {
        //     $yesterdayTotal = $purchaseOrder[0]->days_total;
        // } else {
        //     $yesterdayTotal = 0;
        // }
        // if ($yesterdayTotal != 0) {
        //     $percentageChange = (($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100;
        // } else {
        //     $percentageChange = $todayTotal > 0 ? 100 : 0;
        // }
        // $report['purchaseorder'] = $todayTotal;
        // $report['purchaseorderper'] = round($percentageChange, 2);

        // //expense
        // $expense = getValues('/reports/day_totals_branch_payment?from_date=' . $yesterday . '&to_date=' . $today . '&pstt_id=11&beb_id=1');
        // if (isset($expense[1]) && $expense[1]->pmt_doc_date == $today) {
        //     $todayTotal = $expense[1]->days_total ?? 0;
        // } else if (isset($expense[0]) && $expense[0]->pmt_doc_date == $today) {
        //     $todayTotal = $expense[0]->days_total ?? 0;
        // } else {
        //     $todayTotal = 0;
        // }

        // if (isset($expense[0]) && $expense[0]->pmt_doc_date == $yesterday) {
        //     $yesterdayTotal = $expense[0]->days_total;
        // } else {
        //     $yesterdayTotal = 0;
        // }
        // if ($yesterdayTotal != 0) {
        //     $percentageChange = (($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100;
        // } else {
        //     $percentageChange = $todayTotal > 0 ? 100 : 0;
        // }
        // $report['expense'] = $todayTotal;
        // $report['expenseper'] = round($percentageChange, 2);



        // $totalTickets = Ticket::count(); // Count of all tickets
        // $openTickets = Ticket::where('status', 'Open')->count();
        // $closedTickets = Ticket::where('status', 'Closed')->count();
        // $processingTickets = Ticket::where('status', 'Processing')->count();
        // $onHoldTickets = Ticket::where('status', 'On Hold')->count();



        // $totalAppliedPendingTenders = Tender::whereIn('status', ['Applied', 'Pending'])->count();
        // $appliedTenders = Tender::where('status', 'Applied')->count();
        // $pendingTenders = Tender::where('status', 'Pending')->count();


        // activity()->withProperties(['todo' => $todos, 'completed todo' => $completedTodos, 'totaltickets' => $totalTickets, 'reports' => $report],)->event('access')->log('accessed dashboard page');
        // return view('backend.dashboard', compact('todos', 'completedTodos', 'totalTickets', 'openTickets', 'closedTickets', 'processingTickets', 'onHoldTickets', 'report', 'totalAppliedPendingTenders', 'appliedTenders', 'pendingTenders'));

        return view('backend.dashboard',compact('todos','completedTodos'));
    }
}
