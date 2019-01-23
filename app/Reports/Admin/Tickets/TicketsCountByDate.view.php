<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\BarChart;
use \koolreport\widgets\google\ColumnChart;
?>

    <div class="text-center">
        <h1>Tickets counts daily</h1>
        <h4>This report shows daily tickets counts</h4>
    </div>
    <hr/>

<?php
$ticketCounts = ($this->dataStore('tickets_counts')->toJson());

$processed_data = [];

foreach (json_decode($ticketCounts) as $count){
    $processed_data[$count->date]['date'] = $count->date ;
    if($count->status == \App\Models\Ticket::STATUS_EXPIRED){
        $processed_data[$count->date]['expired'] = $count->total_tickets ;
    }
    if($count->status == \App\Models\Ticket::STATUS_CONFIRMED){
        $processed_data[$count->date]['confirmed'] = $count->total_tickets;
    }
}
$column_data = [];
foreach ($processed_data as $datum) {
    $column_data[]=[
            'date'=>$datum['date'],
        'expired'=>array_key_exists('expired', $datum) ? $datum['expired'] : 0,
        'confirmed'=>array_key_exists('confirmed', $datum) ? $datum['confirmed'] : 0
    ];
}

\koolreport\widgets\google\BarChart::create(array(
    "dataStore"=>$column_data,
    "width"=>"100%",
    "height"=>"500px",
    "columns"=>array(
        "date"=>array(
            "label"=>"Date"
        ),
        "expired"=>array(
            "type"=>"number",
            "label"=>"Expired",
            "prefix"=>" ",
        ),
        "confirmed"=>array(
            "type"=>"number",
            "label"=>"Confirmed",
            "prefix"=>" ",
        ),
    ),
    "options"=>array(
        "title"=>"Tickets counts"
    )
));

?>
<br>
<?php
Table::create(array(
    "dataStore"=>$this->dataStore('tickets_counts'),
    "showFooter"=>true,
    "removeDuplicate"=>array("date"),
    "columns"=>array(
        "date"=>array(
            "label"=>"Date"
        ),
        "status"=>array(
            "label"=>"Status"
        ),
        "total_tickets"=>array(
            "type"=>"number",
            "label"=>"Total ",
            "prefix"=>" ",
            "footer"=>"sum",
            "footerText"=>"<b>Total tickets:</b> @value"
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