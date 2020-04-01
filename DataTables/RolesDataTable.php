<?php

namespace App\Canvas\DataTables;

use App\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Spatie\Permission\Models\Role;

class RolesDataTable extends DataTable
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
            ->addColumn('role_permissions', function($role) {
                    
                    $permissions = "";
                    
                    foreach($role->permissions as $permission)
                    {
                        $permissions = $permissions . $permission->name . " | ";
                    }

                    if (!empty($permissions))
                        $permissions = substr($permissions, 0, -2);

                    return $permissions;
                })
            ->addColumn('action', function($role) {
                return '<a href="/admin/users/roles/'. $role->id .'/edit" class="btn btn-primary">Edit</a><form action="'.route('admin.roles.destroy', $role->id).'" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" name="submit" value="Remove" class="btn btn-danger " onClick="return confirm(\'Are you sure?\')">
                            '.csrf_field().'
                          </form>';
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
    public function query(Role $model)
    {
        return $model->with('permissions')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('roles-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
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
            Column::make('name')->title('Role'),
            Column::make('role_permissions')->title('Permissions'),
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
