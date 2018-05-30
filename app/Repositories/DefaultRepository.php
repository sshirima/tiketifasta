<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 5/10/2018
 * Time: 9:52 PM
 */

namespace App\Repositories;


use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

trait DefaultRepository
{
    protected $conditions = array();
    protected $tableRoutes = array();
    protected $entityColumns = array();

    /**
     * @param Request $request
     */
    public function setConditions(Request $request){
        //Conditions
    }

    /**
     * @param array $columns
     */
    public function setReturnColumn(array $columns){
        $this->entityColumns = $columns;
    }

    /**
     * Initialize important parameters for the queries
     * @param Request $request
     * @param array $returnColumns
     */
    public function initializeTable(Request $request, array $returnColumns){

        $this->setReturnColumn($returnColumns);

        $this->setConditions($request);
    }

    /**
     * @return mixed
     */
    public function instantiateTableList()
    {
        $table = app(TableList::class)
            ->setModel($this->model())
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes($this->getTableRoutes());
        return $table;
    }

    /**
     * Get table route for Okipa table view
     * @return array
     */
    private function getTableRoutes(){

        $this->setTableRoutes();

        return $this->tableRoutes;
    }

    /**
     * Set table routes
     */
    protected function setTableRoutes(){

        $this->setIndexRoute();

        if(!is_null($this->routeCreate)){
            $this->setCreateRoute();
        }

        if(!is_null($this->routeEdit)){
            $this->setEditRoute();
        }

        if(!is_null($this->routeDestroy)){
            $this->setDestroyRoute();
        }
    }

    /**
     * Set index route
     */
    protected function setIndexRoute(){
        $this->tableRoutes['index'] = ['alias' => $this->routeIndex, 'parameters' => []];
    }

    /**
     * Set create route
     */
    protected function setCreateRoute(){
        $this->tableRoutes['create'] = ['alias' => $this->routeCreate, 'parameters' => []];
    }

    /**
     * Set Edit route
     */
    protected function setEditRoute(){
        $this->tableRoutes['edit'] = ['alias' => $this->routeEdit, 'parameters' => []];
    }

    /**
     * Set destroy route
     */
    protected function setDestroyRoute(){
        $this->tableRoutes['destroy'] = ['alias' => $this->routeDestroy, 'parameters' => []];
    }

}