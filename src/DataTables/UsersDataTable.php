<?php

namespace Seongbae\Canvas\DataTables;

use App\Models\User;
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
            //->orderBy('id')
            // ->orderColumn('id', function ($query, $order) {
            //          $query->orderBy('id', $order);
            //      });
            ->addColumn('user_image', function($row) {
                return '<a href="'.route('admin.users.edit', $row->id).'"><img src="'. $row->photo .'" style="width:40px;" class="rounded-circle"></a>';
            })
            ->editColumn('name', function($row) {
                return '<a href="'.route('admin.users.edit', $row->id).'">'.$row->name.'</a>';
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
    public function query(User $model)
    {
        return $model->with('roles')->orderBy('created_at','desc')->newQuery();
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
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create')
                        // Button::make('export'),
                        // Button::make('print'),
                        // Button::make('reset'),
                        // Button::make('reload')
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
            Column::computed('user_image')
                    ->title('')
                    ->with(60),
            Column::make('name'),
            Column::make('email'),
            Column::make('role'),
            Column::make('created_at')
                    ->title('Created'),
            Column::make('last_login_at')
                    ->title('Last Login'),
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
