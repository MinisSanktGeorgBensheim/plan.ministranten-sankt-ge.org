<?php


class gottesdienste
{
    public function output() {

        return "";
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "http://katholische-kirche-bensheim.de/gottesdienste-2/");

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
//            http_build_query(array('x1' => '1','x2' => '1','x3' => '1','x4' => '1', "x5"=>1, "gesendet"=>1)));
//            http_build_query(array('x1' => '1', "x5"=>1, "gesendet"=>1)));
            http_build_query(array('x1' => '1', "gesendet"=>1)));


        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

//        echo $output;
//        exit;
        preg_match_all('/<div class="execphpwidget">.*<table.*<\/table><\/div><br clear=\'all\'>/s', $output, $output_array);

//        $doc = new DOMDocument();
//        $doc->loadHTML($output_array[0][0]);
//        echo $doc->saveHTML();

//        print_r($output_array[0][0]);

        preg_match_all('/<tr id=\'dat\' >(.+?(?=<tr id=\'dat\' >)|.+?(?=<\/table>))/s', $output_array[0][0], $output_array);

        $data = array();

        foreach ($output_array[0] as $value) {
            $dataRow = array();

            preg_match_all('/(?<=colspan=\'3\'>).+?(?=<\/td)/s', $value, $output_arrayDate);
//            $data[] = ;

            $output_arrayDate[0][0] = preg_split('/\| /', $output_arrayDate[0][0]);

            if (isset($output_arrayDate[0][0][0])) $dataRow["day"] = $output_arrayDate[0][0][0];

            $dataRow["time"] = strtotime($dataRow["day"]."", time());
            $dataRow["time2"] = date("Y-m-d H:i:s", strtotime("7. Juni 2019", time()));
            if (isset($output_arrayDate[0][0][1])) $dataRow["title"] = $output_arrayDate[0][0][1];

            preg_match_all('/(<tr id=\'zeit\'>).+?(?=<\/tr>)/s', $value, $output_arrayZeit);

            $times = array();
            foreach ($output_arrayZeit[0] as $valueZeit) {

                preg_match_all('/(?<=<td>).+?(?=<\/div><\/td)/s', $valueZeit, $output_arrayGD1);
                preg_match_all('/(?<=>).*/', $output_arrayGD1[0][0], $output_arrayGD2);
                preg_match_all('/(?<=>).*/', $output_arrayGD1[0][1], $output_arrayGD3);
                preg_match_all('/(?<=>).*/', $output_arrayGD1[0][2], $output_arrayGD4);

//                $times[]= $output_arrayGD1;
                $times[]= array("time"=>$output_arrayGD2[0][0],"place"=>$output_arrayGD3[0][0], "comment" => $output_arrayGD4[0][0]);


            }

            $dataRow["gds"]= $times;

            $data[] = $dataRow;
//            print_r($output_array[0]);
        }
        print_r($data);
        exit;
//        return "";
    }
}