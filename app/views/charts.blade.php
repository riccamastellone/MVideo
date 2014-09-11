@extends('layouts.master')
    
@section('content')
    
<div class="jumbotron margin-top">
    <div class="current-status">
	<div id="container" class="chart"></div>
	
	<?php $count=0; foreach ($results_divided as $r) { ?>
	<div id="container-{{$count}}" class="chart"></div>
	<?php $count++;} ?>
    </div>
    
</div>
    

<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="/packages/plupload/plupload.full.min.js"></script>
<script src="/js/upload.js"></script>
<script src="/js/results.js"></script>
<script>
    $(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'MVideo Results (all devices)'
        },
        subtitle: {
            text: 'How long does 1% last?'
        },
        xAxis: {
            categories: [
                '0%',
                '25%',
                '50%',
                '75%',
                '100%'
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Minutes'
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Wifi ( Signal > 90% )',
            data: [{{ implode($results['wifi-hi'],',') }}]

        }, {
            name: 'Wifi ( 89% > Signal > 70% )',
            data: [{{ implode($results['wifi-mid'],',') }}]

        },{
            name: 'Wifi ( Signal < 69% )',
            data: [{{ implode($results['wifi-low'],',') }}]

        }, {
            name: 'Mobile Network',
            data: [{{ implode($results['mobile'],',') }}]

        }]
    });
});</script>
<?php $count=0; foreach ($results_divided as $imei => $r) { ?>
<script>
    $(function () {
    $('#container-{{$count}}').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'IMEI {{$imei}}'
        },
        subtitle: {
            text: 'How long does 1% last?'
        },
        xAxis: {
            categories: [
                '0%',
                '25%',
                '50%',
                '75%',
                '100%'
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Minutes'
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Wifi ( Signal > 90% )',
            data: [{{ implode($r['wifi-hi'],',') }}]

        }, {
            name: 'Wifi ( 89% > Signal > 70% )',
            data: [{{ implode($r['wifi-mid'],',') }}]

        },{
            name: 'Wifi ( Signal < 69% )',
            data: [{{ implode($r['wifi-low'],',') }}]

        }, {
            name: 'Mobile Network',
            data: [{{ implode($r['mobile'],',') }}]

        }]
    });
});</script>
<?php $count++;} ?>
@stop