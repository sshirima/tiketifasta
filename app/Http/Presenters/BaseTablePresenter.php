<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 3/7/2019
 * Time: 11:43 PM
 */

namespace App\Http\Presenters;


use Okipa\LaravelBootstrapTableList\TableList;

abstract class BaseTablePresenter
{
    protected $table;
    protected $tableColumns;

    /**
     * BaseTablePresenter constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->table = $this->initializeTableCreation($model);

        $this->setTableRoutes();

        $this->setTableColumns();

        $this->setTableRowsNumber();

        $this->addTableQuery();

        $this->setDisplayColumns();
    }

    public function initializeTableCreation($model, $rowsPerPage = 10)
    {
        $table = app(TableList::class)
            ->setModel($model)
            ->setRowsNumber($rowsPerPage)
            ->enableRowsNumberSelector();
        return $table;
    }

    abstract public function setTableRowsNumber($rowsNumber=10);

    abstract public function setTableRoutes();

    abstract public function setTableColumns();

    abstract public function addTableQuery();

    abstract public function setDisplayColumns();

    public function getTable()
    {
        return $this->table;
    }
}