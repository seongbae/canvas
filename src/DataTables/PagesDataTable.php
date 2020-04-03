<?php

namespace Seongbae\Canvas\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Spatie\Permission\Models\Role;
use Seongbae\Canvas\Models\Page;

class PagesDataTable extends DataTable
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
            // ->addColumn('role_permissions', function($role) {
                    
            //         $permissions = "";
                    
            //         foreach($role->permissions as $permission)
            //         {
            //             $permissions = $permissions . $permission->name . " | ";
            //         }

            //         if (!empty($permissions))
            //             $permissions = substr($permissions, 0, -2);

            //         return $permissions;
            //     })
            ->addColumn('action', function($page) {
                return '<a href="'. $page->url .'" class="btn btn-primary" target="_blank">View</a> <a href="/admin/pages/'. $page->id .'/edit" class="btn btn-primary">Edit</a><form action="" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" name="submit" value="Remove" class="btn btn-danger " onClick="return confirm(\'Are you sure?\')">
                            '.csrf_field().'
                          </form>';
            })
            ->addColumn('formatted_date', function($page) {
                return format_date($page->created_at);
            })
            // ->addColumn('action', function($row) {
            //     return '<a href="/admin/users/'. $row->id .'/edit" class="btn btn-primary">Edit</a><form action="'.route('users.destroy', $row->id).'" method="POST" class="d-inline">
            //                 <input type="hidden" name="_method" value="DELETE">
            //                 <input type="submit" name="submit" value="Remove" class="btn btn-danger " onClick="return confirm(\'Are you sure?\')">
            //                 '.csrf_field().'
            //               </form>';
            // })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Event $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Page $model)
    {
        return $model->with('user')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('pages-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(3)
                    ->parameters([
                      'responsive' => true
                    ])
                    ->buttons(
                         Button::make('create')
                    //     Button::make('export'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
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
            Column::make('name'),
            Column::make('uri')->title('URL')->sortable(false)->searchable(false),
            Column::make('user.name')->title('Author'),
            Column::make('created_at')->title('Created'),
            Column::computed('action')->title('')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-right')
            
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Roles_' . date('YmdHis');
    }
}
