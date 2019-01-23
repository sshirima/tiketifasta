<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/11/2019
 * Time: 4:36 PM
 */

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

trait ConfigAnalyser
{
    protected $csv_file_report_l2;
    protected $csv_file_report_l3;
    protected $filename_l2 = 'layer_2_report.csv';
    protected $filename_l3 = 'layer_3_report.csv';

    protected $starting_point = 0;
    protected $number_of_files = 10;

    protected $extraction_keys = [
        'hostname' => [
            'head' => 'Router name',
            'content' => ''
        ],

        'interface' => [
            'head' => 'Interface',
            'content' => ''
        ],
        'service_instance' => [
            'head' => 'Service instance id',
            'content' => ''
        ],
        'shutdown' => [
            'head' => 'Admin status',
            'content' => ''
        ],
        'encapsulation' => [
            'head' => 'Vlan id',
            'content' => null
        ],
        'bandwidth' => [
            'head' => 'Bandwidth',
            'content' => ''
        ],
        'vbid' => [
            'head' => 'VB id',
            'content' => ''
        ],
        'service_policy_output' => [
            'head' => 'Service policy out',
            'content' => null
        ],

        'service_policy_input' => [
            'head' => 'Service policy in',
            'content' => ''
        ],
        'bridge_domain' => [
            'head' => 'Bridge domain',
            'content' => ''
        ],
        'description' => [
            'head' => 'Description',
            'content' => ''
        ],
    ];

    protected $output_file_column_keys_l3 = ['hostname', 'interface', 'shutdown', 'encapsulation', 'bandwidth', 'vbid', 'service_policy_output', 'service_policy_input', 'description'];
    protected $output_file_column_keys_l2 = ['hostname', 'service_instance', 'encapsulation', 'bridge_domain', 'vbid', 'service_policy_output', 'service_policy_input', 'description'];


    protected $report = [
        'router' => [
            'interfaces' => [],
            'parsed_interfaces' => [],
            'service_instances' => [],
            'parsed_service_instances' => [],
        ]
    ];
    protected $routers = [];
    protected $router = [];
    protected $interfaces = [];
    protected $interface = [];

    public function analyseConfigAll()
    {
        $start = microtime(true);

        try {
            $this->readConfigFileAll();

            $this->printExecutionOutput($start);


        } catch (\Exception $exception) {
            //print 'Something went wrong, error:' . json_encode($exception->getTrace());
        }
    }

    protected $create_new_file = false;

    public function analyseConfigFile(Request $request)
    {
        $start = microtime(true);

        try {

            $this->create_new_file = $request->get('new_file', false) == 'true' ? true : false;

            $this->readConfigFile($request->get('filename'), (bool)$request->get('new_file', false));

            $this->printExecutionOutput($start);

        } catch (\Exception $exception) {
            //print 'Something went wrong, error:' . json_encode($exception->getTrace());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array("b", "kb", "mb", "gb", "tb");

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . " " . $units[$pow];
    }

    private function readTheFile2($path)
    {
        $lines = [];
        $handle = fopen($path, "r");

        while (!feof($handle)) {
            $lines[] = trim(fgets($handle));
        }

        fclose($handle);
        return $lines;
    }

    private function readTheFile($path)
    {
        $handle = fopen($path, "r");

        while (!feof($handle)) {
            yield trim(fgets($handle));
        }

        fclose($handle);
    }

    /**
     * @param $string_line
     */
    private function checkHostname($string_line)
    {
        if (preg_match("/^hostname/i", $string_line)) {
            $exploded = explode(' ', $string_line);
            if (array_key_exists(1, $exploded)) {
                $this->extraction_keys['hostname']['content'] = $exploded[1];
                $this->report['router']['name'] = $exploded[1];
            }
        }
    }

    /**
     * @param $int
     */
    private function getVlanId($int)
    {
        $vlan = null;

        if (array_key_exists('encapsulation', $int)) {
            $this->extraction_keys['encapsulation']['content'] = $int['encapsulation'];
        }
    }

    /**
     * @param $int
     */
    private function extractDescription($int)
    {
        $description = null;
        $vbid = null;
        if (array_key_exists('description', $int)) {
            $this->extraction_keys['description']['content'] = ltrim($int['description']);
            //Get the VB ID
            if (str_is('*VB*', $int['description'])) {
                $desc = $int['description'];
                $this->extraction_keys['vbid']['content'] = substr($desc, strpos($desc, "VB"));
            }
        }
    }

    /**
     * @param $int
     */
    private function getAdminStatus($int)
    {
        if (array_key_exists('shutdown', $int)) {
            $this->extraction_keys['service_instance']['content'] = $int['shutdown'];
        }
    }

    /**
     * @param $int
     */
    private function getBridgeDomain($int)
    {
        if (array_key_exists('bridge_domain', $int)) {
            $this->extraction_keys['bridge_domain']['content'] = $int['bridge_domain'];
        }
    }

    /**
     * @param $int
     */
    private function getBandwidth($int)
    {
        if (array_key_exists('bandwidth', $int)) {
            $this->extraction_keys['bandwidth']['content'] = $int['bandwidth'];
        }
    }

    /**
     * @param $int
     */
    private function getServicePolicyOut($int)
    {
        if (array_key_exists('service_policy_output', $int)) {
            $this->extraction_keys['service_policy_output']['content'] = $int['service_policy_output'];
        }
    }


    /**
     * @param $int
     */
    private function getServicePolicyIn($int)
    {
        if (array_key_exists('service_policy_input', $int)) {
            $this->extraction_keys['service_policy_input']['content'] = $int['service_policy_input'];
        }

    }

    private function putContentCsvFile($column_format, $type = 'interface')
    {
        $content= '';
        if (isset($this->extraction_keys['encapsulation']['content']) && isset($this->extraction_keys['service_policy_output']['content'])) {
            if ($type == 'interface') {
                $content = $column_format.'\n';
                $this->report['router']['parsed_interfaces'][] = $this->extraction_keys['interface']['content'];
            } else if ($type == 'service_instance') {
                //fputcsv($output_csv, $column_format);
                $content = $column_format.'\n';
                $this->report['router']['parsed_service_instances'][] = $this->extraction_keys['service_instance']['content'];
            }
        } else {
            if ($type == 'interface') {
                $this->report['router']['interfaces'][] = $this->extraction_keys['interface']['content'];
            } else if ($type == 'service_instance') {
                $this->report['router']['service_instances'][] = $this->extraction_keys['service_instance']['content'];
            }
        }
        $this->extraction_keys['encapsulation']['content'] = null;
        $this->extraction_keys['service_policy_output']['content'] = null;

        return $content;
    }

    /**
     * @param $start
     */
    private function printExecutionOutput($start): void
    {
        $time_diff = (microtime(true) - $start);
        //print 'Configuration file analysis completed successful, memory usage: ' . $this->formatBytes(memory_get_peak_usage()) . ', execution time: ' . sprintf('%0.2f', $time_diff) . ' sec' . '<br>';
    }


    private function createLayerCsvFile($filename, $heading, $newFile = false)
    {
        $newFile = $this->create_new_file;

        if ($newFile) {
            $output_csv = fopen($filename, 'w');
            fputcsv($output_csv, $heading);
        } else {
            if(file_exists($filename)){
                $output_csv = fopen($filename, 'a');
            } else {
                $output_csv = fopen($filename, 'a');
                fputcsv($output_csv, $heading);
            }

        }

        return $output_csv;
    }

    /**
     * @param $interfaces
     */
    private function generateLayer3InfoFile($interfaces): void
    {
        if (count($interfaces) > 0) {

            $output_csv = $this->createLayerCsvFile($this->filename_l3, $this->getCsvData($this->output_file_column_keys_l3, 'head'));

            foreach ($interfaces as $key => $int) {
                $this->report['router']['interfaces'][] = $int;
                $description = '';
                $vbid = '';

                if (array_key_exists('description', $int)) {
                    $description = ltrim($int['description']);
                    //Get the VB ID
                    if (str_is('*VB*', $int['description'])) {
                        $desc = $int['description'];
                        $vbid = substr($desc, strpos($desc, "VB"));
                    }
                }

                $name = $this->report['router']['name'];
                $encp = array_key_exists('encapsulation', $int)?$int['encapsulation']:null;
                $interf = array_key_exists('interface', $int)?$int['interface']:null;
                $shut = array_key_exists('shutdown', $int)?$int['shutdown']:null;
                $band = array_key_exists('bandwidth', $int)?$int['bandwidth']:null;
                $srvout = array_key_exists('service_policy_output', $int)?$int['service_policy_output']:null;
                $srvin = array_key_exists('service_policy_input', $int)?$int['service_policy_input']:null;
                $row=[];
                if(isset($encp) && isset($srvout)){
                    $this->report['router']['parsed_interfaces'][] = $int;
                    $row[] = $name;
                    $row[] = $interf;
                    $row[] = $shut;
                    $row[] = $encp;
                    $row[] = $band;
                    $row[] = $vbid;
                    $row[] = $srvout;
                    $row[] = $srvin;
                    $row[] = $description;
                    fputcsv($output_csv, $row);
                }
            }
            fclose($output_csv);
        }
    }

    private function getCsvData(array $keys, $type = 'content')
    {
        $output = [];
        foreach ($keys as $key) {
            $output[] = $this->extraction_keys[$key][$type];
        }
        return $output;
    }

    /**
     * @param $service_instances
     */
    private function generateLayer2InfoFile($service_instances): void
    {
        if (count($service_instances) > 0) {

            $output_csv = $this->createLayerCsvFile($this->filename_l2, $this->getCsvData($this->output_file_column_keys_l2, 'head'));

            foreach ($service_instances as $service_inst) {
                $this->report['router']['service_instances'][] = $service_inst;
                $description = '';
                $vbid = '';
                if (array_key_exists('description', $service_inst)) {
                    $description = ltrim($service_inst['description']);
                    //Get the VB ID
                    if (str_is('*VB*', $service_inst['description'])) {
                        $desc = $service_inst['description'];
                        $vbid = substr($desc, strpos($desc, "VB"));
                    }
                }

                $name = $this->report['router']['name'];
                $serviIns = array_key_exists('service_instance', $service_inst)?$service_inst['service_instance']:null;
                $encp = array_key_exists('encapsulation', $service_inst)?$service_inst['encapsulation']:null;
                $bridg = array_key_exists('bridge_domain', $service_inst)?$service_inst['bridge_domain']:null;
                $srvout = array_key_exists('service_policy_output', $service_inst)?$service_inst['service_policy_output']:null;
                $srvin = array_key_exists('service_policy_input', $service_inst)?$service_inst['service_policy_input']:null;

                $row=[];
                if(isset($encp) && isset($srvout)){
                    $this->report['router']['parsed_service_instances'][] = $service_inst;
                    $row[] = $name;
                    $row[] = $serviIns;
                    $row[] = $encp;
                    $row[] = $bridg;
                    $row[] = $vbid;
                    $row[] = $srvout;
                    $row[] = $srvin;
                    $row[] = $description;
                    fputcsv($output_csv, $row);
                }
            }

            fclose($output_csv);
        }
    }

    /**
     * @return string
     */
    private function getSubDirectoryName(): string
    {
        $yesterday = Carbon::now()->subDays(1);
        $sub_dir = $yesterday->month . '-' . $yesterday->day . '-' . $yesterday->year;
        return $sub_dir;
    }

    /**
     * @param $file_content_array
     */
    private function analyseFileContents($file_content_array): void
    {
        $interface = array();
        $service_instance = array();
        $interfaces = array();
        $service_instances = array();
        $is_interface = false;
        $is_service_instance = false;

        foreach ($file_content_array as $string_line) {

            ////print $string_line.'<br>';
            $string_line = ltrim($string_line);

            $this->checkHostname($string_line);

            if ($is_interface) {

                if (preg_match("/^description/i", $string_line, $matches)) {
                    $interface['description'] = substr($string_line, 11, strlen($string_line));
                    continue;
                }

                if (preg_match("/^encapsulation/i", $string_line, $matches)) {
                    $interface['encapsulation'] = $this->getConfigVlanId($string_line);
                    continue;
                }

                if (preg_match("/^bandwidth/i", $string_line)) {
                    $interface['bandwidth'] = $this->getConfigBandwidth($string_line);
                    continue;
                }

                if (preg_match("/^service-policy output/i", $string_line, $matches)) {
                    $interface['service_policy_output'] = $this->getConfigServicePolicyValue($string_line);
                    continue;
                }

                if (preg_match("/^service-policy input/i", $string_line, $matches)) {
                    $interface['service_policy_input'] = $this->getConfigServicePolicyValue($string_line);
                    continue;
                }


                if (preg_match("/^shutdown/i", $string_line, $matches)) {
                    $interface['shutdown'] = $this->getConfigInterfaceShutDown($string_line);
                    continue;
                }

                if (preg_match("/^!/i", $string_line, $matches)) {
                    $is_interface = !$is_interface;
                    $interfaces[] = $interface;
                    $interface = [];
                    continue;
                }
            }

            if ($is_service_instance) {

                if (preg_match("/^description/i", $string_line)) {
                    $service_instance['description'] = substr(ltrim($string_line), 11, strlen($string_line));
                    continue;
                }

                if (preg_match("/^encapsulation/i", $string_line)) {
                    $service_instance['encapsulation'] = $this->getConfigVlanId($string_line);
                    continue;
                }

                if (preg_match("/^bridge-domain/i", $string_line)) {
                    $service_instance['bridge_domain'] = $this->getConfigBridgeDomain($string_line);
                    continue;
                }

                if (preg_match("/^service-policy output/i", $string_line, $matches)) {
                    $service_instance['service_policy_output'] = $this->getConfigServicePolicyValue($string_line);
                    continue;
                }

                if (preg_match("/^service-policy input/i", $string_line, $matches)) {
                    $service_instance['service_policy_input'] = $this->getConfigServicePolicyValue($string_line);
                    continue;
                }


                if (preg_match("/^shutdown/i", $string_line, $matches)) {
                    $service_instance['shutdown'] = $this->getConfigInterfaceShutDown($string_line);
                    continue;
                }

                if (preg_match("/^!/i", $string_line, $matches)) {
                    $is_service_instance = !$is_service_instance;
                    $service_instances[] = $service_instance;
                    $service_instance = [];
                    continue;
                }
            }

            list($interface, $is_interface) = $this->isInterface($string_line, $interface, $is_interface);

            list($service_instance, $is_service_instance) = $this->isServiceInstance($string_line, $service_instance, $is_service_instance);

        }

        $this->generateLayer3InfoFile($interfaces);

        $this->generateLayer2InfoFile($service_instances);
    }

    /**
     * @param $string_line
     * @param $interface
     * @param $is_interface
     * @return array
     */
    private function isInterface($string_line, $interface, $is_interface): array
    {
        if (preg_match("/^interface/i", $string_line)) {
            $exploded = explode(' ', $string_line);
            if (array_key_exists(1, $exploded)) {
                $interface['interface'] = $exploded[1];
            }
            $is_interface = !$is_interface;
        }
        return array($interface, $is_interface);
    }

    /**
     * @param $string_line
     * @param $service_instance
     * @param $is_service_instance
     * @return array
     */
    private function isServiceInstance($string_line, $service_instance, $is_service_instance): array
    {
        if (preg_match("/^service instance/i", $string_line)) {
            $exploded = explode(' ', $string_line);
            if (array_key_exists(2, $exploded)) {
                $service_instance['service_instance'] = $exploded[2];
            }
            $is_service_instance = !$is_service_instance;
        }
        return array($service_instance, $is_service_instance);
    }

    /**
     * @param $filename
     * @param $createNewFile
     */
    private function readConfigFile($filename, $createNewFile): void
    {
        /*$this->csv_file_report_l3 = $this->createLayerCsvFile($this->filename_l3, $this->getCsvData($this->output_file_column_keys_l3, 'head'),$createNewFile);
        $this->csv_file_report_l2 = $this->createLayerCsvFile($this->filename_l2, $this->getCsvData($this->output_file_column_keys_l3, 'head'),$createNewFile);*/


        $file_content_string = Storage::disk('ftp')->get($filename . '/' . $this->getSubDirectoryName() . '/' . $filename . '-' . 'Running.Config');
        $this->analyseFileContents(explode("\n", $file_content_string));

        /*fclose($this->csv_file_report_l3);
        fclose($this->csv_file_report_l2);*/
    }

    /**
     * Reading Configuration files from backup server by FTP
     */
    private function readConfigFileAll(): void
    {
        //print 'Reading files from backup server...' . '<br>';
        $directories = Storage::disk('ftp')->directories();

        /*$this->csv_file_report_l3 = $this->createLayerCsvFile($this->filename_l3, $this->getCsvData($this->output_file_column_keys_l3, 'head'));
        $this->csv_file_report_l2 = $this->createLayerCsvFile($this->filename_l2, $this->getCsvData($this->output_file_column_keys_l3, 'head'));*/

        //print count($directories).' files found'.'<br>';

        foreach ($directories as $key => $directory) {
            if ($key > $this->starting_point && $key < ($this->starting_point + $this->number_of_files)) {

                $file_content_string = Storage::disk('ftp')->get($directory . '/' . $this->getSubDirectoryName() . '/' . $directory . '-' . 'Running.Config');
                $this->analyseFileContents(explode("\n", $file_content_string));
                //print 'Analyse config for router: '.$this->extraction_keys['hostname']['content'].'<br>';
            } else if ($key > ($this->starting_point + $this->number_of_files)) {
                break;
            } else {
                continue;
            }

        }
        /*close($this->csv_file_report_l3);
        fclose($this->csv_file_report_l2);*/
    }

    /**
     * @param $string_line
     * @return null
     */
    private function getConfigVlanId($string_line)
    {
        return $this->getParameterValue($string_line, 2);
    }

    /**
     * @param $string_line
     * @return null
     */
    private function getConfigBandwidth($string_line)
    {
        return $this->getParameterValue($string_line, 1);
    }

    /**
     * @param $string_line
     * @return null
     */
    private function getConfigServicePolicyValue($string_line)
    {
        return $this->getParameterValue($string_line, 2);
    }

    /**
     * @param $string_line
     * @return null
     */
    private function getConfigInterfaceShutDown($string_line)
    {
        return $this->getParameterValue($string_line, 0);
    }

    /**
     * @param $string_line
     * @return null
     */
    private function getConfigBridgeDomain($string_line)
    {
        return $this->getParameterValue($string_line, 1);
    }

    private function getParameterValue($string_line, $pos)
    {
        $exploded = explode(' ', $string_line);
        if (array_key_exists($pos, $exploded)) {
            return $exploded[$pos];
        }
        return null;
    }
}