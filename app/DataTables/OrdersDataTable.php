<?php

namespace App\DataTables;

use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->query($query)
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route('admin.orderDetails', $row->id) . '" class="btn details btn-primary">Details</a>';
                return $actionBtn;
            })
            ->filterColumn('lname', function ($query, $keyword) {
                $query->where('c.lname', 'like', "%{$keyword}%");
            })
            ->filterColumn('fname', function ($query, $keyword) {
                $query->where('c.fname', 'like', "%{$keyword}%");
            })
            ->filterColumn('addressline', function ($query, $keyword) {
                $query->where('c.addressline', 'like', "%{$keyword}%");
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('s.status', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        $orders = DB::table('customers as c')
    ->join('orders as o', 'o.customer_id', '=', 'c.id')
    ->join('order_item as oi', 'o.id', '=', 'oi.order_id')
    ->join('items as i', 'oi.item_id', '=', 'i.id')
    ->join('shippings as sh', 'o.shipping_id', '=', 'sh.id')
    ->join('statuses as s', 'o.status_id', '=', 's.id')
    ->select(
        'o.id',
        'c.fname',
        'c.lname',
        'c.addressline',
        'o.date_placed',
        'o.date_shipped',
        'o.date_delivered',
        's.status',
        'sh.rate',
        DB::raw("SUM((oi.quantity * i.sell_price)+sh.rate) as total")
    )
    ->groupBy(
        'o.id',
        'c.fname',
        'c.lname',
        'c.addressline',
        'o.date_placed',
        'o.date_shipped',   
        'o.date_delivered',
        's.status',
        'sh.rate'
    );


        return $orders;
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reviews-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(
                "<'row mb-3'<'col-md-6 d-flex align-items-center gap-2'B><'col-md-6 text-end'f>>" . // Buttons and search bar in one row
                "rt" . // Table
                "<'row mt-3'<'col-md-6'i><'col-md-6 text-end'p>>" // Info and pagination in one row
            )
            ->orderBy(1)
            ->parameters([
                'buttons' =>  ['export', 'print', 'reset', 'reload', 'pdf', 'excel'],
                'language' => [
                    'search' => '_INPUT_',
                    'searchPlaceholder' => 'Search order...',
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
                ->width(60)
                ->addClass('text-center'),
                ['data' => 'id', 'name' => 'o.id', 'title' => 'Order ID'],
                ['data' => 'lname', 'name' => 'c.lname', 'title' => 'Last Name'],
                ['data' => 'fname', 'name' => 'c.fname', 'title' => 'First Name'],
                ['data' => 'addressline', 'name' => 'c.addressline', 'title' => 'Address'],
                ['data' => 'date_placed', 'name' => 'o.date_placed', 'title' => 'Date Ordered'],
                ['data' => 'date_shipped', 'name' => 'o.date_shipped', 'title' => 'Date Shipped'],
                ['data' => 'date_delivered', 'name' => 'o.date_delivered', 'title' => 'Date Delivered'],
                ['data' => 'status', 'name' => 's.status', 'title' => 'Status'],
                Column::make('total')->searchable(false)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
