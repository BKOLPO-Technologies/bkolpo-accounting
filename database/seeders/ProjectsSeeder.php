<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Project; // Ensure you import the Project model
use App\Models\ProjectItem; // Ensure you import the ProjectItem model
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    public function run()
    {
        // Loop through and create 3 projects with dynamic reference_no
        $projectsData = [
            [
                'project_name' => 'Project Titan',
                'project_location' => 'Location A',
                'project_coordinator' => 'John Doe',
                'client_id' => 1, // Assuming client with ID 1 exists
                'schedule_date' => '2025-06-01',
                'total_discount' => 500,
                'subtotal' => 10000,
                'transport_cost' => 200,
                'carrying_charge' => 150,
                'vat' => 0,
                'tax' => 0,
                'grand_total' => 2400,
                'paid_amount' => 0,
                'status' => 'pending',
                'project_type' => 'Running',
                'description' => 'This is the description for Project Titan.',
                'terms_conditions' => 'Terms and conditions for Project Titan.',
            ],
            [
                'project_name' => 'Project Everest',
                'project_location' => 'Location B',
                'project_coordinator' => 'Jane Smith',
                'client_id' => 2, // Assuming client with ID 2 exists
                'schedule_date' => '2025-07-01',
                'total_discount' => 700,
                'subtotal' => 15000,
                'transport_cost' => 300,
                'carrying_charge' => 180,
                'vat' => 0,
                'tax' => 0,
                'grand_total' => 2200,
                'paid_amount' => 0,
                'status' => 'pending',
                'project_type' => 'Running',
                'description' => 'This is the description for Project Everest.',
                'terms_conditions' => 'Terms and conditions for Project Everest.',
            ],
            [
                'project_name' => 'Project Horizon',
                'project_location' => 'Location C',
                'project_coordinator' => 'Mark Lee',
                'client_id' => 3, // Assuming client with ID 3 exists
                'schedule_date' => '2025-08-01',
                'total_discount' => 1000,
                'subtotal' => 20000,
                'transport_cost' => 500,
                'carrying_charge' => 250,
                'vat' => 0,
                'tax' => 0,
                'grand_total' => 1900,
                'paid_amount' => 0,
                'status' => 'pending',
                'project_type' => 'Running',
                'description' => 'This is the description for Project Horizon.',
                'terms_conditions' => 'Terms and conditions for Project Horizon.',
            ]
        ];
        

        foreach ($projectsData as $projectData) {
            // Generate a dynamic reference number for each project
            $randomNumber = rand(100000, 999999);
            $fullDate = now()->format('dmy'); // Current date in 'dmy' format
            $reference_no = 'BCL-PR-'.$fullDate.'-'.$randomNumber;

            // Add the reference_no to the project data
            $projectData['reference_no'] = $reference_no;

            // Create or update the project
            $project = Project::updateOrCreate(
                ['reference_no' => $reference_no],
                $projectData
            );

            // Create project items for the created project
            $this->createProjectItems($project);
        }
    }

    /**
     * Create project items for the given project
     * 
     * @param Project $project
     */
    private function createProjectItems(Project $project)
    {
        // Get unit IDs indexed by name
        $units = Unit::pluck('id', 'name')->toArray();
    
        // $itemsData = [
        //     [
        //         'items' => 'Item 1',
        //         'unit_id' => 1, // Get the unit ID or null if not found
        //         'unit_price' => 100,
        //         'quantity' => 10,
        //         'subtotal' => 1000,
        //         'discount' => 0,
        //         'total' => 1000,
        //         'project_id' => $project->id,
        //     ],
        //     [
        //         'items' => 'Item 2',
        //         'unit_id' => 2,
        //         'unit_price' => 200,
        //         'quantity' => 5,
        //         'subtotal' => 1000,
        //         'discount' => 0,
        //         'total' => 1000,
        //         'project_id' => $project->id,
        //     ],
        //     [
        //         'items' => 'Item 3',
        //         'unit_id' => 3,
        //         'unit_price' => 300,
        //         'quantity' => 3,
        //         'subtotal' => 900,
        //         'discount' => 0,
        //         'total' => 900,
        //         'project_id' => $project->id,
        //     ]
        // ];
    
        // foreach ($itemsData as $itemData) {
        //     ProjectItem::updateOrCreate(
        //         ['items' => $itemData['items'], 'project_id' => $project->id],
        //         $itemData
        //     );
        // }
        // Assign items based on project name or ID

        $itemsMap = [
            'Project Titan' => [
                ['items' => 1, 'unit_id' => 1, 'unit_price' => 120, 'quantity' => 8],
                ['items' => 2, 'unit_id' => 2, 'unit_price' => 90, 'quantity' => 12],
            ],
            'Project Everest' => [
                ['items' => 3, 'unit_id' => 3, 'unit_price' => 140, 'quantity' => 5],
                ['items' => 4, 'unit_id' => 4, 'unit_price' => 75, 'quantity' => 15],
            ],
            'Project Horizon' => [
                ['items' => 5, 'unit_id' => 5, 'unit_price' => 200, 'quantity' => 6],
                ['items' => 6, 'unit_id' => 6, 'unit_price' => 110, 'quantity' => 10],
            ],
        ];

        $projectItems = $itemsMap[$project->project_name] ?? [];

        foreach ($projectItems as $item) {
            $subtotal = $item['unit_price'] * $item['quantity'];
            $itemData = array_merge($item, [
                'subtotal' => $subtotal,
                'discount' => 0,
                'total' => $subtotal,
                'project_id' => $project->id,
            ]);

            ProjectItem::updateOrCreate(
                ['items' => $item['items'], 'project_id' => $project->id],
                $itemData
            );
        }

    }
    
}
