<?php

namespace Seongbae\Canvas\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('user_image', function($row) {
                $imageAttribute = config('canvas.user_image_field');
                return '<a href="'.route('admin.users.show', $row->id).'"><img src="'. $row->$imageAttribute .'" style="width:40px;" class="rounded-circle"></a>';
            })
            ->editColumn('name', function($row) {
                return '<a href="'.route('admin.users.show', $row->id).'">'.$row->name.'</a>';
            })
            ->editColumn('created_at', function($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('action', function($row) {
                return '<a href="'.route('admin.users.edit', $row->id).'" class="btn btn-link text-secondary p-1"><i class="far fa-edit "></i></a><form action="'.route('admin.users.destroy', $row->id).'" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-link text-secondary p-1"  onClick="return confirm(\'Are you sure?\')">
                            <i class="far fa-trash-alt"></i>
                            </button>
                            '.csrf_field().'
                          </form>';
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Event $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $model = config('auth.providers.users.model');
        return $model::orderBy('created_at','desc')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(0)
                    ->buttons(
                        Button::make('create'),
                         Button::make('export'),
                         Button::make('print'),
                         Button::make('reset'),
                         Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('ID')->width(40),
            Column::computed('user_image')
                    ->title('')
                    ->width(40),
            Column::make('name'),
            Column::make('email'),
            Column::make('created_at')
                    ->title('Created'),
            Column::computed('action')->title('')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-right'),
            
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename() : string
    {
        return 'Users_' . date('YmdHis');
    }
}
