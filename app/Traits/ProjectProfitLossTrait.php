<?php

namespace App\Traits;

use App\Models\Project;

trait ProjectProfitLossTrait
{
    /**
     * Get Project Profit & Loss for a specific date range.
     *
     * @param  string  $fromDate
     * @param  string  $toDate
     * @return \Illuminate\Support\Collection
     */
    public function getProjectProfitLossData($fromDate, $toDate)
    {
        // Fetch projects with purchases data (we are not using sales data)
        $projects = Project::with(['purchases' => function ($query) use ($fromDate, $toDate) {
            $query->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                // Filter purchases by the date range
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->when(!$fromDate || !$toDate || $toDate == now()->format('Y-m-d'), function ($query) use ($toDate) {
                // If fromDate or toDate is not provided or toDate is today, apply orWhere with greater than condition
                $query->orWhere('created_at', '>', $toDate);
            });
        }])
        ->get()
        ->map(function ($project) {
            // Calculate total purchases for each project
            $totalPurchases = $project->purchases->sum('total'); // Sum of total purchases

            // The grand total of the project is considered as sales (from the project table itself)
            $totalSales = $project->grand_total; // Grand total directly from the project table
            
            // Calculate profit or loss
            $project->total_sales = $totalSales;
            $project->total_purchases = $totalPurchases;
            $project->profit_loss = $totalSales - $totalPurchases;
            
            return $project;
        });


        // dd($projects);

        // Total Sales, Purchases, and Net Profit/Loss for all projects
        $totalSales = $projects->sum('total_sales');
        $totalPurchases = $projects->sum('total_purchases');
        $netProfitLoss = $totalSales - $totalPurchases;

        return [
            'projects' => $projects,
            'totalSales' => $totalSales,
            'totalPurchases' => $totalPurchases,
            'netProfitLoss' => $netProfitLoss,
        ];
    }
}
