<?php
use \koolreport\widgets\koolphp\Table;
?>

    <div class="text-center">
        <h1>Daily collections</h1>
        <h4>This report shows daily collections revenue from tickets</h4>
    </div>
    <hr/>

<?php
\koolreport\widgets\google\ColumnChart::create(array(
    "dataStore"=>$this->dataStore('c2b_collections_report'),
    "width"=>"100%",
    "height"=>"500px",
    "columns"=>array(
        "paid_date"=>array(
            "label"=>"Date"
        ),
        "total"=>array(
            "type"=>"number",
            "label"=>"Total amount",
            "prefix"=>"Tsh",
        )
    ),
    "options"=>array(
        "title"=>"C2B collections"
    )
));

?>
<?php
Table::create(array(
    "dataStore"=>$this->dataStore('c2b_collections_report'),
    "showFooter"=>true,
    "columns"=>array(
        "paid_date"=>array(
            "label"=>"Date"
        ),
        "total"=>array(
            "type"=>"number",
            "label"=>"Total amount",
            "prefix"=>"Tsh",
            "footer"=>"sum",
            "footerText"=>"<b>Total collections:</b> @value"
        ),
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