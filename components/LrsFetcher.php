<?php

namespace app\components;

use Yii;
use yii\base\Component;
use micro\models\Lrsapp; // adjust if your model is named differently
use yii\queue\Queue;

class LrsFetcher  extends Component
{

    /**
     * Fetch LRS data for a single application number.
     * Call this from job or directly if you’re not using queue.
     */
    public function fetchSingle($applicationNumber)
    {
        // Example scraping or API logic here
        $url = 'https://lrs.telangana.gov.in/LRS/OnlineStatus';
        $data = $this->scrapeApplicationData($url, $applicationNumber);

        if ($data) {
            $model = new Lrsapp();
            $model->application_number = $applicationNumber;
            $model->data = json_encode($data); // Adjust if you're storing structured fields
            $model->save(false);
        }
    }
    /**
     * Fetch and process LRS applications in a range.
     */
    public function fetchRange($prefix, $start, $end)
    {
        for ($i = $start; $i <= $end; $i++) {
            $appNumber = sprintf('%s/%06d', $prefix, $i);

            // Skip if already exists
            if (LrsApplication::find()->where(['application_number' => $appNumber])->exists()) {
                continue;
            }

            // Enqueue or fetch immediately depending on your setup
            Yii::$app->queue->push(new \app\jobs\FetchLrsDataJob([
                'applicationNumber' => $appNumber,
            ]));
        }
    }
    /**
     * Dummy function to simulate scraping/parsing logic.
     */
    protected function scrapeApplicationData($url, $applicationNumber)
    {
        // Replace with CURL/Guzzle/scraping logic
        return [
            'status' => 'Sample Status',
            'applicant_name' => 'Sample Name',
            'application_number' => $applicationNumber,
            'fetched_at' => date('Y-m-d H:i:s'),
        ];
    }

    public function fetchLRSData($LrsApplNo, $cookieFile)
    {
        // $cookieFile = tempnam(sys_get_temp_dir(), 'cookie');
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Connection: keep-alive',
            'Referer: https://lrs.telangana.gov.in/layouts/Citizen_Downloads.aspx',
        ];

        // STEP 0: GET the initial page to grab ViewState, cookies, etc.
        $initialUrl = 'https://lrs.telangana.gov.in/layouts/Citizen_Downloads.aspx';
        $ch = curl_init($initialUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_USERAGENT => $headers[0],
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $initialHtml = curl_exec($ch);
        curl_close($ch);

        // Extract ViewState and EventValidation
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($initialHtml);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        /*  function getInputValue($xpath, $name)
    {
        $input = $xpath->query("//input[@name='$name']")->item(0);
        return $input ? $input->getAttribute('value') : '';
    }*/
        /*  $getInputValue = function ($name) use ($xpath) {
        $input = $xpath->query("//input[@name='$name']")->item(0);
        return $input ? $input->getAttribute('value') : '';
    };*/

        //    $viewstate = $getInputValue('__VIEWSTATE');
        //    $eventValidation = $getInputValue('__EVENTVALIDATION');
        //    $viewstategen = $getInputValue('__VIEWSTATEGENERATOR');

        $viewstate = $this->getInputValue($xpath, '__VIEWSTATE');
        $eventValidation = $this->getInputValue($xpath, '__EVENTVALIDATION');
        $viewstategen = $this->getInputValue($xpath, '__VIEWSTATEGENERATOR');

        // STEP 1: Simulate selecting the radio button
        $step1Url = $initialUrl;
        $step1Data = [
            '__VIEWSTATE' => $viewstate,
            '__VIEWSTATEGENERATOR' => $viewstategen,
            '__EVENTVALIDATION' => $eventValidation,
            '__EVENTTARGET' => 'ctl00$ContentPlaceHolder1$rbtnappnum',
            '__EVENTARGUMENT' => '',
            'ctl00$ContentPlaceHolder1$rbtnappnum' => '1',
        ];

        $ch = curl_init($step1Url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($step1Data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $html = curl_exec($ch);
        curl_close($ch);

        // Save for debug
        // file_put_contents("after_radio_selection.html", $html);

        // STEP 2: Extract form after radio selection
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        $form = $dom->getElementsByTagName('form')->item(0);
        if (!$form) {
            die("❌ Form not found after radio selection. Check HTML content.");
        }

        $action = $form->getAttribute('action');
        $formUrl = 'https://lrs.telangana.gov.in/layouts/' . ltrim($action, '/');

        //   $viewstate = $getInputValue('__VIEWSTATE');
        //    $eventValidation = $getInputValue('__EVENTVALIDATION');
        //    $viewstategen = $getInputValue('__VIEWSTATEGENERATOR');
        // Extract updated hidden fields again
        $viewstate = $this->getInputValue($xpath, '__VIEWSTATE');
        $eventValidation = $this->getInputValue($xpath, '__EVENTVALIDATION');
        $viewstategen = $this->getInputValue($xpath, '__VIEWSTATEGENERATOR');

        // Build form data
        $formData = [
            '__VIEWSTATE' => $viewstate,
            '__VIEWSTATEGENERATOR' => $viewstategen,
            '__EVENTVALIDATION' => $eventValidation,
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            'ctl00$ContentPlaceHolder1$rbtnappnum' => '1',
            'ctl00$ContentPlaceHolder1$txtappno' => $LrsApplNo, // Your application number
            'ctl00$ContentPlaceHolder1$btnsubmit' => 'Submit', // Submit button name + value
        ];

        // STEP 3: Submit the filled form
        $ch = curl_init($formUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($formData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $finalResponse = curl_exec($ch);
        curl_close($ch);
        unlink($cookieFile);

        // STEP 4: Output the final result
        //   echo '<h3>Final Response</h3>';
        //   echo '<pre>' . htmlspecialchars($finalResponse) . '</pre>';
        file_put_contents('intermOutput.html', $finalResponse);
        // STEP 3.5: Load final form (the one that has submit button and form values shown)
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($finalResponse);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        // Re-parse the form (just in case it's a new one)
        $form = $dom->getElementsByTagName('form')->item(0);
        if (!$form) {
            die("❌ Final form not found. Check if it's JS-rendered.");
        }

        $action = $form->getAttribute('action');
        $formUrl = 'https://lrs.telangana.gov.in/layouts/' . ltrim($action, '/');

        // Extract hidden + filled inputs
        $formData = [];
        $inputs = $xpath->query("//form//input");

        foreach ($inputs as $input) {
            $name = $input->getAttribute('name');
            $value = $input->getAttribute('value');

            if (!$name) continue;

            // You can override any values here if needed
            $formData[$name] = $value;
        }
        $formData['ctl00$ContentPlaceHolder1$rbtnappnum'] = 1;
        // You may need to manually add a submit button name/value again
        $formData['ctl00$ContentPlaceHolder1$btn_submit'] = 'Submit'; // <-- replace with real name if different

        // STEP 4: Submit final form
        $ch = curl_init($formUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($formData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $finalOutput = curl_exec($ch);
        curl_close($ch);
        unlink($cookieFile);

        return $finalOutput;
    }

    public function extractTableData($html)
    {
        $dom = new \DOMDocument();
        //  $lrsapp = new Lrsapp();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        $spans = $xpath->query("//td/span[@id]");
        $data = [];

        /*  foreach ($spans as $span) {
            $id = $span->getAttribute("id");
            $value = trim($span->textContent);
            $data[$id] = $value;
        } */
        foreach ($spans as $span) {
            $id = $span->getAttribute("id");
            $value = trim($span->textContent);

            //  $data[$id] = $value;
            switch ($id) {
                case "ContentPlaceHolder1_gvDownloadsData_lblApplicationNumber_0":
                    //$lrsapp->appl_no = $value;
                    $data['appl_no'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblLAYOUTOWNERNAME_0":
                    //$lrsapp->name1 = $value;
                    $data['name1'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblAUTHORITYNAME_0":
                    // $lrsapp->name2 = $value;
                    $data['name2'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblMobileNo_0":
                    //$lrsapp->mobile = $value;
                    $data['mobile'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblSurveyNo_0":
                    //$lrsapp->survey_no = $value;
                    $data['survey_no'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblPlotNo_0":
                    //$lrsapp->plot_no = $value;
                    $data['plot_no'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblIS_LAYOUT_PLOT_0":
                    //$lrsapp->is_layout = $value;
                    $data['is_layout'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblStatus_0":
                    //$lrsapp->aprv_status = $value;
                    $data['aprv_status'] = $value;
                    break;
                case "ContentPlaceHolder1_gvDownloadsData_lblFeeStatus_0":
                    //$lrsapp->fee_status = $value;
                    $data['fee_status'] = $value;
                    break;
            }
        }
        // return $lrsapp->attributes;

        return $data;
    }

    public function getInputValue($xpath, $name)
    {
        $input = $xpath->query("//input[@name='$name']")->item(0);
        return $input ? $input->getAttribute('value') : '';
    }
}
