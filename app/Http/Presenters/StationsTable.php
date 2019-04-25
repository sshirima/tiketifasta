<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 3/8/2019
 * Time: 6:28 PM
 */

namespace App\Http\Presenters;


class StationsTable extends BaseTablePresenter
{
    public function setTableColumns()
    {
        // TODO: Implement setTableColumns() method.
        $this->tableColumns = ['stations.id', 'stations.st_name','stations.st_type', 'stations.location_id','locations.name'];
    }

    public function setTableRoutes()
    {
        // TODO: Implement setTableRoutes() method.
        $this->table->setRoutes([
            'index' => ['alias' => 'admin.stations.index', 'parameters' => []],
            'create' => ['alias' => 'admin.stations.create', 'parameters' => []],
            'edit' => ['alias' => 'admin.stations.edit', 'parameters' => ['id']],
            'destroy' => ['alias' => 'admin.stations.destroy', 'parameters' => ['id']]]);
    }

    public function addTableQuery()
    {
        // TODO: Implement addTableQuery() method.
        $this->table ->addQueryInstructions(function($query){
            $query->select($this->tableColumns);
            $query->join('locations','locations.id','=','stations.location_id');
        });
    }

    public function setDisplayColumns()
    {
        // TODO: Implement setDisplayColumns() method.
        $this->table->addColumn('st_name')->isSortable()->isSearchable()->sortByDefault()->setTitle('Station name')->useForDestroyConfirmation();
        $this->table->addColumn('st_type')->isSortable()->isSearchable()->setTitle('Station types');
        $this->table->addColumn('name')->isSortable()->isSearchable()->setTitle('Location')->setCustomTable('locations');
    }

    public function setTableRowsNumber($rowsNumber = 10)
    {
        // TODO: Implement setRowsNumber() method.
        $this->table->setRowsNumber($rowsNumber);
    }
}