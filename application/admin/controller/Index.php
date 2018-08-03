<?php

namespace app\admin\controller;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;


class Index
{
    public function index()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );


        // dump(url('createImage'));
        // die;

        dump(config('export_config.www') . url('createImage'));
        $source = file_get_contents(config('export_config.www') . url('createImage'));

        $section->addImage($source, ['width' => 350 * 0.75, 'height' => 250 * 0.75]);
        $doc = '../hello.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($doc);

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

}
