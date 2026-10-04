<?php

namespace App\Exports;

use App\Exports\Sheets\MaintenanceSheet;
use App\Exports\Sheets\RepairSheet;
use App\Exports\Sheets\StockOpnameSheet;
use App\Exports\Sheets\MutationSheet;

use App\Models\Inventory;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventoryHistoryExport implements WithMultipleSheets
{
    protected $inventory;


    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }


    public function sheets(): array
    {
        return [

            new MaintenanceSheet(
                $this->inventory
            ),

            new RepairSheet(
                $this->inventory
            ),

            new StockOpnameSheet(
                $this->inventory
            ),

            new MutationSheet(
                $this->inventory
            ),

        ];
    }
}