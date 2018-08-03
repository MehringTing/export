<?php
namespace app\index\controller;

use think\Controller;
use think\Request;





class Base extends Controller
{
    public $json_em;

    public $json_h5;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $code = $request->param('code');

    }

    public function getEastMoneyJson($code)
    {
        $url_em = 'http://emweb.securities.eastmoney.com/NewFinanceAnalysis/MainTargetAjax?ctype=4&type=0&code=SZ002425';
        $path_em = 'E:\report\public\eastmoney\002425.json';
        if (file_exists($path_em)) {
            $rs_em = file_get_contents($path_em);
        } else {
            $rs_em = file_get_contents($url_em);
            $fhandle = fopen($path_em, 'a+');
            fwrite($fhandle, $rs_em);
            fclose($fhandle);
        }
        $data_em = json_decode($rs_em, true);
    }

    public function getH5Json($code)
    {
        $url_h5 = 'http://www.ubd2.com/financial.php/financial/index?company_id=002425&year=2018&quarter=q2&limit=9';
        $path_h5 = 'E:\report\public\h5\002425.json';
        if (file_exists($path_h5)) {
            $rs_h5 = file_get_contents($path_h5);
        } else {
            $rs_h5 = file_get_contents($url_h5);
            $fhandle = fopen($path_h5, 'a+');
            fwrite($fhandle, $rs_h5);
            fclose($fhandle);
        }
        $data_my = json_decode($rs_h5, true);
        $data_my = $data_my['data'];
    }

    //营业总收入
    public function getYYZSR($data)
    {
        if (empty($data)) {
            return;
        }
        $arr = [];
        foreach ($data as $key => $value) {
            $arr[$value['year_quarter']] = [
                'year_quarter' => $value['year_quarter'],
                'num' => $value['num'],
                'month_on_month' => $value['month_on_month'],
                'last_month_is_null' => $value['last_month_is_null'],
                'year_on_year' => $value['year_on_year'],
                'last_year_is_null' => $value['last_year_is_null']
            ];
        }
        return $arr;
    }

    //净利润
    public function getJLR($data)
    {
        if (empty($data)) {
            return;
        }
        $arr = [];
        foreach ($data as $key => $value) {
            $arr[$value['year_quarter']] = [
                'jlr_h5' => $value['num'],
            ];
        }
        return $arr;
    }
}