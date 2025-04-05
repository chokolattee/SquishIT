<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Stock;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;

class ItemStockImport implements ToCollection, WithHeadingRow, WithDrawings
{
    protected $drawings = [];
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.9',
            ],
        ]);
    }

    public function drawings()
    {
        return $this->drawings;
    }

    public function collection(Collection $rows)
    {
        $drawingMap = $this->mapDrawingsByRow();

        foreach ($rows as $index => $row) {
            $currentRow = $index + 2; // Excel data starts at row 2
            try {
                $this->validateRow($row, $currentRow);

                $category = Category::firstOrCreate(
                    ['description' => $row['category_name']],
                    ['created_at' => now(), 'updated_at' => now()]
                );

                $item = Item::create([
                    'item_name'   => $row['item_name'],
                    'description' => $row['description'] ?? null,
                    'cost_price'  => $row['cost_price'] ?? 0,
                    'sell_price'  => $row['sell_price'] ?? 0,
                    'category_id' => $category->id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                Stock::create([
                    'item_id'  => $item->id,
                    'quantity' => $row['quantity'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Process embedded drawings
                if (isset($drawingMap[$currentRow])) {
                    foreach ($drawingMap[$currentRow] as $drawing) {
                        $this->processDrawing($drawing, $item->id);
                    }
                }

                // Process image URLs
                if (!empty($row['image_path'])) {
                    $this->processImageUrls($row['image_path'], $item->id);
                }
            } catch (Exception $e) {
                Log::error("Import error on row {$currentRow}: " . $e->getMessage());
                continue;
            }
        }
    }

    protected function mapDrawingsByRow()
    {
        $drawingMap = [];
        foreach ($this->drawings as $drawing) {
            if ($drawing instanceof Drawing && $drawing->getCoordinates()) {
                $coordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($drawing->getCoordinates());
                $rowNumber = $coordinate[1];
                $drawingMap[$rowNumber][] = $drawing;
            }
        }
        return $drawingMap;
    }

    protected function validateRow($row, $currentRow)
    {
        if (empty($row['item_name'])) {
            throw new Exception("Item name is required on row {$currentRow}");
        }

        if (empty($row['category_name'])) {
            throw new Exception("Category name is required on row {$currentRow}");
        }
    }

    protected function processDrawing(Drawing $drawing, $itemId)
    {
        try {
            $extension = $drawing->getExtension() ?: 'jpg';
            $hashName = Str::random(40) . '.' . $extension;
            $storagePath = 'public/images/' . $hashName;

            Storage::put($storagePath, file_get_contents($drawing->getPath()));

            ItemImage::create([
                'item_id'    => $itemId,
                'image_path' => $storagePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Exception $e) {
            Log::error("Failed to process drawing for item {$itemId}: " . $e->getMessage());
        }
    }

    protected function processImageUrls($imagePaths, $itemId)
    {
        $urls = explode(',', $imagePaths);

        foreach ($urls as $url) {
            $url = trim($url);

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                Log::warning("Invalid URL skipped: {$url}");
                continue;
            }

            try {
                $response = $this->client->get($url);

                if ($response->getStatusCode() === 200) {
                    $imageContent = $response->getBody()->getContents();

                    $contentType = $response->getHeaderLine('Content-Type');
                    $extension = $this->getExtensionFromContentType($contentType) ?: 'jpg';

                    $hashName = Str::random(40) . '.' . $extension;
                    $storagePath = 'public/images/' . $hashName;

                    Storage::put($storagePath, $imageContent);

                    ItemImage::create([
                        'item_id'    => $itemId,
                        'image_path' => $storagePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (RequestException $e) {
                Log::error("Failed to download image from {$url}: " . $e->getMessage());
            } catch (Exception $e) {
                Log::error("Error processing image URL {$url}: " . $e->getMessage());
            }
        }
    }

    protected function getExtensionFromContentType($contentType)
    {
        $mappings = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];

        return $mappings[$contentType] ?? null;
    }
}