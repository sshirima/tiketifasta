<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\BarChart;
?>

    <div class="text-center">
        <h1>Sales Report</h1>
        <h4>This report shows tigopesa transactions</h4>
    </div>
    <hr/>

<?php
BarChart::create(array(
    "dataStore"=>$this->dataStore('tigo_c2b_data'),
    "width"=>"100%",
    "height"=>"500px",
    "columns"=>array(
        "phone_number"=>array(
            "label"=>"Phone number"
        ),
        "amount"=>array(
            "type"=>"number",
            "label"=>"Amount",
            "prefix"=>"Tsh",
        )
    ),
    "options"=>array(
        "title"=>"Sales By Customer"
    )
));
?>
<?php
Table::create(array(
    "dataStore"=>$this->dataStore('tigo_c2b_data'),
    "columns"=>array(
        "phone_number"=>array(
            "label"=>"Phone number"
        ),
        "amount"=>array(
            "type"=>"number",
            "label"=>"Amount",
            "prefix"=>"Tsh",
        )
    ),
    "cssClass"=>array(
        "table"=>"table table-hover table-bordered"
    )
));
?>