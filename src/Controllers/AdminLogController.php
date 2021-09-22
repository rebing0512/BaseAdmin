<?php

namespace Jenson\BaseAdmin\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Exceptions\LaravelExcelException;
use Jenson\BaseAdmin\Models\AdminLog;
use Maatwebsite\Excel\Facades\Excel;

class AdminLogController extends JensonBaseAdminController
{
    public function list(Request $request){
        if($request->isMethod('post')){
            // 要执行的代码
            $query = AdminLog::query()->with(['admin'])->latest();
            $keywords = $request->get('keywords','');
            if($keywords){// 管理员名称
                $query->whereIn('admin_id',function($query) use($keywords){
                    $query->where('username','like','%'.$keywords.'%')->select('id')->from('mbadmin_admins');
                });
            }
            //dd("22");
            //limit	10        page	0
            $pageSize = $request->get('limit',10);
            // 计算offset值
            $offset = $request->get('offset',0);
            //总数
            $total = $query->count();
            $data = $query->limit($pageSize)->offset($offset)->get()->toArray();
            if($data){
                foreach($data as $key=>$val){
                    $data[$key]['username'] = $val['admin']['username'];
                }
            }
            return $this->ret(['total'=>$total,'data'=>$data]);
        }
        $subtitle = '操作日志';
        $nav = 'admin_log';
        # 指定时间段
        $interval = [
            'all'=>'指定时间数据导出',
            'one_week'=>'近一周',
            'one_month'=>'近一个月',
            'three_month'=>'近三个月',
            'six_month'=>'近半年',
            'one_year'=>'近一年',
        ];
        return view('mbcore.baseadmin::admin.log.list',compact('subtitle','nav','interval'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Log Delete
     */
    public function delete($id){
        $data = AdminLog::query()->find($id);
        try{
            if(!$data){
                return $this->retErr("信息不存在！",2);
            }
            $data->delete();  //::withTrashed()->get();
            if($data->trashed()){
                $msg = '删除成功！';
                return $this->ret($msg);
                //dd($data);
            }else{
                $msg =  '删除失败！';
                return $this->retErr($msg);
            }
        }catch (\Exception $e){
            return $this->retErr($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * Excel 数据导出
     */
    public function excel_export(Request $request){
        # 数据选择类型：1-手动选择起始和结束时间【默认】，2-根据指定时间段导出数据
        $type = $request->get('type',1);
        $query = AdminLog::query()->with(['admin']);
        if($type == 1) {
            $start_time = $request->get('start_time');
            if (empty($start_time)) {
                $start_time = Carbon::now()->startOfDay()->toDateTimeString();
            } else {
                $start_time = Carbon::parse($start_time)->startOfDay()->toDateTimeString();
            }
            $end_time = $request->get('end_time');
            if (empty($end_time)) {
                $end_time = Carbon::now()->endOfDay()->toDateTimeString();
            } else {
                $end_time = Carbon::parse($end_time)->endOfDay()->toDateTimeString();
            }
            if (!$start_time) {
                $query->whereBetween('created_at', [$start_time, $end_time]);
            }
            $time = date('Y年m月d日', strtotime($start_time)) . '——' . date('Y年m月d日', strtotime($end_time));
            $data = $query->whereBetween('created_at', [$start_time, $end_time])->get();
        }else{
            $interval = $request->get('interval','all');
            switch($interval){
                case 'one_week':
                    $day_start =  Carbon::now()->subWeeks(1)->startOfDay()->toDateTimeString();
                    break;
                case 'one_month':
                    $day_start =  Carbon::now()->subMonths(1)->startOfDay()->toDateTimeString();
                    break;
                case 'three_month':
                    $day_start =  Carbon::now()->subMonths(3)->startOfDay()->toDateTimeString();
                    break;
                case 'six_month':
                    $day_start =  Carbon::now()->subMonths(6)->startOfDay()->toDateTimeString();
                    break;
                case 'one_year':
                    $day_start =  Carbon::now()->subYears(1)->startOfDay()->toDateTimeString();
                    break;
            }
            $day_end = Carbon::now()->endOfDay()->toDateTimeString();
            $time = date('Y年m月d日',strtotime($day_start)).'——'.date('Y年m月d日',strtotime($day_end));
            $data = $query->whereBetween('created_at',[$day_start,$day_end])->get();
        }
        #              A       B           C        D        E
        $cellData = [['ID','管理员名称','操作行为','IP地址','操作时间']];
        foreach($data as $key => $val){
            $username = $val['admin']['username'];
            if($val['operation']){
                $operation = $val['operation'];
            }else{
                $operation = '──';
            }
            $create = Carbon::parse($val['created_at'])->toDateTimeString();
            $cellData[] = [$val['id'],$username,$operation,$val['ip'],$create];
        }
        $title = $time.'操作日志';
        try {
            Excel::create($title, function ($excel) use ($cellData, $title) {
                $excel->sheet('操作日志', function ($sheet) use ($cellData, $title) {
                    # 设置Excel每一列的宽度
                    $sheet->rows($cellData)
                        # 冻结首行
                        ->freezeFirstRow()
                        # 此为设置整体样式 *
                        ->setStyle([
                            'font' => [
                                'name' => 'Calibri',
                                'size' => 12,
                                'bold' => false,
                            ]
                        ])
                        # 设置标题的样式
                        ->row(1, function ($row) {
                            /** @var CellWriter $row */
                            $row->setFont(array(
                                'family' => 'Calibri',
                                'size' => '14',
                                'bold' => true,
                            ))->setAlignment('center')->setValignment('center')->setBackground('#CCCCCC');
                        })
                        # 首行行高
                        ->setHeight(1, 27)
                        # 设置每一列的宽
                        ->setWidth([
                            'A' => 8,
                            'B' => 18,
                            'C' => 22,
                            'D' => 20,
                            'E' => 28
                        ]);
                });

            })->export('xls');
        } catch (LaravelExcelException $e) {
            throw new LaravelExcelException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * 检查Excel倒数的数据是否存在
     */
    public function check_data(Request $request){
        # 数据选择类型：1-手动选择起始和结束时间【默认】，2-根据指定时间段导出数据
        $type = $request->get('type',1);
        $query = AdminLog::query()->with(['admin']);
        if($type == 1) {# 手动选择起始和结束时间
            $start_time = $request->get('start_time');
            if (empty($start_time)) {
                $start_time = Carbon::now()->startOfDay()->toDateTimeString();
            } else {
                $start_time = Carbon::parse($start_time)->startOfDay()->toDateTimeString();
            }
            $end_time = $request->get('end_time');
            if (empty($end_time)) {
                $end_time = Carbon::now()->endOfDay()->toDateTimeString();
            } else {
                $end_time = Carbon::parse($end_time)->endOfDay()->toDateTimeString();
            }
            if (!$start_time) {
                $query->whereBetween('created_at', [$start_time, $end_time]);
            }
            $time = date('Y年m月d日', strtotime($start_time)) . '——' . date('Y年m月d日', strtotime($end_time));
            $data = $query->whereBetween('created_at', [$start_time, $end_time])->get();
        }else{# 根据指定时间段导出数据
            $interval = $request->get('interval','all');
            switch($interval){
                case 'one_week':
                    $day_start =  Carbon::now()->subWeeks(1)->startOfDay()->toDateTimeString();
                    break;
                case 'one_month':
                    $day_start =  Carbon::now()->subMonths(1)->startOfDay()->toDateTimeString();
                    break;
                case 'three_month':
                    $day_start =  Carbon::now()->subMonths(3)->startOfDay()->toDateTimeString();
                    break;
                case 'six_month':
                    $day_start =  Carbon::now()->subMonths(6)->startOfDay()->toDateTimeString();
                    break;
                case 'one_year':
                    $day_start =  Carbon::now()->subYears(1)->startOfDay()->toDateTimeString();
                    break;
            }
            $day_end = Carbon::now()->endOfDay()->toDateTimeString();
            $time = date('Y年m月d日',strtotime($day_start)).'——'.date('Y年m月d日',strtotime($day_end));
            $data = $query->whereBetween('created_at',[$day_start,$day_end])->get();
        }
        if(count($data) == 0){
            $code = 0;
            $msg = $time.'没有任何数据，请重新选择时间！';
        }else{
            $code  = 1;
            $msg = 'Data Exists';
        }
        return [
            'code'=>$code,
            'result'=>[
                'msg'=>$msg
            ]
        ];
    }
}