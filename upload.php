<?php

if( isset( $_POST['my_file_upload'] ) ){

    $files      = $_FILES; // полученные файлы
    $done_files = array();

    $open = [];

    foreach( $files as $file ){
        $file_name = $file['name'];

        if( move_uploaded_file( $file['tmp_name'], "$file_name" ) ){
            $done_files[] = realpath( "$file_name" );
        }
    }

    foreach( $files as $file ){

        $file_name = $file['name'];
        $result_file = file($file_name);


        array_push($open,'{ name: \'Test\'' . ', data: ' . '[' . implode("",$result_file) . ']},');


        }

    file_put_contents('result.js', 'document.addEventListener(\'DOMContentLoaded\', function () {
        Highcharts.chart(\'container\', {
            chart: {
                type: \'line\',
                zoomType: \'xy\'
            },
            title: {
                text: \'Rocket Thrust graphics\'
            },
            xAxis: {
                title: {
                    text: \'Time\'
                },
                labels: {
                  format: \'{value} sec\'
                }
            },
            yAxis: {
                title: {
                    text: \'Thrust\'
                },
                labels: {
                  format: \'{value} gram\'
                }
            },
            tooltip: {
                headerFormat: \'<b>{series.name}</b><br/>\',
                pointFormat: \'{point.x} sec: {point.y} gram\'
              },
            series: [' . implode("",$open) . ']
        });
    });');

    $data = "Привет" ? array('files' => $open ) : array('error' => 'Ошибка загрузки файлов.');

    die( json_encode( $data ) );
}