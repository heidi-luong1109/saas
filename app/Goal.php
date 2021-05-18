<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'goal_type',
        'start_date',
        'end_date',
        'display',
        'description',
        'created_by',
    ];

    public static $goalType = [
        'Invoice',
        'Payment',
        'Expense',
    ];

    public function target($type, $from, $to, $amount)
    {

        $total    = 0;
        $fromDate = $from . '-01';
        $toDate   = $to . '-01';
        if(\App\Goal::$goalType[$type] == 'Invoice')
        {
            $invoices = Invoice:: select('*')->where('created_by', \Auth::user()->creatorId())->where('issue_date', '>=', $fromDate)->where('issue_date', '<=', $toDate)->get();
            $total    = 0;
            foreach($invoices as $invoice)
            {
                $total += $invoice->getTotal();
            }
        }
        elseif(\App\Goal::$goalType[$type] == 'Payment')
        {
            $payments = Payment:: select('*')->where('created_by', \Auth::user()->creatorId())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->get();
            $total    = 0;

            foreach($payments as $payment)
            {
                $total += $payment->amount;
            }

        }
        elseif(\App\Goal::$goalType[$type] == 'Expense')
        {
            $expenses = Expense:: select('*')->where('created_by', \Auth::user()->creatorId())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->get();
            $total    = 0;

            foreach($expenses as $expense)
            {
                $total += $expense->amount;
            }

        }

        $data['percentage'] = ($total * 100) / $amount;
        $data['total']      = $total;

        return $data;
    }
}
