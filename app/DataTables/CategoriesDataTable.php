<?php

namespace App\DataTables;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoriesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param mixed $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()->query($query)
            ->filter(function ($query) {
                if ($search = request('search')['value'] ?? null) {
                    $query->where('c.description', 'like', "%{$search}%")
                          ->orWhere('c.id', 'like', "%{$search}%")
                          ->orHavingRaw('GROUP_CONCAT(DISTINCT i.item_name SEPARATOR ", ") LIKE ?', ["%{$search}%"]);
                }
            })
            ->addColumn('action', function ($row) {
                if ($row->deleted_at) {
                    return '
                        <form method="POST" action="' . route('categories.restore', $row->id) . '" style="display:inline-block;">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-sm btn-warning">Restore</button>
                        </form>
                    ';
                } else {
                    return '
                        <a href="' . route('categories.edit', $row->id) . '" class="btn btn-sm btn-primary me-1">Edit</a>
                        <form method="POST" action="' . route('categories.destroy', $row->id) . '" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this category?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                }
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        $categories = DB::table('categories as c')
            ->leftJoin('items as i', 'c.id', '=', 'i.category_id')
            ->leftJoin('item_stock as s', 'i.id', '=', 's.item_id')
            ->select(
                'c.id',
                'c.description',
                'c.deleted_at',
                DB::raw('COUNT(i.id) as item_count'),
                DB::raw('SUM(s.quantity) as total_item_stock'),
                DB::raw('GROUP_CONCAT(DISTINCT i.item_name SEPARATOR ", ") as item_names')
            )
            ->groupBy(
                'c.id',
                'c.description',
                'c.deleted_at'
            );

        return $categories;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(
                "<'row mb-3'<'col-md-6 d-flex align-items-center gap-2'B><'col-md-6 text-end'f>>" . // Buttons and search bar in one row
                "rt" . // Table
                "<'row mt-3'<'col-md-6'i><'col-md-6 text-end'p>>" // Info and pagination in one row
            )
            ->orderBy(1)
            ->parameters([
                'buttons' => ['export', 'print', 'reset', 'reload', 'pdf', 'excel'],
                'language' => [
                    'search' => '_INPUT_',
                    'searchPlaceholder' => 'Search categories...',
                    'paginate' => [
                        'previous' => '&laquo;',
                        'next' => '&raquo;',
                    ],
                ],
            ]);
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->title('Actions'),

            Column::make('id')
                ->title('Category ID')
                ->addClass('text-center'),

            Column::make('description')
                ->title('Category Description')
                ->searchable(true), 

            Column::make('item_count')
                ->title('Item Count')
                ->addClass('text-center')
                ->searchable(false),

            Column::make('total_item_stock')
                ->title('Total item_stock')
                ->addClass('text-center')
                ->searchable(false), 

            Column::make('item_names')
                ->title('Item Names')
                ->orderable(false)
                ->searchable(true), 
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Categories_' . date('YmdHis');
    }
}