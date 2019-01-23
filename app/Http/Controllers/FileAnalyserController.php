<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/11/2019
 * Time: 4:36 PM
 */

namespace App\Http\Controllers;

use App\Services\ConfigAnalyser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileAnalyserController extends Controller
{
    use ConfigAnalyser;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayForm()
    {
        return view('file_analyser_result');
    }


    public function analyseConfigFilesAllRequest(Request $request)
    {
        if($request->has('start')){
            $this->starting_point = (int)$request->get('start');
        }

        if($request->has('files')){
            $files = (int)$request->get('files');
            if ($files < 10){
                $this->number_of_files = $files;
            }
        }

        try {

            $this->analyseConfigAll();

            return view('file_analyser_result')->with(['layer_2_report' => asset($this->filename_l2),
                'layer_3_report' => asset($this->filename_l3)]);

        } catch (\Exception $exception) {
            print 'Something went wrong, error:' . $exception->getTraceAsString();
        }
    }

    public function getDirectories(){
        $directories = Storage::disk('ftp')->directories();
        return response(json_encode($directories));
    }

    public function analyseConfigFileRequest(Request $request){
        try {

            $this->analyseConfigFile($request);

            return response(json_encode($this->report));

        } catch (\Exception $exception) {
            print 'Something went wrong, error:' . $exception->getTraceAsString();
        }
    }

}