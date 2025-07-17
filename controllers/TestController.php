<?php

namespace micro\controllers;

use yii\web\Controller;
use macklus\Crawler\Crawler;
use linslin\yii2\curl;

class TestController extends Controller
{
    /*  public $modelClass = 'micro\models\Post';
    public function behaviors()
    {
        // remove rateLimiter which requires an authenticated user to work
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }*/
    public function actionCrawl()
    {

        $step1Url = "https://lrs.telangana.gov.in/layouts/Citizen_Downloads.aspx";
        $cookieFile = tempnam(sys_get_temp_dir(), 'cookie');

        // Step 1: Simulate radio button selection
        $step1Data = [
            'ctl00$ContentPlaceHolder1$rbtnappnum' => 1,

            '__EVENTTARGET' => 'ctl00$ContentPlaceHolder1$rbtnappnum',
            '__EVENTARGUMENT' => '',
        ];
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Connection: keep-alive',
            'Referer: https://lrs.telangana.gov.in/layouts/Citizen_Downloads.aspx',
        ];


        $ch = curl_init($step1Url);
        //  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
        file_put_contents('instep1.html', $initialHtml);
        curl_close($ch);
        $html = curl_exec($ch);
        file_put_contents('step1.html', $html);
        curl_close($ch);

        if (!$html) die("Step 1 failed.");

        // Step 2: Parse returned form
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        // Get the form action
        $form = $dom->getElementsByTagName('form')->item(0);
        $action = $form->getAttribute('action');
        $formUrl = 'https://lrs.telangana.gov.in/layouts/' . ltrim($action, '/');

        // Extract inputs
        $inputs = $xpath->query('//form//input');
        $formData = [];

        foreach ($inputs as $input) {
            $name = $input->getAttribute('name');
            $value = $input->getAttribute('value');
            if (!$name) continue;

            switch ($name) {
                case 'ctl00$ContentPlaceHolder1$txtappno':
                    $formData[$name] = 'M/NAGA/008896/2020';
                    break;
                default:
                    $formData[$name] = $value;
            }
        }

        // Add the submit button manually if needed
        $formData['ctl00$ContentPlaceHolder1$btnsubmit'] = 'Submit';
        $formData['__VIEWSTATE'] = "bxjPsg5B7mEgrezM14OuKSVdGeBbuHtA5pKcMKpX6cZ38yXTQ9a0ImvNUaeJQJm";
        $formData['__VIEWSTATEGENERATOR'] = "7BFF3031";
        $formData['__EVENTVALIDATION'] = "Iv7h6YKZT4SCXuoGRj40Y2ds41J5mtZ+CIIJ1XBmQpjr1VhBJddYH5AowY7Gfgy5vbQhff5RFt7PZnBCcR2wFT64iV7310FNCvCBPaGCsjf64+8LnaHWZNo6gYcAy15g/0UBz+MAlP73yhQ4D0dV+6p0IH9jrIIo0U7m8tauzJ5L9Xqn2Wx2lkaZQaAJLUCR";
        $ch = curl_init($formUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($formData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0',
        ]);

        $finalResponse = curl_exec($ch);
        curl_close($ch);

        unlink($cookieFile);

        // Output final response
        echo '<h3>Final Response:</h3>';
        echo '<pre>' . htmlspecialchars($finalResponse) . '</pre>';
    }

    public function actionCraw()
    {


        $cookieFile = tempnam(sys_get_temp_dir(), 'cookie');

        // Headers to mimic a browser
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

        function getInputValue($xpath, $name)
        {
            $input = $xpath->query("//input[@name='$name']")->item(0);
            return $input ? $input->getAttribute('value') : '';
        }

        $viewstate = getInputValue($xpath, '__VIEWSTATE');
        $eventValidation = getInputValue($xpath, '__EVENTVALIDATION');
        $viewstategen = getInputValue($xpath, '__VIEWSTATEGENERATOR');

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

        // Extract updated hidden fields again
        $viewstate = getInputValue($xpath, '__VIEWSTATE');
        $eventValidation = getInputValue($xpath, '__EVENTVALIDATION');
        $viewstategen = getInputValue($xpath, '__VIEWSTATEGENERATOR');

        // Build form data
        $formData = [
            '__VIEWSTATE' => $viewstate,
            '__VIEWSTATEGENERATOR' => $viewstategen,
            '__EVENTVALIDATION' => $eventValidation,
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            'ctl00$ContentPlaceHolder1$rbtnappnum' => '1',
            'ctl00$ContentPlaceHolder1$txtappno' => 'M/NAGA/008897/2020', // Your application number
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

        // STEP 5: Show final result (PDF link? confirmation? data?)
        echo '<h3>Final Output</h3>';
        echo '<pre>' . htmlspecialchars($finalOutput) . '</pre>';
        file_put_contents('finalOutput.html', $finalOutput);
    }
}
