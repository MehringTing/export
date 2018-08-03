<?php

namespace app\index\controller;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use PhpOffice\PhpWord\Shared\Converter;
use think\Exception;
use think\Controller;

class Index extends Base
{
    /**
     * 营业总收入
     */
    public function yyzsr()
    {
        $stock_code = input('stock_code');
        $url_em = 'http://emweb.securities.eastmoney.com/NewFinanceAnalysis/MainTargetAjax?ctype=4&type=0&code=SZ002425';
        $url_h5 = 'http://www.ubd2.com/financial.php/financial/index?company_id=002425&year=2018&quarter=q2&limit=9';
        $path_em = 'E:\report\public\eastmoney\002425.json';
        $path_h5 = 'E:\report\public\h5\002425.json';
        if (file_exists($path_em)) {
            $rs_em = file_get_contents($path_em);
        } else {
            $rs_em = file_get_contents($url_em);
            $fhandle = fopen($path_em, 'a+');
            fwrite($fhandle, $rs_em);
            fclose($fhandle);
        }
        if (file_exists($path_h5)) {
            $rs_h5 = file_get_contents($path_h5);
        } else {
            $rs_h5 = file_get_contents($url_h5);
            $fhandle = fopen($path_h5, 'a+');
            fwrite($fhandle, $rs_h5);
            fclose($fhandle);
        }
        $data_em = json_decode($rs_em, true);
        $data_my = json_decode($rs_h5, true);
        $data_my = $data_my['data'];

        $data1 = [];
        foreach ($data_em as $key => $value) {
            $data1[$value['date']] = [
                'date' => $value['date'],
                'yyzsr' => $value['yyzsr']
            ];
        }
        // dump($data_my['performanceTrend_incomeTrend']);
        // exit;

        //营业收入
        $data2 = $this->getYYZSR($data_my['performanceTrend_incomeTrend']);
        //净利润
        $data3 = $this->getJLR($data_my['performanceTrend_profitTrend']);
        $data = array_values(array_merge_recursive($data1, $data2, $data3));
        $yyzsr = [
            array_column($data, 'date'),
            array_column($data, 'yyzsr'),
            array_column($data, 'num'),
            array_column($data, 'jlr_h5')
        ];

        $return = ['code' => 1, 'msg' => 'success', 'data' => $yyzsr];
        return json($return);
    }

    public function jbmgsy()
    {
        $stock_code = input('stock_code');
        $url_em = 'http://emweb.securities.eastmoney.com/NewFinanceAnalysis/MainTargetAjax?ctype=4&type=0&code=SZ002425';
        $url_h5 = 'http://www.ubd2.com/financial.php/financial/index?company_id=002425&year=2018&quarter=q2&limit=9';
        $path_em = 'E:\report\public\eastmoney\002425.json';
        $path_h5 = 'E:\report\public\h5\002425.json';
        if (file_exists($path_em)) {
            $rs_em = file_get_contents($path_em);
        } else {
            $rs_em = file_get_contents($url_em);
            $fhandle = fopen($path_em, 'a+');
            fwrite($fhandle, $rs_em);
            fclose($fhandle);
        }
        if (file_exists($path_h5)) {
            $rs_h5 = file_get_contents($path_h5);
        } else {
            $rs_h5 = file_get_contents($url_h5);
            $fhandle = fopen($path_h5, 'a+');
            fwrite($fhandle, $rs_h5);
            fclose($fhandle);
        }
        $data_em = json_decode($rs_em, true);
        $data_my = json_decode($rs_h5, true);
        $data_my = $data_my['data'];

        $data1 = [];
        foreach ($data_em as $key => $value) {
            $data1[$value['date']] = [
                'date' => $value['date'],
                'xsmgsy' => $value['xsmgsy']
            ];
        }
    }

    

    


    public function index()
    {
        //$this->fetch();
        return view();
    }

    public function exportWord()
    {
        $this->display('Index/index');
        //exit;
        //dump(123);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
                . 'The important thing is not to stop questioning." '
                . '(Albert Einstein)'
        );

        $table = $section->addTable();

        $table->addRow();
        $table->addCell(1000, ['vMerge' => 'restart'])->addText(1);
        $table->addCell(1000, ['gridSpan' => 2])->addText(2);
        $table->addRow();
        $table->addCell(1000, ['vMerge' => 'continue'])->addText(1);
        $table->addCell(1000)->addText(3);
        $table->addCell(1000)->addText(4);
        $table->addRow();
        $table->addCell(1000)->addText(5);
        $table->addCell(1000)->addText(6);
        $table->addCell(1000)->addText(7);

//        $data = [
//            '项目',
//            '参会代表' => ['姓名', '电话'],
//            '参会代表' => ['姓名', '电话'],
//            '日期'
//        ];

        $data = [
            ['项目', '参会代表', '参会代表', '日期'],
            [
                '项目',
                ['姓名', '电话'],
                ['姓名', '电话'],
                '日期'
            ]
        ];
        $arr = [
            'aa' => [],
            'bb' => [],
            'cc' => []
        ];
        $keys = array_keys($arr);
        dump(end($keys));
        exit;
        $cols = $this->array_length($data[1], 1);
        dump($cols);
        dump($data);
        foreach ($data as $k => $v) {
            $table->addRow();
            foreach ($v as $kk => $vv) {
                if ($k == 0) {
                    if (is_array($data[1][$kk])) {
                        $table->addCell(1000, ['gridSpan' => '2'])->addText($vv);
                    } else {
                        $table->addCell(1000, ['vMerge' => 'restart'])->addText($vv);
                    }
                } else {
                    if (is_array($vv)) {
                        foreach ($vv as $vvv) {
                            $table->addCell(1000)->addText($vvv);
                        }
                    } else {
                        $table->addCell(1000, ['vMerge' => 'continue'])->addText($vv);
                    }
                }

            }
        }


//        $arr = [
//            [
//                'id' => '1',
//                'title' => '第一章',
//                'menu' => []
//            ],
//            [
//                'id' => '2',
//                'title' => '第二章',
//                'menu' => [
//                    [
//                        'id' => '2.1',
//                        'title' => '第二章 第一节',
//                        'menu' => []
//                    ],
//                    [
//                        'id' => '2.2',
//                        'title' => '第二章 第二节',
//                        'menu' => []
//                    ]
//                ]
//            ],
//            [
//                'id' => '3',
//                'title' => '第三章',
//                'menu' => []
//            ],
//        ];

//        $arr = [
//            [
//                'title' => '第一章',
//                'menu' => []
//            ],
//            [
//                'title' => '第二章',
//                'menu' => [
//                    [
//                        'title' => '第二章 第一节',
//                        'menu' => []
//                    ],
//                    [
//                        'title' => '第二章 第二节',
//                        'menu' => []
//                    ]
//                ]
//            ],
//            [
//                'title' => '第三章',
//                'menu' => []
//            ],
//        ];

//        $arr = [
//            ['id' => '1','title' => '第一章','pid' => 0],
//            ['id' => '2','title' => '第二章','pid' => 0],
//            ['id' => '3','title' => '第二章 第一节','pid' => 2],
//            ['id' => '4','title' => '第二章 第二节','pid' => 2],
//            ['id' => '5','title' => '第三章','pid' => 0]
//        ];

        //dump($arr);
        //dump(count($arr, 1));
        //self::listTitle($arr);
        //$depth = self::array_depth($arr);
        //dump($depth);


        //dump(url('createImage'));
        //die;


        //dump(config('export_config.www') . url('createImage'));
        //$source = file_get_contents(config('export_config.www') . url('createImage'));

        //$section->addImage($source, ['width' => 350 * 0.75, 'height' => 250 * 0.75]);
        $doc = '../hello.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($doc);

    }

    public static function listTitle($titles, $level = 1)
    {
        $pk = 1;
        foreach ($titles as $k => $title) {
            echo $k, $title['title'], '<br>';
            if (!empty($title['menu'])) {
                self::listTitle($title['menu'], ++$level);
            }
        }
    }

    public static function array_depth($array)
    {
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::array_depth($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    public function createImage()
    {
        // Create the Pie Graph.
        $graph = new Graph\PieGraph(510, 360);
        $graph->title->Set("A Simple Pie Plot");
        $graph->title->SetFont(FF_ARIAL, FS_NORMAL, 16);
        $graph->title->SetMargin(20);
        //$graph->SetBox(true, [255,0,0]);

        $data = array(40, 21, 17, 14, 23);
        $p1 = new Plot\PiePlot($data);
        //$p1->ShowBorder();
        $p1->SetColor('black');
        $p1->SetSliceColors(array('#1E90FF', '#2E8B57', '#ADFF2F', '#ff0000', '#BA55D3'));
        $p1->title->SetFont(FF_ARIAL, FS_NORMAL, 20);

        $graph->Add($p1);
        return $graph->Stroke();
//        return $graph;
    }

    public function createLineXYChart()
    {
        $width = 400;
        $height = 300;
        $ydata = array(11, 3, 8, 12, 5, 1, 9, 13, 5, 7);

        $graph = new Graph\Graph($width, $height);
        $graph->SetScale('textlin');
        $graph->SetMargin(50, 30, 50, 0);
        //$lineplot = new Plot\LinePlot($ydata);
        $lineplot = new Plot\BarPlot($ydata);
        $graph->Add($lineplot);

        $lineplot->SetColor('darkred');
        $lineplot->SetWeight(2);
//        $lineplot->mark->SetType(MARK_SQUARE);
//        $lineplot->mark->SetFillColor('red');
//        $lineplot->mark->SetColor('red');
        //$text = mb_convert_encoding('降雨量','GBK','UTF-8');
        $graph->title->Set(iconv('UTF-8', 'GB2312//IGNORE', '降雨量'));
        //$graph->title->Set('降雨量');
        $graph->title->SetFont(FF_SIMSUN, FS_NORMAL, 10);
        $graph->title->SetMargin(5);

        $graph->xaxis->title->SetFont(FF_SIMSUN, FS_NORMAL, 10);
        $graph->xaxis->title->Set(iconv('UTF-8', 'GB2312//IGNORE', '日期aaa123'));
        $graph->yaxis->title->SetFont(FF_SIMSUN, FS_BOLD, 10);
        $graph->yaxis->title->Set(iconv('UTF-8', 'GB2312', '降雨量'));
        //$graph->yaxis->SetLabelAngle(90);
        $graph->yaxis->SetColor('darkred');

        $lineplot->SetLegend(iconv('UTF-8', 'GB2312//IGNORE', '降雨量'));
        $graph->legend->SetPos(0.08, 0.08, 'right', 'top');
        //$graph->legend->SetLineWeight(6);
        $graph->Stroke();

    }


    public function createLineImage()
    {
        $ydata = array(11, 3, 8, 12, 5, 1, 9, 13, 5, 7);
        $y2data = array(354, 200, 265, 99, 111, 91, 198, 225, 293, 251);

        // Create the graph and specify the scale for both Y-axis
        $width = 550;
        $height = 400;
        $graph = new Graph\Graph(550, 300);
        $graph->SetScale('textlin');
        $graph->SetY2Scale('lin');
        $graph->SetShadow();

        // Adjust the margin
        $graph->SetMargin(50, 50, 60, 10);

        // Create the two linear plot
        $lineplot = new Plot\LinePlot($ydata);
        $lineplot2 = new Plot\LinePlot($y2data);

        // Add the plot to the graph
        $graph->Add($lineplot);
        $graph->AddY2($lineplot2);
        //$lineplot2->SetColor('black');
        // $lineplot2->SetWeight(20);

        // Adjust the axis color
        $graph->y2axis->SetColor('#ff00ff');
        $graph->yaxis->SetColor('green');

        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
        $graph->title->Set('Using JpGraph Library');
        $graph->title->SetMargin(5);

//        $graph->subtitle->SetFont(FF_ARIAL, FS_BOLD, 10);
//        $graph->subtitle->Set('(common objects)');

        $graph->xaxis->title->SetFont(FF_ARIAL, FS_BOLD, 10);
        $graph->xaxis->title->Set('X-title');
        $graph->yaxis->title->SetFont(FF_SIMSUN, FS_BOLD, 10);
        $graph->yaxis->title->Set(iconv('UTF-8', 'GB2312//IGNORE', '你好'));
        $graph->yaxis->title->SetMargin(5);
        $graph->y2axis->title->Set('Y2');
        $graph->y2axis->title->SetMargin(20);

        // Set the colors for the plots
        $lineplot->SetColor('green');
        $lineplot->SetWeight(2);
        $lineplot2->SetColor('darkred');
        $lineplot2->SetWeight(2);

        // Set the legends for the plots
        $lineplot->SetLegend('Plot 1');
        $lineplot2->SetLegend('Plot 2');

        // Adjust the legend position
        $graph->legend->SetPos(0.08, 0.08, 'right', 'top');

        // Display the graph
        $graph->Stroke();
    }

    public function array_length($data)
    {
        static $count;
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $this->array_length($v);
            } else {
                $count++;
            }
        }
        return $count;
    }

}
