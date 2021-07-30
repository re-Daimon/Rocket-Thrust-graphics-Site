<?php

if( isset( $_POST['my_file_upload'] ) ){

    $files      = $_FILES; // полученные файлы
    $done_files = array();

    $open = []; //массив данных

    foreach( $files as $file ){
        $file_name = $file['name'];

        if( move_uploaded_file( $file['tmp_name'], "$file_name" ) ){ // загружаем файлы
            $done_files[] = realpath( "$file_name" );
        }
    }

    $cout = 1; //счетчик файлов
    foreach( $files as $file ){

        $file_name = $file['name'];
        $result_file = file($file_name); // получаем данные


        array_push($open,'{ name: \'Test' . $cout . '\'' . ', data: ' . '[' . implode("",$result_file) . ']},');
        $cout++;


        }
    // изменяем js файл
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
