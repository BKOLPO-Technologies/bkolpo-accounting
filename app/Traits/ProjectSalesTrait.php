<?php
namespace App\Traits;

use App\Models\Project;

trait ProjectSalesTrait
{
    public function getProjectSalesData($id, $fromDate, $toDate)
    {
        // Fetch the project with related models
        $project = Project::where('id', $id)
            ->with(['client', 'items', 'sales', 'purchases', 'receipts'])
            ->firstOrFail(); // Ensure the project exists

        // Filter purchases based on the provided dates
        $purchases = $project->purchases()
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get();

        // Calculate totals
        $totalAmount = $project->grand_total;
        $paidAmount = $project->paid_amount;
        $dueAmount = $totalAmount - $paidAmount;

        return compact('project', 'purchases', 'totalAmount', 'paidAmount', 'dueAmount');
    }
}
