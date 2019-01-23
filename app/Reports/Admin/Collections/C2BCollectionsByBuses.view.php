<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\BarChart;
use \koolreport\widgets\google\ColumnChart;
?>

    <div class="text-center">
        <h1>Buses collections</h1>
        <h4>This report shows collections revenue per bus</h4>
    </div>
    <hr/>

<?php
\koolreport\widgets\google\BarChart::create(array(
    "dataStore"=>$this->dataStore('buses_collections'),
    "width"=>"100%",
    "height"=>"500px",
    "columns"=>array(
        "reg_number"=>array(
            "label"=>"Reg number"
        ),
        "total_amount"=>array(
            "type"=>"number",
            "label"=>"Total ",
            "prefix"=>"Tsh ",
        )
    ),
    "options"=>array(
        "title"=>"Revenue collections by bus"
    )
));

?>
<?php
Table::create(array(
    "dataStore"=>$this->dataStore('buses_collections'),
    "showFooter"=>true,
    "columns"=>array(
        "reg_number"=>array(
            "label"=>"Reg number"
        ),
        "total_amount"=>array(
            "type"=>"number",
            "label"=>"Total ",
            "prefix"=>"Tsh ",
            "footer"=>"sum",
            "footerText"=>"<b>Total collections:</b> @value"
        )
    ),
    "paging"=>array(
        "pageSize"=>10,
        "pageIndex"=>0,
    ),
    "cssClass"=>array(
        "table"=>"table-bordered table-striped table-hover"
    )
));
?>