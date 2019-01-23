<?php
use \koolreport\widgets\koolphp\Table;
?>

    <div class="text-center">
        <h1>Merchants revenue collections</h1>
        <h4>This report shows collections revenue per merchant</h4>
    </div>
    <hr/>

<?php
\koolreport\widgets\google\BarChart::create(array(
    "dataStore"=>$this->dataStore('merchants_collections'),
    "width"=>"100%",
    "height"=>"500px",
    "columns"=>array(
        "merchant_name"=>array(
            "label"=>"Merchant name "
        ),
        "total_amount"=>array(
            "type"=>"number",
            "label"=>"Sum ",
            "prefix"=>"Tsh ",
        )
    ),
    "options"=>array(
        "title"=>"Revenue collections by merchants"
    )
));

?>
<?php
Table::create(array(
    "dataStore"=>$this->dataStore('merchants_collections'),
    "showFooter"=>true,
    "columns"=>array(
        "merchant_name"=>array(
            "label"=>"Merchant name "
        ),
        "total_amount"=>array(
            "type"=>"number",
            "label"=>"Sum ",
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