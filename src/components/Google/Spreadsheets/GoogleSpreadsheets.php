<?php

namespace ozerich\shop\components\Google\Spreadsheets;

use yii\base\Component;

class GoogleSpreadsheets extends Component
{
    public $enabled = false;

    public $credentials_file;

    /** @var \Google_Client */
    private $client;

    /** @var \Google_Service_Sheets */
    private $service;

    private function getTokenPath()
    {
        return \Yii::getAlias('@runtime/google_token.json');
    }

    private function createClient()
    {
        $client = new \Google_Client();

        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig($this->credentials_file);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        if (file_exists($this->getTokenPath())) {
            $accessToken = json_decode(file_get_contents($this->getTokenPath()), true);
            $client->setAccessToken($accessToken);
        }

        return $client;
    }

    public function init()
    {
        if (!is_file($this->credentials_file)) {
            return;
        }

        $this->client = $this->createClient();

        $this->service = new \Google_Service_Sheets($this->client);

        parent::init();
    }

    public function updateAccessToken()
    {
        try {
            $client = $this->createClient();

            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {
                    $authUrl = $client->createAuthUrl();
                    printf("Open the following link in your browser:\n%s\n", $authUrl);
                    print 'Enter verification code: ';
                    $authCode = trim(fgets(STDIN));

                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    if (array_key_exists('error', $accessToken)) {
                        throw new \Exception(join(', ', $accessToken));
                    }
                }

                $tokenPath = $this->getTokenPath();

                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }

            return $client->getAccessToken();
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function addSheet($spreadsheet_id, $name)
    {
        $body = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
            'requests' => array('addSheet' => array('properties' => array('title' => $name)))));

        try {
            $result = $this->service->spreadsheets->batchUpdate($spreadsheet_id, $body);
        } catch (\Google_Service_Exception $exception) {

        }
    }

    public function setSheetData($spreadsheet_id, $sheet, $data)
    {
        $range = "'" . $sheet . "'!A1";

        $valueRange = new \Google_Service_Sheets_ValueRange([
            'range' => $range,
            'values' => $data
        ]);

        $data = [$valueRange];

        $body = new \Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => 'RAW',
            'data' => $data
        ]);

        try {
            $this->service->spreadsheets_values->batchUpdate($spreadsheet_id, $body);
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function getSheetData($spreadsheet_id, $sheet = 'Лист1')
    {
        $range = "'" . $sheet . "'!A1:E";

        try {
            $response = $this->service->spreadsheets_values->get($spreadsheet_id, $range);
        } catch (\Exception $exception) {
            return null;
        }

        $values = $response->getValues();
        return $values;
    }
}