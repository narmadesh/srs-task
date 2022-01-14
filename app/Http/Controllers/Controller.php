<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Controller extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index()
    {
        $data = DB::table('excel_data')->get();
        return view('welcome', compact('data'));
    }

    public function upload(Request $request)
    {
        $the_file = $request->file('File');
        try
        {
            $allow_extension = array("xls","xlsx");
            $file_array = explode(".",$the_file->getClientOriginalName());
            $file_extension = end($file_array);
            if(in_array($file_extension,$allow_extension))
            {
                
                    $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($the_file);
                    $reader = IOFactory::createReader($file_type);
                    $spreadsheet = $reader->load($the_file);
                    $data = $spreadsheet->getActiveSheet()->toArray();
                    $total = count($data);
                    $file_data=array();
                    for($i=0;$i<=$total-1;$i++)
                    {
                        if(is_string($data[$i][0])===true || is_string($data[$i][1])===true)
                        {
                            return response()->json(['response'=>'error','message'=>'Alphabets are not allowed']);
                            exit;
                        }
                        else
                        {
                            array_push($file_data,['Column_A'=>$data[$i][0],'Column_B'=>$data[$i][1],'Column_C'=>$data[$i][0]+$data[$i][1]]);
                        }
                    }
                    if(!empty($file_data))
                    {
                        $insert=DB::table('excel_data')->insert($file_data);
                        if($insert)
                        {
                            return response()->json(['response'=>'success','message'=>'Data uploaded successfully']);
                        }
                        else
                        {
                            return $request->json(['response'=>'error','message'=>'Failed to upload data']);
                        }
                    }
                    
            }
        }
        catch (Exception $e) {
           $error_code = $e->errorInfo[1];
           return $error_code;
       }
    }
}
