<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sql;
    private $bindings;

    public function __construct($sql, $bindings)
    {
        $this->sql = $sql;
        $this->bindings = $bindings;
    }

    public function handle()
    {
        $products = Product::with(['subcategory'])
            ->fromSub(function ($query) {
                $query->fromRaw("({$this->sql})", $this->bindings);
            }, 'filtered_products')
            ->get();

        $csvFileName = 'products-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen(Storage::path('public/' . $csvFileName), 'w');

        // Add CSV headers
        fputcsv($handle, ['ID', 'Name', 'Description', 'Price', 'Subcategory']);

        foreach ($products as $product) {
            fputcsv($handle, [
                $product->id,
                $product->name,
                $product->description,
                $product->price,
                $product->subcategory->name ?? 'N/A'
            ]);
        }

        fclose($handle);
    }
}
