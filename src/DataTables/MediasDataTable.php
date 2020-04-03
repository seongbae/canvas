<?php

namespace Seongbae\Canvas\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Seongbae\Canvas\Models\Media;

class MediasDataTable extends DataTable
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
             ->addColumn('action', function($row) {
                return '<a href="/admin/media/'. $row->id .'/edit" class="btn btn-primary">Edit</a>
                        <form action="'.route('admin.media.destroy', $row->id).'" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" name="submit" value="Remove" class="btn btn-danger " onClick="return confirm(\'Are you sure?\')">
                            '.csrf_field().'
                          </form>';
            })
            ->addColumn('img', function($row) {
                return '<img src="'. $row->path .'" class="img-fluid" style="width:80px;">';
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Media $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Media $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('medias-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create')
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
            Column::computed('img')->title(''),
            Column::make('name'),
            Column::make('created_at'),
            Column::computed('action')
                    ->title('')
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
    protected function filename()
    {
        return 'Medias_' . date('YmdHis');
    }
}
