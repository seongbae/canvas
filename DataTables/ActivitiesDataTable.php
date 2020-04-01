<?php

namespace App\Canvas\DataTables;

use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Spatie\Permission\Models\Role;

class ActivitiesDataTable extends DataTable
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
            ->addColumn('properties_display', function($row) {
                    
                return json_encode($row->properties);
            })
            ->addColumn('subject_display', function($row) {

                if ($row->subject != null)
                    return json_encode($row->subject);
            })
            ->addColumn('action_by', function($row) {
                
                if ($row->causer != null)
                    return $row->causer->name;
            })
            ->addColumn('action_on', function($row) {
                if ($row->subject != null)
                    $row->subject->name;
            })
            // ->addColumn('action', function($row) {
            //     return '<a href="/admin/achievements/'. $role->id .'/edit" class="btn btn-primary">Edit</a><form action="'.route('admin.achievements.destroy', $role->id).'" method="POST" class="d-inline">
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
    public function query(Activity $model)
    {
        return $model->with('causer')->with('subject')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('activities-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->responsive()
                    ->pageLength(20)
                    //->addAction(['width' => '100%'])
                    //->with('hello')
                    ->orderBy(0);
                    // ->buttons(
                    // //     Button::make('create')
                    // //     Button::make('export'),
                    // //     Button::make('print'),
                    // //     Button::make('reset'),
                    // //     Button::make('reload')
                    // );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('created_at'),
            Column::make('log_name'),
            Column::make('action_by'),
            Column::make('subject_type'),
            Column::make('description'),
            Column::make('properties_display'),
            // Column::computed('action')->title('')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->addClass('text-right')
            
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Activities_' . date('YmdHis');
    }
}
